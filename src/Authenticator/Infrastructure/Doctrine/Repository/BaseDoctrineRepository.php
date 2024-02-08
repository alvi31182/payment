<?php

declare(strict_types=1);

namespace App\Authenticator\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

abstract class BaseDoctrineRepository
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected LoggerInterface $logger
    )
    {
    }
}