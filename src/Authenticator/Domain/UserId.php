<?php

declare(strict_types=1);

namespace App\Authenticator\Domain;

use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;
use Ramsey\Uuid\Doctrine\UuidV7Generator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;

#[Embeddable]
final class UserId
{
    /* @var non-empty-string */
    private const UUID_VERSION = '7';

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true, nullable: false)]
        #[ORM\GeneratedValue(strategy: 'CUSTOM')]
        #[ORM\CustomIdGenerator(class: UuidV7Generator::class)]
        private UuidInterface $id
    )
    {
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public static function generateUuidV7(): self
    {
        return new self(Uuid::uuid7());
    }

    private function validateUuid(string $uuid): void
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgumentException("Invalid UUID format from PaymentId." . $uuid);
        }

        if (substr($uuid, 14, 1) !== self::UUID_VERSION) {
            throw new InvalidArgumentException("Invalid UUID version from PaymentId. Must be version 7.");
        }
    }
}