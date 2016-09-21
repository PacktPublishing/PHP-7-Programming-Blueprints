<?php
namespace Packt\Chp6\App;

use Packt\Chp6\Authentication\SessionProvider;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Slim request handler that handles authentication requests
 *
 * @package Packt\Chp6
 * @subpackage App
 */
class AuthenticationHandler
{
    /** @var AuthenticationHandler */
    private $sessionProvider;

    /**
     * AuthenticationHandler constructor.
     *
     * @param SessionProvider $sessionProvider The session provider
     */
    public function __construct(SessionProvider $sessionProvider)
    {
        $this->sessionProvider = $sessionProvider;
    }

    /**
     * Handles an authentication request.
     *
     * The body must contain a `username` and `password` parameter. If the
     * credentials are valid, a new session will be registered for this user
     * and the HTTP response will contain a Cookie containing said session ID.
     *
     * @param Request  $req The HTTP request
     * @param Response $res The HTTP response
     * @return Response The HTTP response
     */
    public function __invoke(Request $req, Response $res): Response
    {
        $username = $req->getParsedBodyParam('username');
        $password = $req->getParsedBodyParam('password');

        if (!$username || !$password) {
            return $res->withStatus(403);
        }

        // TODO
        // This is obviously not a complete user verification, which would be
        // out of scope at this place.
        if (!$username == 'mhelmich' || !$password == 'secret') {
            return $res->withStatus(403);
        }

        $session = $this->sessionProvider->registerSession($username);
        return $res
            ->withHeader('Set-Cookie', "session={$session->getId()}")
            ->withRedirect('/')->write('');
    }
}