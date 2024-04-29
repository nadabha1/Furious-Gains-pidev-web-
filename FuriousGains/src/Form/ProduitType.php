<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;

use App\Form\CategorieType;

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
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z\s]*$/',
                    'message' => "La marque ne peut contenir que des lettres de l'alphabet et des espaces"
                ]),
                new Assert\Length([
                    'max' => 255,
                    'maxMessage' => "La marque ne peut pas dépasser {{ limit }} caractères"
                ])            ],
        ])
        ->add('quantite', TextType::class, [ // Utilisation de NumberType au lieu de IntegerType
            'attr' => ['class' => 'form-style', 'placeholder' => 'Votre quantité'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'La quantité est obligatoire.']),
                new Assert\GreaterThan(['value' => 0, 'message' => 'La quantité doit être supérieure à zéro.']),
                new Assert\Type(['type' => 'numeric', 'message' => 'La quantité doit être un nombre entier positif.']),
            ],
        ])
        ->add('prix_produit', TextType::class, [ // Utilisation de NumberType au lieu de TextType
            'attr' => ['class' => 'form-style', 'placeholder' => 'Votre prix'],
            'constraints' => [
                new Assert\NotBlank(['message' => 'Le prix est obligatoire.']),
                new Assert\GreaterThan(['value' => 0, 'message' => 'Le prix doit être supérieur à zéro.']),
                new Assert\Type(['type' => 'numeric', 'message' => 'Le prix du produit doit être un nombre positif.']),
            ],
        ])
            ->add('description', TextType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre description'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description est obligatoire.']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]*$/',
                        'message' => "La description ne peut contenir que des lettres de l'alphabet et des espaces"
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => "La description ne peut pas dépasser {{ limit }} caractères"
                    ])
                ],
            ])
            ->add('id_categorie', TextType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre id_categorie'],
                'constraints' => [
                    new Assert\Length([
                        'max' => 1000,
                        'maxMessage' => 'La id_categorie ne peut pas dépasser  caractères.',
                    ]),
                ],
            ])  
         
            
            ->add('image_name', FileType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre image'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
   
}
