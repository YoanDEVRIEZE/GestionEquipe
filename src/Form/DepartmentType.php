<?php

namespace App\Form;

use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du service',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom du service',
                    'rows' => 2,
                ],
                'required' => true,
                'constraints' => [
                    new Length(
                        max: 100
                    ),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du service',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez une description pour le service',
                    'rows' => 4,
                ],
                'required' => false,
                'constraints' => [
                    new Length(
                        max: 500
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
