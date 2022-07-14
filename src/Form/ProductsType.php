<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;

class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TypeTextType::class, [ 
                'required' => false 
                ])
            ->add('stock', NumberType::class,[ 
                'required' => false 
                ])
            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label'=> ( $options['etiqueta'])? $options['etiqueta'] : 'Guardar', 'row_attr' => ['class' => 'col-12 text-center mt-5']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
            'etiqueta' => null,
        ]);
    }
}
