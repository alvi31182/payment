<?php

declare(strict_types=1);

namespace App\Payment\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;
use Ramsey\Uuid\Doctrine\UuidV7Generator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Embeddable]
class PaymentId
{
    /* @var non-empty-string */
    private const string UUID_VERSION = '7';

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: 'uuid', unique: true, nullable: false)]
        #[ORM\GeneratedValue(strategy: 'CUSTOM')]
        #[ORM\CustomIdGenerator(class: UuidV7Generator::class)]
        private UuidInterface|string $id
    ) {
        $this->validateUuid(uuid: $this->id->toString());
    }

    public function getId(): string
    {
        return $this->id;
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