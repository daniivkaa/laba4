<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class,[
				'attr' => ['class' => 'form-control'],
			])
			->add('rating', ChoiceType::class, [
				'attr' => ['class' => 'form-control'],
				'choices' => [
					'Ужасно' => 1,
					'Плохо' => 2,
					'Нормально' => 3,
					'Хорошо' => 4,
					'Отлично' => 5,
				],
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
