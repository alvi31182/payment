<?php

declare(strict_types=1);

namespace App\Authenticator\Infrastructure\Doctrine\Repository;

use App\Authenticator\Domain\Account;
use App\Authenticator\Domain\Email;
use App\Authenticator\Domain\ReadAccountStorage;
use App\Authenticator\Domain\UserWriteStorage;

class PersistRepository extends BaseDoctrineRepository implements UserWriteStorage
{

    public function save(Account $user): void
    {
        $this->entityManager->persist($user);
    }
}