<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Persistence;

use Doctrine\ORM\EntityManagerInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;

class AsyncTransactionProcessor implements AsyncTransactionManager
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param callable(): PromiseInterface $transaction
     */
    public function transactional(callable $transaction): PromiseInterface
    {
        $deferred = new Deferred();
        $this->entityManager->beginTransaction();

        $transaction()->then(
            function ($result) use ($deferred) {
                $this->entityManager->flush();
                $this->entityManager->commit();
                $deferred->resolve($result);
            },
            function (\Throwable $exception) use ($deferred) {
                $this->entityManager->rollback();
                $deferred->reject(new \RuntimeException($exception->getMessage()));
            }
        )->finally(
            function (){
                $this->entityManager->clear();
            }
        );

        return $deferred->promise();
    }
}