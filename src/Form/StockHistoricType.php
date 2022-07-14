<?php

namespace App\Form;

use App\Entity\Products;
use App\Entity\StockHistoric;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockHistoricType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stock')
            ->add('user', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'username',
            ])            
            ->add('product', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
            ])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StockHistoric::class,
        ]);
    }
}
