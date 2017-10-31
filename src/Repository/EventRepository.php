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

        $query = $this->findAllQuery($queryBuilder);

        return $query->getResult();
    }

    public function findAllAsJson()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $query = $this->findAllQuery($queryBuilder);

        $collection = [];
        foreach ($query->iterate() as $row) {
            $entity            = $row[0];
            $event             = new \StdClass();
            $event->event_name = $entity->getEventName();
            $event->date_time  = $entity->getDateTime();
            array_push($collection, $event);
        }
        return $collection;
    }

    public function findOneOrCreate(
        int $foreignId,
        \DateTime $dateTime,
        string $eventName
    ): Event {
        $entity = $this->findOneBy(['foreignId' => $foreignId]);

        if (null === $entity) {
            $entity = $this->createEvent(
                $foreignId,
                $dateTime,
                $eventName
            );
        } else {
            $entity = $this->updateEvent(
                $entity,
                $foreignId,
                $dateTime,
                $eventName
            );
        }
        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    /**
     * @param int       $foreignId
     * @param \DateTime $dateTime
     * @param string    $eventName
     * @return Event
     */
    public function createEvent(
        int $foreignId,
        \DateTime $dateTime,
        string $eventName
    ): Event {
        $entity = new Event(
            $foreignId,
            $dateTime,
            $eventName
        );
        return $entity;
    }

    /**
     * @param Event     $entity
     * @param int       $foreignId
     * @param \DateTime $dateTime
     * @param string    $eventName
     * @return Event
     */
    public function updateEvent(
        Event $entity,
        int $foreignId,
        \DateTime $dateTime,
        string $eventName
    ): Event {
        $entity->setForeignId($foreignId)
               ->setDateTime($dateTime)
               ->setEventName($eventName);
        return $entity;
    }

    /**
     * @param $queryBuilder
     * @return mixed
     */
    protected function findAllQuery($queryBuilder)
    {
        $query = $queryBuilder
            ->select('e')
            ->from(Event::class, 'e')
            ->where('e.dateTime > :today')
            ->orderBy('e.dateTime', 'ASC')
            ->setParameter('today', new \DateTime('today'))
            ->getQuery();
        return $query;
    }

}