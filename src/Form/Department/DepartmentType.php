<?php

namespace App\Form\Department;

use App\Entity\Department;
use App\EventListener\Department\NormalizeDepartmentListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

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
                'constraints' => [
                    new Regex(
                        pattern : '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\-]+$/',
                        message : 'Le nom du service ne doit contenir que des lettres, des chiffres, des espaces ou des tirets.',
                    ),
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
                'constraints' => [
                    new Regex(
                        pattern : "/^[A-Za-zÀ-ÖØ-öø-ÿœŒ0-9\s\-\.,'’]+$/",
                        message : 'La description ne doit contenir que des lettres, des chiffres, des espaces, des tirets, des points ou des virgules.',
                    ),
                ],
                'required' => false,
            ]);

            $builder->addEventListener(FormEvents::PRE_SUBMIT, [new NormalizeDepartmentListener(), 'onFormPreSubmit']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
