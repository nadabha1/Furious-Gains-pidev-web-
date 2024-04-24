<?php


namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $maxParticipants = $options['max_participants'];

        $builder
            ->add('nbPlace', IntegerType::class, [
                'constraints' => [
                    new Assert\Positive([
                        'message' => 'The number of places must be a positive integer.',
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => $maxParticipants,
                        'message' => 'The number of places cannot exceed the maximum allowed participants.',
                    ]),
                ],
                'attr' => [
                    'max' => $maxParticipants,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
        $resolver->setRequired('max_participants'); // Required option for the maximum number of participants
    }
}
