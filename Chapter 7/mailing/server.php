<?php
use Packt\Chp7\Checkout\JsonRpcClient;
use React\ZMQ\SocketWrapper;

require 'vendor/autoload.php';

$loop = \React\EventLoop\Factory::create();
$ctx = new \React\ZMQ\Context($loop);

/** @var SocketWrapper $socket */
$socket = $ctx->getSocket(ZMQ::SOCKET_SUB);
$socket->subscribe("");
$socket->connect('tcp://checkout:5558');

echo "Listening on $addr\n";

$socket->on('message', function(string $msg) {
    echo "received message $msg\n";

    $data = json_decode($msg);
    if (isset($data->customer->email)) {
        $email = $data->customer->email;
        echo "sending confirmation email to $email\n";

        // mail($email, "Your order confirmation", "Insert fancy message text here...");
    }
});

$loop->run();