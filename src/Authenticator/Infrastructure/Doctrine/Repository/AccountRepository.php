<?php

declare(strict_types=1);

namespace App\Authenticator\Infrastructure\Doctrine\Repository;

use App\Authenticator\Domain\Account;
use App\Authenticator\Domain\Email;
use App\Authenticator\Domain\ReadAccountStorage;
use App\Authenticator\Domain\UserWriteStorage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class AccountRepository extends EntityRepository implements UserWriteStorage
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(className: Account::class));
    }

    public function save(Account $user): void
    {
        $this->getEntityManager()->persist($user);
    }

}