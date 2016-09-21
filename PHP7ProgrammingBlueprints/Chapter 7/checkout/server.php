<?php
use Packt\Chp7\Checkout\CheckoutService;
use Packt\Chp7\Checkout\JsonRpcClient;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\ZMQ\SocketWrapper;

require 'vendor/autoload.php';

$loop = Factory::create();
$ctx  = new Context($loop);
$addr = 'tcp://0.0.0.0:5557';

/** @var SocketWrapper $socket */
$socket = $ctx->getSocket(ZMQ::SOCKET_REP);
$socket->bind($addr);

/** @var SocketWrapper $pubSocket */
$pubSocket = $ctx->getSocket(ZMQ::SOCKET_PUB);
$pubSocket->bind('tcp://0.0.0.0:5558');

$httpSocket = new \React\Socket\Server($loop);
$httpSocket->listen(8080, '0.0.0.0');

$httpServer = new \React\Http\Server($httpSocket);

echo "Listening on $addr\n";

$client          = new JsonRpcClient($ctx, 'tcp://inventory:5557');
$checkoutService = new CheckoutService($client);

$httpServer->on('request', function(\React\Http\Request $request, \React\Http\Response $response) use ($checkoutService, $pubSocket) {
    if ($request->getPath() != '/orders') {
        $response->writeHead(404, [
            'Content-Type' => 'application/json;charset=utf8'
        ]);
        $response->end(json_encode(['msg' => 'this resource does not exist']));
        return;
    }

    if ($request->getMethod() != 'POST') {
        $response->writeHead(405, [
            'Content-Type' => 'application/json;charset=utf8'
        ]);
        $response->end(json_encode(['msg' => 'this method is not allowed']));
        return;
    }

    $length = $request->getHeaders()['Content-Length'];
    $body   = '';

    $request->on('data', function(string $data) use (&$body, $length, $checkoutService, $response, $pubSocket) {
        $body .= $data;

        if (strlen($body) == $length) {
            $checkoutService->handleCheckoutOrder($body)->then(function() use ($response, $body, $pubSocket) {
                $pubSocket->send($body);
                $response->writeHead(200, [
                    'Content-Type' => 'application/json;charset=utf8'
                ]);
                $response->end(json_encode(['msg' => 'checkout order was executed']));
            }, function(\Exception $err) use ($response) {
                $response->writeHead(500, [
                    'Content-Type' => 'application/json;charset=utf8'
                ]);
                $response->end(json_encode(['msg' => $err->getMessage()]));
            });
        }
    });
    $request->on('end', function() {
        echo "all was written\n";
    });
});

$socket->on('message', function($msg) use ($ctx, $checkoutService, $pubSocket, $socket) {
    echo "received checkout order $msg\n";

    $checkoutService->handleCheckoutOrder($msg)->then(function() use ($pubSocket, $msg, $socket) {
        $pubSocket->send($msg);
        $socket->send(json_encode(['msg' => 'OK']));
    }, function(\Exception $err) use ($socket) {
        $socket->send(json_encode(['error' => $err->getMessage()]));
    });
});

$loop->run();