<?php

declare(strict_types=1);

namespace App\Payment\Model;

use App\Payment\Application\Command\CreatePaymentDepositCommand;
use App\Payment\Infrastructure\Doctrine\Repository\PaymentRepository;
use App\Payment\Model\Enum\AmountType;
use App\Payment\Model\Event\DomainEventRoot;
use App\Payment\Model\Event\PaymentCreated;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[Index(
    columns: ['id', 'player_id'],
    name: 'btree_payment_player_idx',
    options: ['using' => 'btree']
)]
class Payment extends DomainEventRoot
{
    public function __construct(
        #[ORM\Embedded(class: PaymentId::class, columnPrefix: false)]
        private PaymentId $id,
        #[ORM\Embedded(class: Money::class, columnPrefix: false)]
        private Money $money,
        #[ORM\Embedded(class: PlayerId::class, columnPrefix: false)]
        private PlayerId $playerId,
        #[ORM\Column(enumType: AmountType::class, type: 'string', nullable: false)]
        private AmountType $amountType,
        #[ORM\Column(type: 'timestamp', nullable: false)]
        private DateTimeImmutable $createdAt,
        #[ORM\Column(type: 'timestamp', nullable: true)]
        private ?DateTimeImmutable $updatedAt
    ) {
    }

    /**
     * @param numeric-string $pennies
     */
    public static function createDeposit(CreatePaymentDepositCommand $command, string $pennies): self
    {
        $payment = new self(
            id: PaymentId::generateUuidV7(),
            money: new Money(
                amount: $pennies,
                currency: $command->currency
            ),
            playerId: new PlayerId($command->playerId),
            amountType: AmountType::DEPOSIT,
            createdAt: (new DateTimeImmutable()),
            updatedAt: null
        );

        $payment->recordEvent(domainEvent: new PaymentCreated(
            id: $payment->getId()->getId(),
            amount: $payment->getMoney()->getAmount(),
            playerId: $payment->getPlayerId()->getId()
        ));

        return $payment;
    }

    /**
     * @param numeric-string $depositAmount
     */
    public function appendDeposit(string $depositAmount): self
    {
        $newAmount = bcadd(
            num1: $this->getMoney()->getAmount(),
            num2: $depositAmount,
            scale: 2
        );

        $this->money = new Money(
            amount: $newAmount,
            currency: $this->getMoney()->getCurrency()
        );

        $this->amountType = AmountType::DEPOSIT;
        $this->updatedAt = new DateTimeImmutable();
        return $this;
    }

    /**
     * @param numeric-string $withdrawalAmount
     */
    public function withdrawal(string $withdrawalAmount): self
    {
        $newAmount = bcsub(
            num1: $this->getMoney()->getAmount(),
            num2: $withdrawalAmount,
            scale: 2
        );

        $this->money = new Money(
            amount: $newAmount,
            currency: $this->getMoney()->getCurrency()
        );

        $this->amountType = AmountType::WITHDRAWAL;
        $this->updatedAt = new DateTimeImmutable();
        return $this;
    }

    public function getPlayerId(): PlayerId
    {
        return $this->playerId;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getId(): PaymentId
    {
        return $this->id;
    }
}
