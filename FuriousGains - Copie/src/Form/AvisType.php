<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('produit',EntityType::class,[
                                                  'class'=>'App\Entity\Produit',
                                                  'choice_label'=>'marque_produit',
                                                  'placeholder'=>'choose an produit'])
            ->add('user',EntityType::class,[
        'class'=>'App\Entity\User',
        'choice_label'=>'nom',
        'placeholder'=>'choose an user'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
