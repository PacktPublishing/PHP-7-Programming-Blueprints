<?php
namespace Packt\Chp5\Model;

use DateTime;

class Profile
{
    /** @var string */
    private $username;
    /** @var string */
    private $givenName;
    /** @var string */
    private $familyName;
    /** @var string */
    private $passwordHash;
    /** @var array */
    private $interests;
    /** @var DateTime */
    private $birthday;

    public function __construct(
        string $username,
        string $givenName,
        string $familyName,
        string $passwordHash,
        array $interests = [],
        DateTime $birthday = null
    ) {
        $this->username     = $username;
        $this->givenName    = $givenName;
        $this->familyName   = $familyName;
        $this->passwordHash = $passwordHash;
        $this->interests    = $interests;
        $this->birthday     = $birthday;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getGivenName(): string
    {
        return $this->givenName;
    }

    /**
     * @return string
     */
    public function getFamilyName(): string
    {
        return $this->familyName;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return array
     */
    public function getInterests(): array
    {
        return $this->interests;
    }

    /**
     * @return DateTime
     */
    public function getBirthday(): \DateTime
    {
        return $this->birthday;
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isPasswordValid(string $password):bool
    {
        return password_verify($password, $this->passwordHash);
    }

}