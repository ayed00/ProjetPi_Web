<?php

namespace App\Form;

use App\Entity\Formmattion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class FormmattionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('image')
            ->add('prix')
            ->add('datede')
            ->add('datefi')

            ->add('image',FileType::class,['mapped' => false,
                'attr' => array('accept' => 'image/jpeg,image/png')])





        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formmattion::class,
        ]);
    }
}
