<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Doctrine\Repository;

use App\Payment\Model\Payment;
use App\Payment\Model\PlayerId;
use App\Payment\Model\ReadPaymentStorage;
use App\Payment\Model\WritePaymentStorage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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
        $this->getEntityManager()->flush();
    }

    public function isPlayerIdExists(string $playerId): bool
    {
        return $this->sqlQueryForPaymentTable->isPlayerIdExists(new PlayerId($playerId));
    }
}
