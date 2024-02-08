<?php

declare(strict_types=1);

namespace App\Authenticator\Domain;

use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping as ORM;

#[Embeddable]
final class Email
{
    public function __construct(
        #[ORM\Column(type: "string", unique: true)]
        private string $email
    )
    {
        $this->emailValidation(email: $this->email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function emailValidation(string $email): void
    {

    }

    public function __toString(): string
    {
        return $this->email;
    }
}