<?php

declare(strict_types=1);

namespace App\Tests\Payment\Model;

use App\Payment\Model\Payment;
use App\Payment\Model\WritePaymentStorage;

class TestWritePaymentStorage implements WritePaymentStorage
{
    private array $deposits = [];

    public function createDeposit(Payment $payment): void
    {
        $this->deposits[] = $payment->getMoney()->getAmount();
    }

    public function appendDeposit(Payment $payment): void
    {
        // TODO: Implement updateSumAmountByPlayerId() method.
    }

    public function withdrawal(Payment $payment): void
    {

    }
}