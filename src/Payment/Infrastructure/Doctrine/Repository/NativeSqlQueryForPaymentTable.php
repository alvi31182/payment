<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Doctrine\Repository;

use App\Payment\Application\Response\PlayerAmount;
use App\Payment\Infrastructure\Exception\ReadPaymentQueryException;
use App\Payment\Infrastructure\Exception\UpdateDepositException;
use App\Payment\Model\PlayerId;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use PDO;
use Psr\Log\LoggerInterface;
use Throwable;

final class NativeSqlQueryForPaymentTable
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws ReadPaymentQueryException
     * @throws Exception
     */
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
                ],
                types: [
                    'playerId' => PDO::PARAM_STR,
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

    /**
     * @throws Exception
     */
    public function findById(PlayerId $playerId): bool
    {
        $connection = $this->entityManager->getConnection();

        $SQL = <<<SQL
            SELECT player_id 
                FROM payment 
            WHERE player_id = :playerId
SQL;
        $stmt = $connection->executeQuery(
            sql: $SQL,
            params: [
                'playerId' => $playerId->getId(),
            ],
            types: [
                'playerId' => PDO::PARAM_STR,
            ]
        );

        $player = $stmt->fetchOne();

        if (!$player) {
            return false;
        }

        return true;
    }

    /**
     * @throws UpdateDepositException
     * @throws Exception
     */
    public function updateAmountSumByPlayerId(PlayerId $playerId, int $amount): void
    {
        $connection = $this->entityManager->getConnection();

        try {
            $connection->beginTransaction();
            $SQL = <<<SQL
                UPDATE payment SET amount = amount + :newAmount
                    WHERE player_id = :playerId
SQL;
            $connection->executeStatement(
                sql: $SQL,
                params: [
                    'newAmount' => $amount,
                    'playerId' => $playerId->getId(),
                ],
                types: [
                    'newAmount' => PDO::PARAM_INT,
                    'playerId' => PDO::PARAM_STR,
                ]
            );
            $connection->commit();
        } catch (Throwable $exception) {
            $connection->rollBack();
            throw new UpdateDepositException($exception->getMessage());
        }
    }


    public function getAmountByPlayerId(PlayerId $playerId): PlayerAmount
    {
        $connection = $this->entityManager->getConnection();
        try {
            $SQL = <<<SQL
                SELECT 
                    id, 
                    amount, 
                    player_id, 
                    currency
                FROM payment WHERE player_id = :playerId
SQL;
            /**
             * @var array{
             *     id: string,
             *     player_id: string,
             *     amount: string,
             *     currency: string
             * } $playerAmount
             */
            $playerAmount = $connection->executeQuery(
                sql: $SQL,
                params: [
                    'playerId' => $playerId->getId(),
                ],
                types: [
                    'playerId' => PDO::PARAM_STR,
                ]
            )->fetchAssociative();


            return new PlayerAmount(
                paymentId: $playerAmount['id'],
                playerId: $playerAmount['player_id'],
                amount: $playerAmount['amount'],
                currency: $playerAmount['currency']
            );
        } catch (Throwable $exception) {
            throw new DomainException(
                message: $exception->getMessage(),
            );
        }
    }
}
