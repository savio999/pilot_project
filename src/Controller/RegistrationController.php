<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private $requestStack;   

    public function __construct(EmailVerifier $emailVerifier,RequestStack $requestStack)
    {
        $this->emailVerifier = $emailVerifier;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function index(Request $request, UserPasswordHasherInterface $passEncoder, ManagerRegistry $doctrine, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $session = $this->requestStack->getSession();
        if ($this->getUser()) {
            return $this->redirectToRoute('list.view');
        }

        $user = new User();
        $regForm = $this->createForm(RegistrationFormType::class, $user);       

        $regForm->handleRequest($request);

        if ($regForm->isSubmitted()) {
            $data = $regForm->getData();
            $password = $regForm->get('password')->getData(); 
           
            if(!empty($password)){
              $user->setPassword(
              $passEncoder->hashPassword(
                    $user,
                    $password 
                )
              );
            }
            
          
           //validation
           $errors = $validator->validate($user);
           if( ! $regForm->isValid()){
                return $this->render('registration/register.html.twig', [
                    'reg_form' => $regForm->createView(),
                    'reg_errors' => $errors,
                    'login_error' => [],
                    'login_last_username' => ''

                ]);
           }else{
                $entityManager->persist($user);
                $entityManager->flush();


                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('noreply@smartshore.nl', 'Savio'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );  
                
                $this->addFlash("success", "Verification mail sent. Please check");                
           }
        }

        //login
        $login_error = $session->get('login_error');
        $login_last_username = $session->get('login_last_username'); 

        $session->set('login_error',[]);
        $session->set('login_last_username','');
       
        return $this->render('registration/register.html.twig', [
            'reg_form' => $regForm->createView(),
            'reg_errors' => [],
            'login_error' => $login_error,
            'login_last_username' => $login_last_username
        ]);

    }


    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
       $id = $request->get('id'); 

      
       if (null === $id) {
           return $this->redirectToRoute('app_register');
       }

       $user = $userRepository->find($id);

       // Ensure the user exists in persistence
       if (null === $user) {
          return $this->redirectToRoute('app_register');
       }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified. Please login');

        return $this->redirectToRoute('app_register');
    }
}
