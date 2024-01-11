<?php

declare(strict_types=1);

namespace App\Payment\Application\Query;

final readonly class GetPlayerAmountQuery
{
    public function __construct(
        public string $playerId
    ) {
    }
}
