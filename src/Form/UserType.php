<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\User;
use App\Enum\RolesEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isActive', CheckboxType::class, [
                'label' => 'Activer le compte',
                'help' => 'Cochez pour autoriser l\'utilisateur à se connecter à l\'application.',
                'attr' => ['class' => 'form-check-input'],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email professionnel <sup style="color: red">*</sup>',
                'label_html' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'utilisateur@entreprise.com',
                ],
                'help' => 'L\'email professionnel sera utilisé comme identifiant de connexion.',
                'required' => true,
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom <sup style="color: red">*</sup>',
                'label_html' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Jean',
                ],
                'help' => 'Le prénom de l\'utilisateur (50 caractères maximum).',
                'required' => true,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom <sup style="color: red">*</sup>',
                'label_html' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Dupont',
                ],
                'help' => 'Le nom de l\'utilisateur (50 caractères maximum).',
                'required' => true,
            ])
            ->add('emailPrivate', EmailType::class, [
                'label' => 'Email personnel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'utilisateur@gmail.com',
                ],
                'help' => 'L\'email personnel peut être utilisé pour la récupération du mot de passe.',
                'required' => false,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone personnel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0700000000',
                ],
                'help' => 'Le numéro de téléphone personnel de l\'utilisateur.',
                'required' => false,
            ])
            ->add('phonePro', TextType::class, [
                'label' => 'Téléphone professionnel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0320000000',
                ],
                'help' => 'Le numéro de téléphone professionnel de l\'utilisateur.',
                'required' => false,
            ])
            ->add('companyId', TextType::class, [
                'label' => 'Identifiant entreprise <sup style="color: red">*</sup>',
                'label_html' => true,
                'attr' => ['class' => 'form-control'],
                'help' => 'Identifiant interne de l\'utilisateur dans l\'entreprise (50 caractères maximum).',
                'required' => true,
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '10 RUE DE PARIS 75000 PARIS',
                ],
                'help' => 'Adresse postale de l\'utilisateur (255 caractères maximum, facultatif).',
                'required' => false,
            ])
            ->add('roles', EnumType::class, [
                'class' => RolesEnum::class,
                'label' => 'Poste(s) occupé(s) <sup style="color: red">*</sup>',
                'label_html' => true,
                'multiple' => true,
                'expanded' => true,
                'choices' => [
                    RolesEnum::ROLE_USER,
                    RolesEnum::ROLE_MANAGER,
                    RolesEnum::ROLE_ADMIN,
                ],
                'choice_label' => fn (RolesEnum $choice) => $choice->label(),
                'choice_value' => fn ($choice) =>
                    $choice instanceof RolesEnum ? $choice->value : $choice,
                'attr' => ['class' => 'form-check-input'],
                'help' => 'Sélectionnez le(s) poste(s) occupé(s) par l\'utilisateur (1 minimum).',
                'required' => true,
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'label' => 'Service <sup style="color: red">*</sup>',
                'label_html' => true,
                'placeholder' => 'Sélectionner un service',
                'attr' => [
                    'class' => 'form-select',
                ],
                'help' => 'Le service auquel l\'utilisateur appartient.',
                'required' => true,
            ])
            ->add('avatar', TextType::class, [
                'label' => 'Photo de profil',
                'attr' => ['class' => 'form-control'],
                'help' => 'Sélectionnez la photo de profil de l\'utilisateur.',
                'required' => false,
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
