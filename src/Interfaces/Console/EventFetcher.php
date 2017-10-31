<?php

namespace Charliemcr\Tramatic\Interfaces\Console;

use Charliemcr\Tramatic\Entity\Event;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\ClientInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventFetcher extends Command
{
    /**
     * @var ClientInterface
     *
     */
    private $client;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $events = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->client    = $container->get('guzzle');
        $this->em        = $container->get('em');
        parent::__construct();
    }

    public function configure()
    {
        parent::configure();
        $this->setName('fetch')
             ->addArgument(
                 'team',
                 InputArgument::REQUIRED,
                 'The team id to fetch. 12 for MC, 13 for MU'
             )
             ->setDescription('Get the event for football teams');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        AnnotationRegistry::registerLoader('class_exists');
        $teamId       = $input->getArgument('team');
        $this->events = $this->filterMatches($this->fetchMatches($teamId), $teamId);
        foreach ($this->events as $event) {
            $entity = $this->em->getRepository(Event::class)
                               ->setContainer($this->container)
                               ->findOneOrCreate(
                                   $event['id'],
                                   new \DateTime(
                                       $event['date'] . $event['time'],
                                       new \DateTimeZone($this->container->get('settings')['timeZone'])
                                   ),
                                   $event['homeTeamName'] . ' v ' . $event['awayTeamName']
                               );

            $output->writeln($entity->getId() . ' Event saved.');
        }
        $this->em->flush();
    }

    private function filterMatches(array $events, int $teamId)
    {
        return array_filter($events, function ($event) use ($teamId) {
            return ((int)$event['homeTeamNo'] === $teamId);
        });
    }

    private function fetchMatches($teamId): array
    {
        $url  = 'https://www.footballwebpages.co.uk/matches.json?team=' . $teamId . '&results=0';
        $res  = $this->client->request(
            'GET',
            $url
        );
        $body = $res->getBody();
        $body->rewind();
        $content = json_decode($body->getContents(), true);
        $events  = $content['matchesTeam']['match'];
        return $events;
    }

}