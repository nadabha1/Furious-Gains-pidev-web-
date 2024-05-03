<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statutCommande')
            ->add('montantTotal')
            ->add('id_client',EntityType::class,[
                'class'=>'App\Entity\User',
                'choice_label'=>'cin',
                'placeholder'=>'choose an author'])
            ->add('idProduit',EntityType::class,[
                    'class'=>'App\Entity\Produit',
                    'choice_label'=>'marqueProduit',
                    'placeholder'=>'choose an produit']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
