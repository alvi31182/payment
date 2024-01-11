<?php

declare(strict_types=1);

namespace App\Tests\Payment\Infrastructure\Persistence;

use App\Payment\Infrastructure\Persistence\TransactionProcessor;

/**
 * @template T
 * @implements TransactionProcessor<T>
 */
class TransactionProcessorMock implements TransactionProcessor
{
    public function transactional(callable $operation): void
    {
        $operation();
    }
}
