<?php
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\ZMQ\SocketWrapper;

require 'vendor/autoload.php';

$loop = Factory::create();
$ctx = new Context($loop);

/** @var SocketWrapper $subSocket */
$subSocket = $ctx->getSocket(ZMQ::SOCKET_SUB);
$subSocket->subscribe("");
$subSocket->connect('tcp://checkout:5558');

$pushSocket = $ctx->getSocket(ZMQ::SOCKET_PUSH);
$pushSocket->bind('tcp://0.0.0.0:5557');

$pullSocket = $ctx->getSocket(ZMQ::SOCKET_PULL);
$pullSocket->bind('tcp://0.0.0.0:5558');

$pullSocket->on('message', function(string $msg) {
    echo "order $msg successfully processed for shipping\n";
});

$subSocket->on('message', function(string $msg) use ($pushSocket) {
    echo "dispatching checkout order $msg to workers\n";
    $pushSocket->send($msg);
});

$loop->run();