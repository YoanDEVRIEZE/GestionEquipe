<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email professionnel',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => true,
            ]);

        if (!$options['edit']) {
            $builder->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir un mot de passe.'),
                    new Length(
                        min: 8,
                        max: 4096,
                        minMessage: 'Votre mot de passe doit comporter au moins {{ limit }} caractères.'
                    ),
                    new Regex(
                        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                        message: 'Le mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.'
                    ),
                    new NotCompromisedPassword(
                        message: 'Ce mot de passe a été compromis lors d’une fuite de données. Veuillez en choisir un autre.'
                    ),
                ],
            ]);
        }

        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('emailPrivate', EmailType::class, [
                'label' => 'Email personnel',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('phonePro', TextType::class, [
                'label' => 'Téléphone pro',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('companyId', TextType::class, [
                'label' => 'ID entreprise',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('position', TextType::class, [
                'label' => 'Poste',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('department', TextType::class, [
                'label' => 'Département',
                'attr' => ['class' => 'form-control'],
                'required' => true,
            ])
            ->add('avatar', TextType::class, [
                'label' => 'Avatar (URL)',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Actif',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'edit' => false,
        ]);
    }
}
