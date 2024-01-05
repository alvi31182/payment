<?php

declare(strict_types=1);

namespace App\Payment\Model;

interface WritePaymentStorage
{
    public function createDeposit(Payment $payment): void;
}
