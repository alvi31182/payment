<?php

declare(strict_types=1);

namespace App\Payment\Model;

use App\Payment\Application\Response\PlayerAmount;

interface ReadPaymentStorage
{
    public function isPlayerIdExists(string $playerId): bool;

    public function getAmountByPlayerId(string $playerId): PlayerAmount;

    public function findPlayerById(string $playerId): bool;

    public function findPaymentByPlayerId(string $playerId): ?Payment;
}
