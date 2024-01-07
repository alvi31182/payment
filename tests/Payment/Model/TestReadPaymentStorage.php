<?php

declare(strict_types=1);

namespace App\Tests\Payment\Model;

use App\Payment\Model\Payment;
use App\Payment\Model\ReadPaymentStorage;

class TestReadPaymentStorage implements ReadPaymentStorage
{
    public function __construct(
        private Payment $payment
    ) {
    }

    public function isPlayerIdExists(string $playerId): bool
    {
        return true;
    }

    public function getSumByPlayerId(string $playerId): int
    {

        return ($playerId === $this->payment->getPlayerId()->getId())
            ? (int) $this->payment->getMoney()->getAmount()
            : 0;
    }

    public function findPlayerById(string $playerId): bool
    {
        return true;
    }

    public function findPaymentByPlayerId(string $playerId): ?Payment
    {
        return $this->payment;
    }
}
