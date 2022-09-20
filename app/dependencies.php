<?php
$container = $app->getContainer();

$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

$container['cache'] = function ($container) {
    $redis = new Redis();

    $redis->connect($container->get('settings')['cache']['redis_host']);
    $redis->select(6);

    return $redis;
};