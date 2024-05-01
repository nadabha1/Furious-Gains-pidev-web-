<?php

namespace App\Form;

use App\Entity\Livraison;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateLivraison', DateType::class, [
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => new \DateTime(),
                        'message' => 'La date de livraison doit être la date actuelle ou une date future.',
                    ]),
                ],
            ])
            ->add('statutLivraison')
            ->add('adresseLivraison')
            ->add('montantPaiement')
            ->add('modeLivraison')
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
