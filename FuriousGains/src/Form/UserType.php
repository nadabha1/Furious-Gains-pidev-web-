<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cin', NumberType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre CIN'],])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre nom'],])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre prenom'],])
            ->add('dateuser', DateType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre Date de naissance'],
                'years' => range(date('Y') - 50, date('Y') - 18),])
            ->add('numTel', NumberType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre numtel'],])
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre adresse'],])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre email'],])
            ->add('password', PasswordType::class, [
                'attr' => ['class' => 'form-style', 'placeholder' => 'Votre password'],])
            ->add('image',FileType::class,['attr' => ['class' => 'form-style'],
                'label' => 'picture for your  profile (image file)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ]])
            ->add('roles',ChoiceType::class,[
                    'expanded' => false,
                    'multiple' =>  false,

                    'choices'  => [
                        'Admin' => 'ROLE_ADMIN',
                        'Client' => 'ROLE_USER',
                    ],


                ]
            )
            ->add('Ajouter', SubmitType::class, [
                'attr' => ['class' => 'btn btn-danger btn-sm'],])
        ;
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            fn ($rolesAsArray) => count($rolesAsArray) ? $rolesAsArray[0]: null,
            fn ($rolesAsString) => [$rolesAsString]
        ));
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
