<?php

namespace Charliemcr\Tramatic\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Event
 * @package Charliemcr\Tramatic\Entity
 * @ORM\Entity(repositoryClass="Charliemcr\Tramatic\Repository\EventRepository")
 * @ORM\Table()
 */
class Event
{
    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $foreignId;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $timeZone;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $eventName;

    /**
     * Event constructor.
     * @param int       $foreignId
     * @param \DateTime $dateTime
     * @param string    $eventName
     */
    public function __construct(
        int $foreignId,
        \DateTime $dateTime,
        string $eventName
    ) {
        $this->id = Uuid::uuid4();
        $this->setForeignId($foreignId)
             ->setDateTime($dateTime)
             ->setEventName($eventName);
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getForeignId(): int
    {
        return $this->foreignId;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        $this->dateTime->setTimezone(new \DateTimeZone($this->timeZone));
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return Event
     */
    public function setDateTime(\DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;
        $this->timeZone = $dateTime->getTimezone()->getName();
        return $this;
    }

    /**
     * @param int $foreignId
     * @return Event
     */
    public function setForeignId(int $foreignId): self
    {
        $this->foreignId = $foreignId;
        return $this;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     * @return $this
     */
    public function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
        return $this;
    }
}