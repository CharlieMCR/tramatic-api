<?php

namespace Charliemcr\Tramatic\Repository;


use Charliemcr\Tramatic\Entity\Event;
use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function findAll()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $query = $queryBuilder
            ->select('e')
            ->from(Event::class, 'e')
            ->orderBy('e.dateTime', 'ASC')
            ->setMaxResults(1)
            ->getQuery();

        $result = $query->getResult();
        return $result;
    }
}