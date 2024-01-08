<?php

declare(strict_types=1);

namespace App\Tests\Payment\Infrastructure\Persistence;

use App\Payment\Infrastructure\Persistence\TransactionProcessor;

/**
 * @psalm-suppress MissingTemplateParam
 */
class TransactionProcessorMock implements TransactionProcessor
{
    public function transactional(callable $transaction): void
    {
        $transaction();
    }
}
