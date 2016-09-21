<?php
namespace Packt\Chp6\Authentication;

/**
 * A class for managing sessions
 *
 * @package Packt\Chp6
 * @subpackage Authentication
 */
class SessionProvider
{
    /** @var array */
    private $users = [];

    /**
     * Checks if a session with a given ID exists.
     *
     * @param string $sessionId The session ID
     * @return bool `true`, when a session with the given ID exists, otherwise `false`
     */
    public function hasSession(string $sessionId): bool
    {
        return array_key_exists($sessionId, $this->users);
    }

    /**
     * Gets the session associated with a session ID
     *
     * @param string $sessionId The session ID
     * @return Session The session associated with the ID
     */
    public function getUserBySession(string $sessionId): Session
    {
        return $this->users[$sessionId];
    }

    /**
     * Registers a new user session
     *
     * @param string $user The user for whom to register the session
     * @return Session The new session
     */
    public function registerSession(string $user): Session
    {
        $id = sha1(random_bytes(64));
        return $this->users[$id] = new Session($id, $user);
    }
}