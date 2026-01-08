<?php

namespace App\Form\Skill;

use App\Entity\Skill;
use App\EventListener\Skill\NormalizeSkillListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la compétence <sup style="color : red;">*</sup>',
                'label_html' => true,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom de la compétence',
                ],
                'constraints' => [
                    new Regex(
                        pattern : '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s\-]+$/',
                        message : 'Le nom de la compétence ne doit contenir que des lettres, des chiffres, des espaces ou des tirets.',
                    ),
                ],
                'help' => 'Nom de la compétence (100 caractères maximum).',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la compétence',
                'label_html' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'Placeholder' => 'Entrez une description pour la compétence',
                    'rows' => 6,
                ],
                'constraints' => [
                    new Regex(
                        pattern : "/^[A-Za-zÀ-ÖØ-öø-ÿœŒ0-9\s\-\.,'’]+$/",
                        message : 'La description ne doit contenir que des lettres, des chiffres, des espaces, des tirets, des points ou des virgules.',
                    ),
                ],
                'help' => 'Description de la compétence (500 caractères maximum).'
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, [new NormalizeSkillListener(), 'onFormPreSubmit']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}
