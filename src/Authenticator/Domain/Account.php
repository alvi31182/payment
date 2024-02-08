<?php

declare(strict_types=1);

namespace App\Authenticator\Domain;

use App\Authenticator\Infrastructure\Doctrine\Repository\AccountRepository;
use App\Authenticator\Infrastructure\Doctrine\Repository\BaseDoctrineRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: BaseDoctrineRepository::class)]
#[ORM\Table(name: "Account")]
class Account implements UserInterface
{
    public function __construct(
        #[ORM\Embedded(class: UserId::class, columnPrefix: false)]
        private UserId $id,
        #[ORM\Column(type: "string", unique: true)]
        private Email $email,
        #[ORM\Column(type: "string")]
        private string $password,
        #[ORM\Column(type: "jsonb")]
        private array $roles = []
    )
    {
    }


    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email->getEmail();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return '';
    }

    #[\Override] public function getRoles(): array
    {
        return $this->roles;
    }
}