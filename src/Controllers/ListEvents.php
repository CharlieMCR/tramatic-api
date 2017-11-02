<?php

namespace Charliemcr\Tramatic\Controllers;

use Charliemcr\Tramatic\Entity\Event;
use Charliemcr\Tramatic\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ListEvents
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EventRepository
     */
    private $repository;


    public function __construct(ContainerInterface $container)
    {
        $this->container  = $container;
        $this->em         = $container->get('em');
        $this->repository = $this->em->getRepository(Event::class);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $events   = $this->repository->findAllFiltered();
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->withJson($events, 200, 0);
    }
}