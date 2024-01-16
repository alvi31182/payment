<?php

declare(strict_types=1);

namespace App\EventStorage\Infrastructure\Doctrine;

use App\EventStorage\Model\EventStorage;
use App\EventStorage\Model\WriteEventStorage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method EventStorage|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventStorage|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method EventStorage|null findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 *
 * @template-extends EntityRepository<EventStorage>
 */
class EventStorageRepository extends EntityRepository implements WriteEventStorage
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(className: EventStorage::class));
    }

    public function create(EventStorage $eventStorage): void
    {
        $this->getEntityManager()->persist($eventStorage);
    }
}