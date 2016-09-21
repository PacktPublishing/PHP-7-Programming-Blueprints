<?php
namespace Packt\Chp5\Middleware;

use Packt\Chp5\Service\ProfileService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Stream;

class ProfileMiddleware
{

    /** @var ProfileService */
    private $profileService;

    /** @var bool */
    private $errorIfNotFound;

    public function __construct(bool $errorIfNotFound, ProfileService $profileService)
    {
        $this->profileService = $profileService;
        $this->errorIfNotFound = $errorIfNotFound;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ): ResponseInterface
    {
        $username = $request->getAttribute('route')->getArgument('username');
        $profile  = null;
        if ($this->profileService->hasProfile($username)) {
            $profile = $this->profileService->getProfile($username);
        }

        if ($profile || !$this->errorIfNotFound) {
            return $next($request->withAttribute('profile', $profile), $response);
        } else {
            $stream = new Stream(fopen('php://temp', 'w'));
            $stream->write(json_encode(['message' => 'the user "' . $username . '" does not exist']));

            return $response
                ->withStatus(404)
                ->withHeader('content-type', 'application/json')
                ->withBody($stream);
        }
    }
}