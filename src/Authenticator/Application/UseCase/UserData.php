<?php

declare(strict_types=1);

namespace App\Authenticator\Application\UseCase;

use Symfony\Component\Security\Core\User\UserInterface;

final readonly class UserData implements UserInterface
{
    public function __construct(
        public string $email,
        public array $roles = [],
    )
    {
    }

    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }
}