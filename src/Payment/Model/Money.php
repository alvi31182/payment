<?php

declare(strict_types=1);

namespace App\Payment\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Money
{
    public function __construct(
        #[ORM\Column(type: 'integer', nullable: false)]
        private int $amount,
        #[ORM\Column(type: 'string', nullable: false)]
        private string $currency
    ) {
    }
}
