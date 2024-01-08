<?php

declare(strict_types=1);

namespace App\Payment\Model;

interface ReadPaymentStorage
{
    public function isPlayerIdExists(string $playerId): bool;

    public function findPlayerById(string $playerId): bool;

    public function findPaymentByPlayerId(string $playerId): ?Payment;
}
