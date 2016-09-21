<?php
namespace Packt\Chp5\Route;

use Packt\Chp5\Mapper\ProfileJsonMapping;
use Packt\Chp5\Service\ProfileService;
use Slim\Http\Request;
use Slim\Http\Response;

class DeleteProfileRoute
{

    /** @var ProfileService */
    private $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function __invoke(Request $req, Response $res, array $args): Response
    {
        $username = $args['username'];
        $this->profileService->deleteProfile($username);
        return $res->withStatus(204);
    }
}