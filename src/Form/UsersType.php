<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['data']->getPassword()){
            $builder
                ->add('username', TextType::class, [ 
                        'required' => false 
                        ])
                ->add('plainPassword', PasswordType::class, [
                    "required" => false,
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Su contraseña de tener al menos  {{ limit }} letras',
                            'max' => 4096,
                        ]),
                    ],
                ])
                ->add("roles", ChoiceType::class, [
                    "choices" => ["ROLE_USER","ROLE_ADMIN"],
                    "choice_label" => function($key, $index) {
                        return $key;
                    },
                    "expanded" => true,
                    "multiple" => true,
                    "required" => false,
                    "label" => "roles",
                ])            
                ->add('active')
                ->add('save', SubmitType::class, ['label'=> ( $options['etiqueta'])? $options['etiqueta'] : 'Guardar', 'row_attr' => ['class' => 'col-12 text-center mt-5']])
            ;
        } else {
            $builder
                ->add('username', TextType::class, [ 
                    'required' => false 
                    ])
                ->add('plainPassword', PasswordType::class, [
                    'mapped' => false,
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ingrese una contraseña',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Su contraseña de tener al menos  {{ limit }} letras',
                            'max' => 4096,
                        ]),
                    ],
                ])
                ->add("roles", ChoiceType::class, [
                    "choices" => ["ROLE_USER","ROLE_ADMIN"],
                    "choice_label" => function($key, $index) {
                        return $key;
                    },
                    "expanded" => true,
                    "multiple" => true,
                    "required" => false,
                    "label" => "roles",
                ])            
                ->add('active')
                ->add('save', SubmitType::class, ['label'=> ( $options['etiqueta'])? $options['etiqueta'] : 'Guardar', 'row_attr' => ['class' => 'col-12 text-center mt-5']])

            ;
        }
        
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
            'etiqueta' => null,
        ]);
    }
}
