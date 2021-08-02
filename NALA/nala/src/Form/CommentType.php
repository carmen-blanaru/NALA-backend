<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', null, [
                'label' => 'Le commentaire',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('display', null, [
                'label' => 'Affichage',
                'attr' => ['class' => 'form-check form-check-label']
            ])
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('user')
            // ->add('post')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
