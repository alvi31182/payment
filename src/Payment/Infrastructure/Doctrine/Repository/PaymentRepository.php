<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Doctrine\Repository;

use App\Payment\Application\Response\PlayerAmount;
use App\Payment\Infrastructure\Exception\ReadPaymentQueryException;
use App\Payment\Model\Payment;
use App\Payment\Model\PlayerId;
use App\Payment\Model\ReadPaymentStorage;
use App\Payment\Model\WritePaymentStorage;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Payment|null findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 *
 * @template-extends EntityRepository<Payment>
 */
final class PaymentRepository extends EntityRepository implements ReadPaymentStorage, WritePaymentStorage
{
    public function __construct(
        private readonly NativeSqlQueryForPaymentTable $sqlQueryForPaymentTable,
        EntityManagerInterface $em
    ) {
        parent::__construct($em, $em->getClassMetadata(className: Payment::class));
    }

    public function createDeposit(Payment $payment): void
    {
        $this->getEntityManager()->persist($payment);
    }

    public function withdrawal(Payment $payment): void
    {
        $this->getEntityManager()->persist($payment);
    }

    public function appendDeposit(Payment $payment): void
    {
        $this->getEntityManager()->persist($payment);
    }

    public function getAmountByPlayerId(string $playerId): PlayerAmount
    {
        return $this->sqlQueryForPaymentTable->getAmountByPlayerId(new PlayerId($playerId));
    }

    public function findPaymentByPlayerId(string $playerId): ?Payment
    {
        return $this->findOneBy(['playerId.playerId' => $playerId]);
    }

    /**
     * @throws ReadPaymentQueryException
     * @throws Exception
     */
    public function isPlayerIdExists(string $playerId): bool
    {
        return $this->sqlQueryForPaymentTable->isPlayerIdExists(new PlayerId($playerId));
    }

    /**
     * @throws Exception
     */
    public function findPlayerById(string $playerId): bool
    {
        return $this->sqlQueryForPaymentTable->findById(playerId: new PlayerId($playerId));
    }
}
