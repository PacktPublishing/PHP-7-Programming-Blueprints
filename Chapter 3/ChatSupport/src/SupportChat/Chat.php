<?php
namespace SupportChat;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class SupportChat implements MessageComponentInterface
{
	private $clients;
	
	public function __construct()
	{
		// initialize clients storage
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn)
	{
		// store new connection in clients
		$this->clients->attach($conn);
		printf("New connection: %s\n", $conn->resourceId);
		// send a welcome message to the client that just connected
		$conn->send(json_encode(array('type' => 'message', 'text' => 'Welcome to the test chat app!')));
	}

	public function onClose(ConnectionInterface $conn)
	{
		// remove connection from clients
		$this->clients->detach($conn);
		printf("Connection closed: %s\n", $conn->resourceId);
	}

	public function onError(ConnectionInterface $conn, Exception $error)
	{
		// display error message and close connection
		printf("Error: %s\n", $error->getMessage());
		$conn->close();
	}

	public function onMessage(ConnectionInterface $conn, $message)
	{
		// send message out to all connected clients
		foreach ($this->clients as $client) {
			$client->send($message);
		}
	}

}
