<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la catégorie',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la catégorie',
                'required' => false,
                'attr' => ['class' => 'form-control mb-3']
            ])
           // ->add('picturecategory')
             ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date de création",
                'attr' => ['class' => 'form-control mb-3']
            ])
            // ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
