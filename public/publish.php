<?php

include_once dirname(__FILE__) . '/../vendor/autoload.php';

$redis_client = new Predis\Client([
    'host'   => 'redis-4-0',
    'port'   => 6379,
    'scheme' => 'tcp',
]);

$redis_client->publish('test-channel', $_GET['message']);
