<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use Throwable;
use RuntimeException;

/**
 * @template T
 * @implements TransactionProcessor<T>
 */
readonly class TransactionManager implements TransactionProcessor
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param callable(): void $operation
     *
     * @return void
     */
    public function transactional(callable $operation): void
    {
        $this->entityManager->beginTransaction();

        try {
            $operation();
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Throwable $exception) {
            $this->entityManager->rollback();
            throw new RuntimeException($exception->getMessage());
        } finally {
            $this->entityManager->clear();
        }
    }
}
