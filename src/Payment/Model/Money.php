<?php

declare(strict_types=1);

namespace App\Payment\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Money
{
    /**
     * @param numeric-string $amount
     */
    public function __construct(
        #[ORM\Column(type: 'decimal', nullable: false)]
        private string $amount,
        #[ORM\Column(type: 'string', nullable: false)]
        private string $currency
    ) {
    }

    /**
     * @return numeric-string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
