<?php

namespace App\Form\Team;

use App\Entity\Team;
use App\EventListener\Team\NormalizeTeamListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

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
                'constraints' => [
                    new Regex(
                        pattern : '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\-]+$/',
                        message : 'Le nom de l\'équipe ne doit contenir que des lettres, des chiffres, des espaces ou des tirets.',
                    ),
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
                'constraints' => [
                    new Regex(
                        pattern : "/^[A-Za-zÀ-ÖØ-öø-ÿœŒ0-9\s\-\.,'’]+$/",
                        message : 'La description ne doit contenir que des lettres, des chiffres, des espaces, des tirets, des points ou des virgules.',
                    ),
                ],
                'help' => 'Description de l\'équipe (500 caractères maximum).'
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [new NormalizeTeamListener(), 'onFormPreSubmit']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
