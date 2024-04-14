<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titreAnnonces')
            ->add('descriptionAnnonces')
            ->add('imag')
            ->add('user',EntityType::class,[
                'class'=>'App\Entity\User',
                'choice_label'=>'nom',
                'placeholder'=>'choose an user'])
        ;        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
