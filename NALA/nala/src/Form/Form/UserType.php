<?php

namespace App\Form\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                'label' => 'Prénom'
            ])
            ->add('lastname', null, [
                'label' => 'Nom'
            ])
            ->add('nickname', null, [
                'required' => true,
                'label' => 'Pseudonyme'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse mail'
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Super Administrateur' => 'ROLE_SUPER_ADMIN',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Moderateur' => 'ROLE_MODO',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Rôle'
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'Mot de passe'
            ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'label' => 'Ajouter une image',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',

                    ])
                ]
            ])
           // ->add('themedisplay')
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'label' => "Choisissez une date"
            ])
            //->add('updatedAt')
            //->add('likedPosts')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
