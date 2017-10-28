<?php

namespace Charliemcr\Tramatic\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use Ramsey\Uuid\Uuid;

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
     */
    public $footballWebpagesId;
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @Exclude
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="string")
     */
    private $timeZone;

    /**
     * @ORM\Column(type="string")
     */
    private $homeTeamName;

    /**
     * @ORM\Column(type="integer")
     */
    private $homeTeamNo;

    /**
     * @ORM\Column(type="string")
     */
    private $awayTeamName;

    /**
     * @ORM\Column(type="integer")
     */
    private $awayTeamNo;

    /**
     * Event constructor.
     * @param           $footballWebpagesId
     * @param \DateTime $dateTime
     * @param           $homeTeamName
     * @param           $homeTeamNo
     * @param           $awayTeamName
     * @param           $awayTeamNo
     */
    public function __construct(
        $footballWebpagesId,
        \DateTime $dateTime,
        $homeTeamName,
        $homeTeamNo,
        $awayTeamName,
        $awayTeamNo
    ) {
        $this->id                 = Uuid::uuid4();
        $this->footballWebpagesId = $footballWebpagesId;
        $this->dateTime           = $dateTime;
        $this->timeZone           = $dateTime->getTimezone()->getName();
        $this->homeTeamName       = $homeTeamName;
        $this->homeTeamNo         = $homeTeamNo;
        $this->awayTeamName       = $awayTeamName;
        $this->awayTeamNo         = $awayTeamNo;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFootballWebpagesId()
    {
        return $this->footballWebpagesId;
    }

    /**
     * @return mixed
     */
    public function getHomeTeamName()
    {
        return $this->homeTeamName;
    }

    /**
     * @return mixed
     */
    public function getHomeTeamNo()
    {
        return $this->homeTeamNo;
    }

    /**
     * @return mixed
     */
    public function getAwayTeamName()
    {
        return $this->awayTeamName;
    }

    /**
     * @return mixed
     */
    public function getAwayTeamNo()
    {
        return $this->awayTeamNo;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        $this->dateTime->setTimezone(new \DateTimeZone($this->timeZone));
        return $this->dateTime;
    }
}