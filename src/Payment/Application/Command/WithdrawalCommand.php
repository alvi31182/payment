<?php

declare(strict_types=1);

namespace App\Payment\Application\Command;

final readonly class WithdrawalCommand
{
    /**
     * @param numeric-string $withdrawalSum
     */
    public function __construct(
        public string $withdrawalSum,
        public string $playerId
    ) {
    }
}
