<?php

declare(strict_types=1);

namespace App\Authenticator\Domain;

use App\Authenticator\Infrastructure\Doctrine\Repository\AccountProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;

#[ORM\Entity(repositoryClass: AccountProfileRepository::class)]
class AccountProfile
{
    public function __construct(
        #[Id]
        #[ORM\Column(type: 'integer')]
        #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
        #[ORM\SequenceGenerator(sequenceName: 'message_seq', allocationSize: 100, initialValue: 1)]
        private int|null $id = null,
        #[ORM\Column(type: "string")]
        private string $name
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }


}