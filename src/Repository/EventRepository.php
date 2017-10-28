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
            ->where('e.dateTime > :today')
            ->orderBy('e.dateTime', 'ASC')
            ->setParameter('today', new \DateTime('today'))
            ->getQuery();

        return $query->getResult();
    }
}