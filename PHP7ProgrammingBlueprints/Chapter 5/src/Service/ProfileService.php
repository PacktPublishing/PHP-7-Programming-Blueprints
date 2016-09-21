<?php
declare(strict_types = 1);

namespace Packt\Chp5\Service;

use MongoDB\Collection;
use MongoDB\Model\BSONDocument;
use Packt\Chp5\Exception\UserNotFoundException;
use Packt\Chp5\Mapper\ProfileMongoMapper;
use Packt\Chp5\Model\Profile;
use Traversable;

class ProfileService
{

    /** @var Collection */
    private $profileCollection;

    public function __construct(Collection $profileCollection)
    {
        $this->profileCollection = $profileCollection;
    }

    public function getProfiles(
        array $filter = [],
        string $sorting = 'username',
        bool $sortAscending = true
    ): Traversable
    {
        $records = $this->profileCollection->find($filter, ['sort' =>
            [$sorting => $sortAscending ? 1 : -1]]
        );

        foreach ($records as $record) {
            yield $this->fromRecord($record);
        }
    }

    public function hasProfile(string $username): bool
    {
        return $this->profileCollection->count(['username' => $username]) > 0;
    }

    public function getProfile(string $username): Profile
    {
        $record = $this->profileCollection->findOne(['username' => $username]);
        if ($record) {
            return $this->fromRecord($record);
        }

        throw new UserNotFoundException($username);
    }

    public function updateProfile(string $username, Profile $profile): Profile
    {
        $record             = $this->toRecord($profile);
        $record['username'] = $username;

        $this->profileCollection->findOneAndUpdate(
            ['username' => $username],
            [
                '$set' => $record
            ]
        );
        return $profile;
    }

    public function createProfile(string $username, Profile $profile): Profile
    {
        $record             = $this->toRecord($profile);
        $record['username'] = $username;

        $this->profileCollection->insertOne($record);
        return $profile;
    }

    public function deleteProfile(string $username)
    {
        $this->profileCollection->findOneAndDelete(['username' => $username]);
    }

    public function toRecord(Profile $profile): array
    {
        return [
            'username' => $profile->getUsername(),
            'givenName' => $profile->getGivenName(),
            'familyName' => $profile->getFamilyName(),
            'passwordHash' => $profile->getPasswordHash(),
            'interests' => $profile->getInterests(),
            'birthday' => $profile->getBirthday()->format('d-m-Y')
        ];
    }

    public function fromRecord($record): Profile
    {
        return new Profile(
            $record['username'],
            $record['givenName'],
            $record['familyName'],
            $record['passwordHash'],
            $record['interests']->getArrayCopy(),
            new \DateTime($record['birthday'])
        );
    }

}