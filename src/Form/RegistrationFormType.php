<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
			'constraints' =>	[
				new Email([
						'message' => 'Email введен неверно',
					])
				],
			])
			->add('name', TextType::class, [
			'label' => 'Имя',
			'constraints' =>	[
				new Length([
					'min' => 2,
					'minMessage' => 'Имя должно быть больше {{ limit }} символов',
					// max length allowed by Symfony for security reasons
					'max' => 4096,
						])
				],
			])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Вы должны согласиться с правилами',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
				'type'	=>	PasswordType::class,
                'mapped' => false,
				'invalid_message'	=>	'Пароли должны совпадать',
				'first_options'	=>	['label' => 'Password'],
				'second_options'	=>	['label' => 'Confirn_Password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Пожалуйста введите пароль',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Твой пароль должен быть больше {{ limit }} символов',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}