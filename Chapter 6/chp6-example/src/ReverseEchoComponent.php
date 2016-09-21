<?php
namespace Packt\Chp6\Example;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

/**
 * WebSocket component that echoes all received messages back to their sender, in reverse.
 *
 * @package Packt\Chp6\Example
 */
class ReverseEchoComponent implements MessageComponentInterface
{
    /** @var \SplObjectStorage */
    private $users;

    /**
     * ReverseEchoComponent constructor.
     */
    public function __construct()
    {
        $this->users = new SplObjectStorage();
    }

    /**
     * Event function that is called when a new user connects to the WebSocket server.
     *
     * @param ConnectionInterface $conn An object representing the user's connection
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        echo "new connection from {$conn->remoteAddress}\n";
        $this->users->attach($conn);
    }

    /**
     * Event function that is called when a user disconnects from the WebSocket server.
     *
     * @param ConnectionInterface $conn An object representing the user's connection
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
        echo "connection closed by {$conn->remoteAddress}\n";
        $this->users->detach($conn);
    }

    /**
     * Event function that is called when a user sends a message to the WebSocket server.
     *
     * @param ConnectionInterface $sender An object representing the sender's connection
     * @param string              $msg    The message as string
     * @return void
     */
    public function onMessage(ConnectionInterface $sender, $msg)
    {
        echo "received message '$msg' from {$sender->remoteAddress}\n";
        $response = strrev($msg);
        $sender->send($response);
    }

    /**
     * Event function that is called when an error occurs during handshake or an event.
     *
     * @param ConnectionInterface $conn An object representing the sender's connection
     * @param Exception           $err  The caught exception.
     * @return void
     */
    public function onError(ConnectionInterface $conn, Exception $err)
    {
    }
}