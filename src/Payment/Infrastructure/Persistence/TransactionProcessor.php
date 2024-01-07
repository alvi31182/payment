<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence;

/**
 * @template T
 */
interface TransactionProcessor
{
    /**
     * @param callable(): void $operation
     * @return void
     */
    public function transactional(callable $operation): void;
}
