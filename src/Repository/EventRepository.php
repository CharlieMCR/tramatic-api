<?php

namespace Charliemcr\Tramatic\Repository;


use Charliemcr\Tramatic\Entity\Event;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Psr\Container\ContainerInterface;

class EventRepository extends EntityRepository
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     * @return EventRepository
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Finds all events from today.
     *
     * @return array
     */
    public function findAll(): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $query = $this->findAllQuery($queryBuilder);

        return $query->getResult();
    }

    /**
     * Filters all the Events from today onwards to only return the name and time of the event.
     *
     * @return array
     */
    public function findAllFiltered(): array
    {
        /**
         * @var $queryBuilder QueryBuilder
         */
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $collection = [];
        foreach ($this->findAllQuery($queryBuilder)->iterate() as $row) {
            /**
             * @var $entity Event
             */
            $entity            = $row[0];
            $event             = new \StdClass();
            $event->event_name = $entity->getEventName();
            $event->date_time  = $entity->getDateTime()->format('c');
            array_push($collection, $event);
        }
        return $collection;
    }

    /**
     * @param int       $foreignId
     * @param \DateTime $dateTime
     * @param string    $eventName
     * @return Event
     */
    public function findOneOrCreate(
        int $foreignId,
        \DateTime $dateTime,
        string $eventName
    ): Event {
        /**
         * @var $entity Event|null
         */
        $entity = $this->findOneBy(['foreignId' => $foreignId]);

        if (null === $entity) {
            return $this->createEvent(
                $foreignId,
                $dateTime,
                $eventName
            );
        }
        return $this->updateEvent(
            $entity,
            $foreignId,
            $dateTime,
            $eventName
        );
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

        $this->_em->persist($entity);
        $this->_em->flush();

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

        $this->_em->persist($entity);
        $this->_em->flush();

        return $entity;
    }

    /**
     * Finds all events from today onwards.
     *
     * @param QueryBuilder $queryBuilder
     * @return Query
     */
    protected function findAllQuery(QueryBuilder $queryBuilder): Query
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