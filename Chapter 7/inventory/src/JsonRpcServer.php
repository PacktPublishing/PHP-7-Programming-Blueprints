<?php
namespace Packt\Chp7\Inventory;

use Throwable;

class JsonRpcServer
{
    /** @var \ZMQSocket */
    private $socket;

    /** @var object */
    private $server;

    public function __construct(\ZMQSocket $socket, $server)
    {
        $this->socket = $socket;
        $this->server = $server;
    }

    public function run()
    {
        while ($msg = $this->socket->recv()) {
            $json = json_decode($msg);
            if (json_last_error()) {
                printf("received unparseable request\n");
                $this->socket->send(json_encode(['error' => ['message' => 'unparseable JSON: ' . json_last_error_msg()]]));
                continue;
            }

            printf("received request for method %s\n", $json->method);
            $method = [$this->server, $json->method];
            if (is_callable($method)) {
                try {
                    $result = call_user_func_array($method, $json->params ?? []);
                    $this->socket->send(json_encode(['result' => $result]));
                } catch (Throwable $t) {
                    $this->socket->send(json_encode(['error' => ['message' => $t->getMessage()]]));
                }
            } else {
                $this->socket->send(json_encode(['error' => ['message' => 'uncallable method']]));
            }
        }
    }
}