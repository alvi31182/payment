<?php

declare(strict_types=1);

namespace App\EventStorage\Model;

use App\EventStorage\Application\Command\AppendEventCommand;
use App\EventStorage\Infrastructure\Doctrine\EventStorageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(
    repositoryClass: EventStorageRepository::class
)]
class EventStorage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(type: 'bigint', unique: true, nullable: false)]
    private string $id;
    public function __construct(
        #[ORM\Column(type: 'string', nullable: false)]
        private string $eventName,
        #[ORM\Column(type: 'jsonb', nullable: false)]
        private string $eventData,
        #[ORM\Column(type: 'timestamp', nullable: false)]
        private DateTimeImmutable $createdAt
    ) {
    }

    public static function appendEvent(AppendEventCommand $command): self
    {
        return new self(
            eventName: $command->eventName,
            eventData: $command->eventData,
            createdAt: new DateTimeImmutable()
        );
    }
}