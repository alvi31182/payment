<?php

declare(strict_types=1);

namespace App\Authenticator\Infrastructure\Doctrine\Repository;

use App\Authenticator\Domain\AccountProfile;
use App\Authenticator\Domain\WriteAccountProfile;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class AccountProfileRepository extends EntityRepository implements WriteAccountProfile
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(AccountProfile::class));
    }

    public function save(AccountProfile $accountProfile): void
    {
        $this->getEntityManager()->persist($accountProfile);
    }
}