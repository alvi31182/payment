<?php

declare(strict_types=1);

namespace App\Payment\Application\Command;

final readonly class CreatePaymentDepositCommand
{
    public function __construct(
        public string $amount,
        public string $currency,
        public string $playerId
    ) {
    }
}
