<?php
namespace Packt\Chp5\Mapper;

use Packt\Chp5\Model\Profile;

trait ProfileJsonMapping
{

    private function profileToJson(Profile $profile): array
    {
        return [
            'username' => $profile->getUsername(),
            'givenName' => $profile->getGivenName(),
            'familyName' => $profile->getFamilyName(),
            'interests' => $profile->getInterests(),
            'birthday' => $profile->getBirthday()->format('Y-m-d')
        ];
    }

    private function profileFromJson(string $username, array $json): Profile
    {
        return new Profile(
            $json['username'] ?? $username,
            $json['givenName'],
            $json['familyName'],
            $json['passwordHash'],
            $json['interests'] ?? [],
            $json['birthday'] ? new \DateTime($json['birthday']) : NULL
        );
    }
}