<?php

namespace App\Form;

use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du service <sup style="color: red;">*</sup>',
                'label_html' => true,
                'help' => 'Le nom du service (100 caractères maximum).',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom du service',
                ],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du service',
                'help' => 'La description du service (500 caractères maximum).',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez une description pour le service',
                    'rows' => 6,
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
