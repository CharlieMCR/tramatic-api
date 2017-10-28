<?php

namespace Charliemcr\Tramatic\Controllers;

use Charliemcr\Tramatic\Entity\Event;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class ListEvents
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Container $container)
    {
        $this->container  = $container;
        $this->em         = $container->get('em');
        $this->serializer = $container->get('serializer');
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $events   = $this->em->getRepository(Event::class)->findAll();
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->write($this->serializer->serialize($events, 'json'));
    }
}