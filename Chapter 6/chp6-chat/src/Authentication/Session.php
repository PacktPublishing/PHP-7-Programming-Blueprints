<?php
namespace Packt\Chp6\Authentication;

/**
 * Object representing a session
 *
 * @package    Packt\Chp6
 * @subpackage Authentication
 */
class Session
{
    /** @var string */
    private $user;

    /** @var string */
    private $id;

    /**
     * Session constructor.
     *
     * @param string $id   The session ID
     * @param string $user The user this session belongs to
     */
    public function __construct(string $id, string $user)
    {
        $this->user = $user;
        $this->id   = $id;
    }

    /**
     * Gets the user this session belongs to
     *
     * @return string The user
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * Gets this session's ID
     *
     * @return string The session ID
     */
    public function getId(): string
    {
        return $this->id;
    }
}