<?php
namespace Packt\Chp6\Http;

use Exception;
use Guzzle\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;

/**
 * A simple HTTP server that says "hello"
 *
 * @package    Packt\Chp6
 * @subpackage Http
 */
class HelloWorldServer implements HttpServerInterface
{

    /**
     * Event that is called when the connection is closed.
     *
     * Useless for HTTP connections.
     *
     * @param ConnectionInterface $conn An object representing the user's HTTP connection
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
    }

    /**
     * Event that is called when an error occurs somewhere.
     *
     * Useless for HTTP connections.
     *
     * @param ConnectionInterface $conn An object representing the user's HTTP connection
     * @param Exception           $e    The caught exception
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
    }

    /**
     * Event that is called when a new connection is opened.
     *
     * @param ConnectionInterface $conn    An object representing the user's HTTP connection
     * @param RequestInterface    $request The HTTP request
     */
    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null)
    {
        $conn->send("HTTP/1.1 200 OK\r\n");
        $conn->send("Content-Type: text/plain\r\n");
        $conn->send("Content-Length: 13\r\n");
        $conn->send("\r\n");
        $conn->send("Hello World!\n");
        $conn->close();
    }

    /**
     * Event that is called when a message is received from the user
     *
     * Useless for HTTP connections.
     *
     * @param ConnectionInterface $from An object representing the sender's connection
     * @param string              $msg  The message
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
    }
}