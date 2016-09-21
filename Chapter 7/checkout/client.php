<?php
declare(ticks = 1);

$request = json_encode([
    'cart' => [
        ['articlenumber' => 1000, 'amount' => 3],
        ['articlenumber' => 1001, 'amount' => 2]
    ],
    'customer' => [
        'email' => 'john.doe@example.com'
    ]
]);

pcntl_signal(SIGINT, function() {
    echo "Caught SIGINT; exiting";
    exit(0);
});

$ctx  = new ZMQContext();
$sock = $ctx->getSocket(ZMQ::SOCKET_REQ);
$sock->connect('tcp://checkout:5557');

echo "sending request $request\n";

$sock->send($request);

$result = $sock->recv();
echo "received response $result\n";