<?php
namespace Packt\Chp6\Example;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use React\EventLoop\LoopInterface;
use SplObjectStorage;

/**
 * WebSocket component that sends a message to each connected user in a configurable interval.
 *
 * @package Packt\Chp6\Example
 */
class PingComponent implements MessageComponentInterface
{
    /** @var LoopInterface */
    private $loop;

    /** @var \SplObjectStorage */
    private $users;

    /**
     * PingComponent constructor.
     *
     * @param LoopInterface $loop     The event loop. Required as this component needs to register a timer function.
     * @param int           $interval The interval in which connected users should be pinged.
     */
    public function __construct(LoopInterface $loop, int $interval = 5)
    {
        $this->loop = $loop;
        $this->users = new SplObjectStorage();

        $i = 0;
        $this->loop->addPeriodicTimer($interval, function() use (&$i) {
            foreach ($this->users as $user) {
                $user->send('Ping ' . $i);
            }
            $i ++;
        });
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