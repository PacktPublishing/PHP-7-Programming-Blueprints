<?php
namespace Packt\Chp5\Route;

use Packt\Chp5\Mapper\ProfileJsonMapping;
use Packt\Chp5\Service\ProfileService;
use Slim\Http\Request;
use Slim\Http\Response;

class PutProfileRoute
{

    use ProfileJsonMapping;

    /** @var ProfileService */
    private $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function __invoke(Request $req, Response $res, array $args): Response
    {
        $username    = $args['username'];
        $profileJson = $req->getParsedBody();

        if (isset($profileJson['password'])) {
            $profileJson['passwordHash'] = password_hash($profileJson['password'], PASSWORD_BCRYPT);
            unset($profileJson['password']);
        }

        $profile = $this->profileFromJson($username, $profileJson);

        if ($this->profileService->hasProfile($username)) {
            $profile = $this->profileService->updateProfile($username, $profile);
            return $res->withJson($this->profileToJson($profile));
        } else {
            $profile = $this->profileService->createProfile($username, $profile);
            return $res->withJson($this->profileToJson($profile), 201);
        }
    }
}