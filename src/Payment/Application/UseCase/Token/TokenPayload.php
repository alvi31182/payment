<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Token;

final readonly class TokenPayload
{
    public function __construct(
        public string $playerId,
        public int $expired
    ) {
    }
}
