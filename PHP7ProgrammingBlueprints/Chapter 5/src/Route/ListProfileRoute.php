<?php
namespace Packt\Chp5\Route;

use Packt\Chp5\Mapper\ProfileJsonMapping;
use Packt\Chp5\Service\ProfileService;
use Slim\Http\Request;
use Slim\Http\Response;

class ListProfileRoute
{

    use ProfileJsonMapping;

    /** @var ProfileService */
    private $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $sort = $request->getQueryParam('sort', 'username');

        $profiles     = $this->profileService->getProfiles($request->getQueryParams(), $sort);
        $profilesJson = [];

        foreach ($profiles as $profile) {
            $profilesJson[] = $this->profileToJson($profile);
        }

        return $response->withJson($profilesJson);
    }
}