<?php

declare(strict_types=1);

namespace App\Payment\Application\Command;

final readonly class AddPaymentDepositCommand
{
    /**
     * @param numeric-string $appendAmount
     */
    public function __construct(
        public string $playerId,
        public string $appendAmount
    ) {
    }
}
