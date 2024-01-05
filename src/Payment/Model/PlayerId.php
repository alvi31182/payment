<?php

declare(strict_types=1);

namespace App\Payment\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

#[Embeddable]
class PlayerId
{
    /* @var non-empty-string */
    private const UUID_VERSION = '7';

    public function __construct(
        #[ORM\Column(type: 'uuid', unique: false, nullable: false)]
        private string $playerId
    ) {
        $this->validateUuid(uuid: $this->playerId);
    }

    public function getId(): string
    {
        return $this->playerId;
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
