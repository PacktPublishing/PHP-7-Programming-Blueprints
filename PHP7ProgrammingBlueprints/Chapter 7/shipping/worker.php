<?php
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\ZMQ\SocketWrapper;

require 'vendor/autoload.php';

$loop = Factory::create();
$ctx = new Context($loop);

/** @var SocketWrapper $pushSocket */
$pushSocket = $ctx->getSocket(ZMQ::SOCKET_PUSH);
$pushSocket->connect('tcp://shippingmaster:5558');

/** @var SocketWrapper $pullSocket */
$pullSocket = $ctx->getSocket(ZMQ::SOCKET_PULL);
$pullSocket->connect('tcp://shippingmaster:5557');
$pullSocket->on('message', function(string $msg) use ($pushSocket) {
    echo "processing checkout order for shipping: $msg\n";
    sleep(5);
    $pushSocket->send($msg);
});

$loop->run();