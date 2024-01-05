<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Doctrine\Repository;

use App\Payment\Infrastructure\Exception\ReadPaymentQueryException;
use App\Payment\Model\PlayerId;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class NativeSqlQueryForPaymentTable
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {
    }

    public function isPlayerIdExists(PlayerId $playerId): bool
    {
        $connection = $this->entityManager->getConnection();

        try {
            $connection->beginTransaction();
            $SQL = <<<SQL
                SELECT 
                    player_id 
                FROM payment WHERE player_id = :playerId
SQL;

            $stmt = $connection->executeQuery(
                sql: $SQL,
                params: [
                    'playerId' => $playerId->getId(),
                ]
            );

            $result = $stmt->fetchOne();
            if (!$result) {
                return false;
            }
            $connection->commit();
        } catch (Throwable $exception) {
            $connection->rollBack();
            throw new ReadPaymentQueryException(
                $exception->getMessage()
            );
        }

        return true;
    }
}
