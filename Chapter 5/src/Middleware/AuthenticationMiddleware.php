<?php
namespace Packt\Chp5\Middleware;

use Packt\Chp5\Model\Profile;
use Packt\Chp5\Service\ProfileService;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthenticationMiddleware
{
    /**
     * @var ProfileService
     */
    private $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }


    public function __invoke(
        Request $request,
        Response $response,
        callable $next
    ): ResponseInterface
    {
        /** @var Profile $profile */
        $profile = $request->getAttribute('profile');

        $username = $request->getHeader('php-auth-user')[0] ?? null;
        $password = $request->getHeader('php-auth-pw')[0] ?? null;

        if (!$profile) {
            return $next($request, $response);
        }

        if (!$username || !$password) {
            return $response
                ->withHeader('www-authenticate', 'Basic realm="User authorization"')
                ->withStatus(401);
        }

        if (!$profile->isPasswordValid($password)) {
            return $response
                ->withHeader('www-authenticate', 'Basic realm="User authorization"')
                ->withStatus(401);
        }

        if ($username !== $profile->getUsername()) {
            return $response
                ->withStatus(403)
                ->withJson(['msg' => 'you are not authorized to edit this profile']);
        }

        return $next($request, $response);
    }
}