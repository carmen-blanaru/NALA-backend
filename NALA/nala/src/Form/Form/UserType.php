<?php

namespace App\Form\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('nickname', null, [
                'required' => true
            ])
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Super Administrateur' => 'ROLE_SUPER_ADMIN',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Moderateur' => 'ROLE_MODO',
                ],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('password', PasswordType::class, [
                'required' => true
            ])
            ->add('picture')
            ->add('themedisplay')
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text'
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
