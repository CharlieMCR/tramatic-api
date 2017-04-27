<?php


$app->get('/ping', function ($request, $response, $args) {
    // Render index view
    return $response->withJson(['ack' => time()]);
});