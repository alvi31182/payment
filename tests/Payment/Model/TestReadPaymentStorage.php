<?php

declare(strict_types=1);

namespace App\Tests\Payment\Model;

use App\Payment\Application\Response\PlayerAmount;
use App\Payment\Model\Payment;
use App\Payment\Model\ReadPaymentStorage;

class TestReadPaymentStorage implements ReadPaymentStorage
{
    public function __construct(
        private array $data = [],
        private readonly ?Payment $payment = null
    ) {
    }

    public function isPlayerIdExists(string $playerId): bool
    {
        return true;
    }

    public function findPlayerById(string $playerId): bool
    {
        return true;
    }

    public function findPaymentByPlayerId(string $playerId): ?Payment
    {
        return $this->payment;
    }

    public function getAmountByPlayerId(string $playerId): PlayerAmount
    {
        $data = [
            'paymentId' => '018ce318-f96c-72ec-be26-0c05e12da0b2',
            'playerId' => '018ce31c-a470-7078-93c5-8ccfe56047e9',
            'amount' => '277908',
            'currency' => 'RUB',
        ];

        return new PlayerAmount(
            $data['paymentId'],
            $playerId,
            $data['amount'],
            $data['currency']
        );
    }
}
