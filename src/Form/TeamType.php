<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'équipe <sup style="color : red;">*</sup>',
                'label_html' => true,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom de l\'équipe',
                ],
                'help' => 'Nom de l\'équipe (100 caractères maximum).',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'équipe',
                'label_html' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'Placeholder' => 'Entrez une description pour l\'équipe',
                    'rows' => 6,
                ],
                'help' => 'Description de l\'équipe (500 caractères maximum).'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
