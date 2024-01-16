<?php

namespace App\Payment\Infrastructure\Persistence;

use React\Promise\PromiseInterface;

interface AsyncTransactionManager
{
    /**
     * @param callable(): PromiseInterface $transaction
     */
    public function transactional(callable $transaction): PromiseInterface;
}