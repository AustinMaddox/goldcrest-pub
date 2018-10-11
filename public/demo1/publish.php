<?php

header('Content-Type: application/json; charset=UTF-8', true, 200);

include_once dirname(__FILE__) . '/../../vendor/autoload.php';

$redis_client = new Predis\Client([
    'host'   => 'redis-4-0',
    'port'   => 6379,
    'scheme' => 'tcp',
]);

$redis_client->publish('channel-for-demos', $_GET['message']);

echo json_encode([
    'message' => $_GET['message'],
]);
