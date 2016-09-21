<?php
declare(strict_types = 1);
namespace Packt\Chp5\Exception;

use Exception;

class UserNotFoundException extends Exception
{
    /**
     * @var string
     */
    private $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }
}