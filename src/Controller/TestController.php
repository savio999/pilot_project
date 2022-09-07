<?php

namespace App\Controller;

use PHPUnit\Util\Xml\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestController extends AbstractController
{
   
    /**
     * @Route("/test", name="app_test")
     */
    public function index(Request $request, ValidatorInterface $validator): Response
    {
        $form = $this->createFormBuilder([],
            [
                'attr' => [
                    'novalidate' => 'novalidate',
                    'id' => 'personal_data',
                    'data-testid' => 'personal_data',
                ]
            ]
        )
            ->add('first_name',TextType::class,
            [
                'label' => 'First Name',
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Please enter first name'
                    ])
                ],
                'empty_data' => '',
                'attr' => ['data-testid' => 'personal_data_first_name']
            ])
            ->add('gender', ChoiceType::class,
            [
                'label' => 'Gender',
                'constraints' =>[
                    new NotBlank([
                        'message' => 'Please select'
                    ])
                ],
                'expanded' => true,
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female'
                ],
                'attr' => ['data-testid' => 'personal_data_gender']
            ])
            ->add('hobbies', ChoiceType::class,
            [
                'choices' => [
                    'Listen music/watch TV' => 'music_tv',
                    'Sports' => 'sports',
                    'Travel' => 'travel'
                ],
                'expanded' => true,
                'multiple' => true,
                'attr' => ['data-testid' => 'personal_data_hobbies']
            ])
            ->add('work_environment', ChoiceType::class,
            [
                'choices' => [
                    'Select' => '',
                    'WFH' => 'wfh',
                    'Office' => 'office',
                    'Hybrid' => 'hybrid'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Select any one'
                    ])
                ],
                'attr' => ['data-testid' => 'personal_data_work_environment']
            ])
            ->add('pdf_file',FileType::class,
            [
                'constraints' => [
                    new File([
                        //'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ]),
                    new NotBlank([
                        'message' => 'Select pdf file'
                    ])
                ],
                'attr' => [
                    'data-testid' => 'pdf_file'
                    ]
            ])
            ->add('Save', SubmitType::class,[
                'attr' => ['data-testid' => 'personal_data_submit']
            ])
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $errors = $validator->validate($form);
            if(! $form->isValid() ){
                return $this->render('test/index.html.twig',[
                    'form' => $form->createView(),
                    'errors' => $errors
                ]);
            }else{
                //do processing
                $this->addFlash("success","Form sent successfully");
            }
        }

        return $this->render('test/index.html.twig', [
            'form' => $form->createView(),
            'errors' => []
        ]);
    }
}
