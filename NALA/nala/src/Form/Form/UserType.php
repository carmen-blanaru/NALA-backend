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
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('lastname', null, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('nickname', null, [
                'required' => true,
                'label' => 'Pseudonyme',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Adresse mail',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Super Administrateur' => 'ROLE_SUPER_ADMIN',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Moderateur' => 'ROLE_MODO',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'list-group-horizontal list-group-item-info mb-3'],
                'label' => 'Rôle'
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('picture', FileType::class, [
                'data_class' => null,
                'label' => 'Ajouter un avatar',
                'required' => false,
                'attr' => ['class' => 'form-control mb-3'],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',

                    ])
                ]
            ])
           // ->add('themedisplay')
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date de création",
                'attr' => ['class' => 'form-control mb-3']
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
