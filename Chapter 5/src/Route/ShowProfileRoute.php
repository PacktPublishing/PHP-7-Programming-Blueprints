<?php
namespace Packt\Chp5\Route;

use Packt\Chp5\Mapper\ProfileJsonMapping;
use Packt\Chp5\Service\ProfileService;
use Slim\Http\Request;
use Slim\Http\Response;

class ShowProfileRoute
{

    use ProfileJsonMapping;

    /** @var ProfileService */
    private $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function __invoke(Request $req, Response $res): Response
    {
        $profile = $req->getAttribute('profile');
        return $res->withJson($this->profileToJson($profile));
    }
}