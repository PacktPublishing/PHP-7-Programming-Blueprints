<?php
namespace Packt\Chp6\Authentication;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * A component decorator that checks authentication and terminates
 * un-authenticated WebSocket connections.
 *
 * @package    Packt\Chp6
 * @subpackage Authentication
 */
class AuthenticationComponent implements MessageComponentInterface
{

    /** @var MessageComponentInterface */
    private $wrapped;

    /** @var SessionProvider */
    private $sessionProvider;

    /**
     * AuthenticationComponent constructor.
     *
     * @param SessionProvider           $sessionProvider The session provider
     * @param MessageComponentInterface $wrapped The decorated WebSocket component
     */
    public function __construct(SessionProvider $sessionProvider, MessageComponentInterface $wrapped)
    {
        $this->wrapped         = $wrapped;
        $this->sessionProvider = $sessionProvider;
    }

    /**
     * Event that is called when a new user connects to the WebSocket component.
     *
     * This method checks if the HTTP request contains a Cookie with a valid
     * session ID. If not, the connection is terminated immediately.
     *
     * @param ConnectionInterface $conn An object representing the user's connection
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $sessionId = $conn->WebSocket->request->getCookie('session');
        if (!$sessionId || !$this->sessionProvider->hasSession($sessionId)) {
            echo "connection is not authenticated!";
            $conn->send('Not authenticated');
            $conn->close();
            return;
        }

        $user       = $this->sessionProvider->getUserBySession($sessionId);
        $conn->user = $user;

        $this->wrapped->onOpen($conn);
    }

    /**
     * Event that is called when a user disconnects from the WebSocket component.
     *
     * @param ConnectionInterface $conn An object representing the user's connection
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->wrapped->onClose($conn);
    }

    /**
     * Event that is called when an error occurred at some time during communication
     *
     * @param ConnectionInterface $conn An object representing the user's connection
     * @param Exception           $e    The caught exception
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
        $this->wrapped->onError($conn, $e);
    }

    /**
     * Event that is called when a message was received from a connected user
     *
     * @param ConnectionInterface $from An object representing the sender's connection
     * @param string              $msg  The received message
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->wrapped->onMessage($from, $msg);
    }

}