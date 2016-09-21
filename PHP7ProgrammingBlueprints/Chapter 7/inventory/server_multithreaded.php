<?php
use Packt\Chp7\Inventory\InventoryService;
use Packt\Chp7\Inventory\JsonRpcServer;

require 'vendor/autoload.php';

$args = getopt('p:w:', ['port=','workers=']);

$workers = $args['w'] ?? $args['workers'] ?? 4;

$port = $args['p'] ?? $args['port'] ?? 5557;
$addr = 'tcp://0.0.0.0:' . $port;

function worker(int $i)
{
    $ctx = new ZMQContext();

    $sock = $ctx->getSocket(ZMQ::SOCKET_REP);
    $sock->connect('ipc://workers.ipc');

    $service = new InventoryService();

    $server = new JsonRpcServer($sock, $service, 'inventory-' . $i);
    $server->run();
}

for ($i = 0; $i < $workers; $i ++) {
    $pid = pcntl_fork();
    if ($pid == 0) {
        worker($i);
        exit();
    }
}

$ctx = new ZMQContext();

//  Socket to talk to clients
$clients = $ctx->getSocket(ZMQ::SOCKET_ROUTER);
$clients->bind($addr);

//  Socket to talk to workers
$workers = $ctx->getSocket(ZMQ::SOCKET_DEALER);
$workers->bind("ipc://workers.ipc");

//  Connect work threads to client threads via a queue
$device = new ZMQDevice($clients, $workers);
$device->run();