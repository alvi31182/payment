<?php

declare(strict_types=1);

namespace App\Payment\Model;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Infrastructure\Doctrine\Repository\PaymentRepository;
use App\Payment\Model\Enum\AmountType;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    public function __construct(
        #[ORM\Embedded(class: PaymentId::class, columnPrefix: false)]
        private PaymentId $id,
        #[ORM\Embedded(class: Money::class, columnPrefix: false)]
        private Money $deposit,
        #[ORM\Embedded(class: PlayerId::class, columnPrefix: false)]
        private PlayerId $playerId,
        #[ORM\Column(enumType: AmountType::class, type: 'string', nullable: false)]
        private AmountType $amountType,
        #[ORM\Column(type: 'timestamp', nullable: false)]
        private DateTimeImmutable $createdAt,
        #[ORM\Column(type: 'timestamp', nullable: true)]
        private ?int $updatedAt
    ) {
    }

    public static function createDeposit(CreatePaymentDepositCommand $command): self
    {
        return new self(
            id: PaymentId::generateUuidV7(),
            deposit: new Money(
                amount: $command->amount,
                currency: $command->currency
            ),
            playerId: new PlayerId($command->playerId),
            amountType: AmountType::DEPOSIT,
            createdAt: (new DateTimeImmutable()),
            updatedAt: null
        );
    }
}
