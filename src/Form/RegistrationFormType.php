<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username',TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter username'
                    ])
                ],
                'label' => 'Username',
                'empty_data' => ''
            ])
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter email'
                    ]),
                    new Email([
                        'message' => 'Your email doesn\'t seems to be valid'
                    ]),
                ],
                'label' => 'Email',
                'empty_data' => ''
            ])
            ->add('password',RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
                'required' => true,
                'invalid_message' => 'Passwords don\'t match',
                'constraints' => [
                    new NotBlank(['message' => 'Password should not be blank.']),
                    new Length(array(
                        'min' => 6,
                        'max' => 40,
                        'minMessage' => "Password must be at least {{ limit }} characters long.",
                        'maxMessage' => "Password cannot be longer than {{ limit }} characters.",
                    ))],
            ])
            ->add("Signup", SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'id' => 'registration_form',
                'novalidate' => 'novalidate'
            ],
        ]);
    }
}
