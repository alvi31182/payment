<?php

declare(strict_types=1);

namespace App\Payment\Model;

use App\Payment\Infrastructure\Doctrine\Repository\PaymentRepository;
use App\Payment\Model\Enum\AmountType;
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
        private int $createdAt,
        #[ORM\Column(type: 'timestamp', nullable: true)]
        private ?int $updatedAt
    )
    {
    }
}