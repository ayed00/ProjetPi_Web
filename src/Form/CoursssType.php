<?php

namespace App\Form;

use App\Entity\Coursss;
use App\Entity\Formmattion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CoursssType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('video')
            ->add('dateajoutt')
            ->add('formations', EntityType::class, [
                'class' => Formmattion::class,
                'choice_label' => 'nom',
                'multiple' => false,
            ])



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coursss::class,
        ]);
    }
}
