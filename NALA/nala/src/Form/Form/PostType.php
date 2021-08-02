<?php

namespace App\Form\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title', null, [
                'required' => true,
                'label' => 'Titre',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('display', null, [
                'label' => 'Affichage',
                'attr' => ['class' => 'mb-3']
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
