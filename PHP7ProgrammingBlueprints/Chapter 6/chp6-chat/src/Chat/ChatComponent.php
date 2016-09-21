<?php
namespace Packt\Chp6\Chat;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * WebSocket component modeling the actual chat room
 *
 * @package Packt\Chp6
 * @subpackage Chat
 */
class ChatComponent implements MessageComponentInterface
{
    /** @var \SplObjectStorage */
    private $users;

    /**
     * ChatComponent constructor.
     */
    public function __construct()
    {
        $this->users = new \SplObjectStorage();
    }

    /**
     * Event that is called when a new user connects to the WebSocket component.
     *
     * @param ConnectionInterface $conn An object representing the user's connection
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        echo "user {$conn->remoteAddress} connected.\n";
        $this->users->attach($conn);
    }

    /**
     * Event that is called when a user disconnects from the WebSocket component.
     *
     * @param ConnectionInterface $conn An object representing the user's connection
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
        echo "user {$conn->remoteAddress} disconnected.\n";
        $this->users->detach($conn);
    }

    /**
     * Event that is called when an error occurred at some time during communication
     *
     * @param ConnectionInterface $conn An object representing the user's connection
     * @param Exception           $e    The caught exception
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
    }

    /**
     * Event that is called when a message was received from a connected user
     *
     * @param ConnectionInterface $from An object representing the sender's connection
     * @param string              $msg  The received message
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        if ($msg === 'ping') {
            return;
        }

        $decoded = json_decode($msg);
        $decoded->author = $from->user->getUser();
        $msg = json_encode($decoded);

        echo "received message '$msg' from user {$from->remoteAddress}\n";
        foreach ($this->users as $user) {
            if ($user != $from) {
                $user->send($msg);
            }
        }
    }
}