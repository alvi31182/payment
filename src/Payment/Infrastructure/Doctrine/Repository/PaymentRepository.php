<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Doctrine\Repository;

use App\Payment\Model\Payment;
use App\Payment\Model\ReadPaymentStorage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class PaymentRepository extends EntityRepository implements ReadPaymentStorage
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(className: Payment::class));
    }
}