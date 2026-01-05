<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\User;
use App\Enum\RolesEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
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
            ->add('isActive', CheckboxType::class, [
                'label' => 'Activé le compte',
                'required' => false,
                'help' => 'Autorisera l\'utilisateur à se connecter à l\'application.',
                'attr' => ['class' => 'form-check-input'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email professionnel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'utilisateur@entreprise.com',
                ],
                'required' => true,
                'help' => 'L\'email professionnel sera utilisé comme identifiant de connexion.',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Jean',
                ],
                'required' => true,
                'help' => 'Le prénom de l\'utilisateur.',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Dupont',
                ],
                'required' => true,
                'help' => 'Le nom de famille de l\'utilisateur.',
            ])
            ->add('emailPrivate', EmailType::class, [
                'label' => 'Email personnel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'utilisateur@gmail.com',
                ],
                'required' => false,
                'help' => 'L\'email personnel peut être utilisé pour la récupération de mot de passe.',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone personnel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0700000000',
                ],
                'required' => false,
                'help' => 'Le numéro de téléphone personnel de l\'utilisateur.',
            ])
            ->add('phonePro', TextType::class, [
                'label' => 'Téléphone professionnel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '0320000000',
                ],
                'required' => false,
                'help' => 'Le numéro de téléphone professionnel de l\'utilisateur.',
            ])
            ->add('companyId', TextType::class, [
                'label' => 'Identifiant entreprise',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'help' => 'Identifiant interne de l\'utilisateur dans l\'entreprise.',
            ])
            ->add('adress', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '10 RUE DE PARIS 75000 PARIS',
                ],
                'required' => false,
                'help' => 'L\'adresse postale de l\'utilisateur.',
            ])
            ->add('roles', EnumType::class, [
                'class' => RolesEnum::class,
                'label' => 'Poste(s) occupé(s)',
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
                'help' => 'Les postes occupés par l\'utilisateur.',
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'label' => 'Service',
                'placeholder' => 'Sélectionner un service',
                'required' => true,
                'attr' => [
                    'class' => 'form-select',
                ],
                'help' => 'Le service auquel l\'utilisateur appartient.',
            ])
            ->add('avatar', TextType::class, [
                'label' => 'Photo de profil ',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'help' => 'Selectionner votre photo de profil de l\'utilisateur.',
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
