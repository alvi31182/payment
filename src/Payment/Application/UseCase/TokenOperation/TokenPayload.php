<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\TokenOperation;

final readonly class TokenPayload
{
    public function __construct(
        public string $playerId,
        public int $expired
    ) {
    }
}
