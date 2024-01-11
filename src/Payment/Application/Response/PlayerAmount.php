<?php

declare(strict_types=1);

namespace App\Payment\Application\Response;

final readonly class PlayerAmount
{
    public function __construct(
        public string $paymentId,
        public string $playerId,
        public string $amount,
        public string $currency,
    ) {
    }
}
