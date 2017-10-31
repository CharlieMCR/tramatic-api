<?php

namespace Charliemcr\Tramatic\Controllers;

use Charliemcr\Tramatic\Entity\Event;
use Doctrine\ORM\EntityManager;
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


    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->em        = $container->get('em');
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $events   = $this->em->getRepository(Event::class)->findAllAsJson();
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->withJson($events, 200, 0);
    }
}