<?php

namespace App\Form;

use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateLivraison', DateType::class, [
                'label' => 'Date de livraison',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('statutLivraison', ChoiceType::class, [
                'label' => 'Statut de livraison',
                'choices' => [
                    'En cours' => 'en_cours',
                    'Livré' => 'livre',
                    'Annulé' => 'annule',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('adresseLivraison', null, [
                'label' => 'Adresse de livraison',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('montantPaiement', MoneyType::class, [
                'label' => 'Montant de paiement',
                'currency' => 'EUR',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('modeLivraison', ChoiceType::class, [
                'label' => 'Mode de livraison',
                'choices' => [
                    'Standard' => 'standard',
                    'Express' => 'express',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('commande',EntityType::class,[
                    'class'=>'App\Entity\Commande',
                    'choice_label'=>'id_command',
                    'placeholder'=>'choose an author']
            )
            ->add('id_client',EntityType::class,[
                'class'=>'App\Entity\User',
                'choice_label'=>'cin',
                'placeholder'=>'choose an author'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}