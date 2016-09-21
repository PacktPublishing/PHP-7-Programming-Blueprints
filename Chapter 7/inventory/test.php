<?php
require 'vendor/autoload.php';

$ctx  = new ZMQContext();

$sock = $ctx->getSocket(ZMQ::SOCKET_REQ);
$sock->connect('tcp://127.0.0.1:5557');

$sock->send('{"method": "takeArticle", "params": [1001]}');

$start = microtime(TRUE);
var_dump($sock->recv());
$end = microtime(TRUE);

echo "took " . ($end - $start) . " seconds\n";
