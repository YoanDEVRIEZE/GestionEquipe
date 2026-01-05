<?php

namespace App\Entity;

use App\Enum\RolesEnum;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['companyId'], message: 'Cet identifiant d\'entreprise est déjà utilisé.')]
#[UniqueEntity(fields: ['email'], message: 'Cet email d’entreprise est déjà utilisé.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Length(max : 180, maxMessage : '180 caractères maximum.')]
    #[Assert\NotBlank(message : 'L\'adresse mail professionnelle est obligatoire.')]
    #[Assert\Email(message : 'Veuillez saisir une adresse mail.')]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(enumType: RolesEnum::class)]
    #[Assert\Count(min : 1, minMessage : 'Veuillez selectionner un poste minimum.')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max : 50, maxMessage : '50 caractères maximum.')]
    #[Assert\NotBlank(message : 'Le prénom est obligatoire.')]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max : 50, maxMessage : '50 caractères maximum.')]
    #[Assert\NotBlank(message : 'Le nom est obligatoire.')]
    private ?string $lastName = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Length(max : 180, maxMessage : '180 caractères maximum.')]
    #[Assert\Email(message : 'Veuillez saisir une adresse mail.')]
    private ?string $emailPrivate = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20, maxMessage: '20 caractères maximum.')]
    #[Assert\Regex(pattern: '/^\+?[0-9\s\-\(\)]*$/', message: 'Le numéro de téléphone doit être un numéro valide.')]
    private ?string $phone = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20, maxMessage: '20 caractères maximum')]
    #[Assert\Regex(pattern: '/^\+?[0-9\s\-\(\)]*$/', message: 'Le numéro de téléphone doit être un numéro valide.')]
    private ?string $phonePro = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\Length(max: 50, maxMessage: '50 caractères maximum.')]
    #[Assert\NotBlank(message : 'L\'identifiant d\'entreprise est obligatoire.')]
    private ?string $companyId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: '255 caractères maximum.')]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: '255 caractères maximum.')]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Assert\NotNull(message : 'Veuillez selectionner un service.')]
    private ?Department $department = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = array_map(
            fn (RolesEnum $role) => $role->value,
            $this->roles
        );

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
        
        return $data;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmailPrivate(): ?string
    {
        return $this->emailPrivate;
    }

    public function setEmailPrivate(?string $emailPrivate): static
    {
        $this->emailPrivate = $emailPrivate;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhonePro(): ?string
    {
        return $this->phonePro;
    }

    public function setPhonePro(?string $phonePro): static
    {
        $this->phonePro = $phonePro;

        return $this;
    }

    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    public function setCompanyId(?string $companyId): static
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }
}
