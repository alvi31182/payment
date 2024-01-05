<?php

declare(strict_types=1);

namespace App\Payment\Application\UseCase\Authorization;

final class Payload
{
    public function __construct(
        private string $playerId,
        private int $exp
    ) {
    }
}
