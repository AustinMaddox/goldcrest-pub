<?php

include_once dirname(__FILE__) . '/../vendor/autoload.php';

$redis_client = new Predis\Client([
    'host'               => 'redis-4-0',
    'port'               => 6379,
    'read_write_timeout' => 0,
    'scheme'             => 'tcp',
]);

// Initialize a new pubsub consumer.
$pubsub = $redis_client->pubSubLoop();

// Subscribe to two different channels.
$pubsub->subscribe('control-channel', 'test-channel');

// Start processing the pubsub messages.
// Open a terminal and use redis-cli to push messages to the channels.
// Examples:
// ./redis-cli PUBLISH test-channel "This is a test"
// ./redis-cli PUBLISH control-channel terminate-the-loop
foreach ($pubsub as $message) {
    switch ($message->kind) {
        case 'subscribe':
            echo "Subscribed to {$message->channel}", PHP_EOL;
            break;
        case 'message':
            if ($message->channel == 'control-channel') {
                if ($message->payload == 'terminate-the-loop') {
                    echo 'Aborting pubsub loop...', PHP_EOL;
                    $pubsub->unsubscribe();
                } else {
                    echo "Received an unrecognized command: {$message->payload}.", PHP_EOL;
                }
            } else {
                echo "Received the following message from {$message->channel}:", PHP_EOL, " {$message->payload}", PHP_EOL, PHP_EOL;
            }
            break;
    }
}

// Always unset the pubsub consumer instance when you are done! The class destructor will take care of cleanups and prevent protocol desynchronizations between the client and the server.
unset($pubsub);

$version = redis_version($redis_client->info());
echo "Goodbye from Redis $version!", PHP_EOL;
