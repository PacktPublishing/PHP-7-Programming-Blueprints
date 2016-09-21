<?php
use Packt\Chp7\Inventory\InventoryService;
use Packt\Chp7\Inventory\JsonRpcServer;

require 'vendor/autoload.php';

$args = getopt('p:', ['port=']);
$ctx = new ZMQContext();

$port = $args['p'] ?? $args['port'] ?? 5557;
$addr = 'tcp://0.0.0.0:' . $port;

$sock = $ctx->getSocket(ZMQ::SOCKET_REP);
$sock->bind($addr);

echo "Listening on $addr\n";

$service = new InventoryService();

$server = new JsonRpcServer($sock, $service);
$server->run();
