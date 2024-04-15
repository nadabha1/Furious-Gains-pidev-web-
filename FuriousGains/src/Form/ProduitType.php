<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque_produit', TextType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre marque'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La marque du produit est obligatoire.']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'La marque du produit ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('quantite', NumberType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre quantité'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La quantité est obligatoire.']),
                    new Assert\PositiveOrZero(['message' => 'La quantité doit être un nombre positif ou zéro.']),
                ],
            ])
            ->add('prix_produit', NumberType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre prix'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prix est obligatoire.']),
                    new Assert\PositiveOrZero(['message' => 'Le prix doit être un nombre positif ou zéro.']),
                ],
            ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre description'],
                'constraints' => [
                    new Assert\Length([
                        'max' => 1000,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
         
             
            ->add('id_categorie', EntityType::class, [
                'class' => 'App\Entity\Categorie',
                'choice_label' => 'nom_categorie', // Utilisez le champ approprié pour le label des catégories
                'attr' => ['class' => 'form-style', 'placeholder' => 'Categorie'],
            ])
            
            ->add('image_name', FileType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre image'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
