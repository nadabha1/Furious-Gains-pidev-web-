<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomEvent', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'message' => 'The name must contain only letters.',
                    ]),
                ],
            ])
            ->add('lieuEvent', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6]),
                ],
            ])
            ->add('prixEvent', NumberType::class, [
                'constraints' => [
                    new Assert\Positive(),
                ],
            ])
            ->add('nbParticipation', IntegerType::class, [
                'constraints' => [
                    new Assert\Positive(),
                ],
            ])
            ->add('dateEvent', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new Assert\GreaterThan('today'),
                ],
            ])
            ->add('heureEvent', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/',
                        'message' => 'The time format is invalid.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Assert\Length(['min' => 10]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Evenement',
        ]);
    }
}
