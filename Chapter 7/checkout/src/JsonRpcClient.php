<?php
namespace Packt\Chp7\Checkout;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\ZMQ\Context;
use React\ZMQ\SocketWrapper;
use ZMQ;

class JsonRpcClient
{
    /** @var Context */
    private $context;

    /** @var string */
    private $url;

    public function __construct(Context $context, string $url)
    {
        $this->context = $context;
        $this->url = $url;
    }

    public function request(string $method, array $args = []): PromiseInterface
    {
        $body = json_encode([
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $args
        ]);

        $deferred = new Deferred();

        /** @var SocketWrapper $sock */
        $sock = $this->context->getSocket(ZMQ::SOCKET_REQ);
        $sock->connect($this->url);
        $sock->on('message', function(string $response) use ($deferred) {
            $response = json_decode($response);

            if (isset($response->result)) {
                $deferred->resolve($response->result);
            } elseif (isset($response->error)) {
                $deferred->reject(new \Exception($response->error->message, $response->error->code));
            } else {
                $deferred->reject(new \Exception('invalid response'));
            }

        });

        $sock->send($body);

        return $deferred->promise();
    }
}
