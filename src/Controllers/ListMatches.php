<?php

namespace Charliemcr\Tramatic\Controllers;

use GuzzleHttp\Client;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class ListMatches
{
    /**
     * @var Client
     */
    private $guzzle;

    public function __construct(Container $container)
    {
        $this->guzzle = $container['guzzle'];
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $home = array_values(
            array_filter(
                array_map(function ($match) {
                    if ($match['homeTeamName'] === 'Manchester City'
                        || $match['homeTeamName'] === 'Manchester United') {
                        return $match;
                    }
                },
                    array_merge($this->matches(12), $this->matches(13))
                )
            )
        );
        uasort($home, function ($a, $b) {
            if (strtotime($a['date']) === strtotime($b['date'])) {
                return 0;
            }
            return (strtotime($a['date']) < strtotime($b['date'])) ? -1 : 1;
        });
        $response = $response->withHeader('Content-Type', 'application/json');
        return $response->write(json_encode(
            array_values($home)
        ));
    }

    /**
     * @param $teamId
     * @return array
     */
    private function matches($teamId): array
    {
        $res = $this->guzzle->request(
            'GET',
            'https://www.footballwebpages.co.uk/matches.json?team=' . $teamId . '&results=0'
        );
        $body = $res->getBody();
        $body->rewind();
        $content = json_decode($body->getContents(), true);
        $matches = $content['matchesTeam']['match'];
        return $matches;
    }
}