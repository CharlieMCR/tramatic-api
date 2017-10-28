<?php

namespace Charliemcr\Tramatic\Repository;


use Charliemcr\Tramatic\Entity\Event;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;

class EventRepository extends EntityRepository
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

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

    public function findOneOrCreate(array $criteria, array $attributes)
    {
        $entity = $this->findOneBy($criteria);

        if (null === $entity) {
            $entity = new Event(
                $attributes['id'],
                new \DateTime(
                    $attributes['date'] . $attributes['time'],
                    new \DateTimeZone($this->container->get('settings')['timeZone'])
                ),
                $attributes['homeTeamName'],
                $attributes['homeTeamNo'],
                $attributes['awayTeamName'],
                $attributes['awayTeamNo']
            );
            $this->_em->persist($entity);
            $this->_em->flush();
        }

        return $entity;
    }
}