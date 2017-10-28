<?php

$container = $app->getContainer();

$container['guzzle'] = function () {
    return new GuzzleHttp\Client();
};

$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger   = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
};

// Doctrine
$container['em'] = function ($c) {
    $settings = $c->get('settings');
    $config   = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $settings['doctrine']['meta']['entity_path'],
        $settings['doctrine']['meta']['auto_generate_proxies'],
        $settings['doctrine']['meta']['proxy_dir'],
        $settings['doctrine']['meta']['cache'],
        false
    );

    $em = \Doctrine\ORM\EntityManager::create($settings['doctrine']['connection'], $config);
    \Doctrine\DBAL\Types\Type::addType(
        \Ramsey\Uuid\Doctrine\UuidType::NAME,
        \Ramsey\Uuid\Doctrine\UuidType::class
    );
    $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping(
        \Ramsey\Uuid\Doctrine\UuidType::NAME,
        \Ramsey\Uuid\Doctrine\UuidType::NAME
    );

    return $em;
};

$container['serializer'] = function () {
    return \JMS\Serializer\SerializerBuilder::create()->build();
};