<?php
namespace Packt\Chp6\App;

use Packt\Chp6\Authentication\SessionProvider;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Slim request handler that renders the main chat application.
 *
 * @package Packt\Chp6
 * @subpackage App
 */
class IndexHandler
{
    /**
     * @var SessionProvider
     */
    private $sessionProvider;

    /**
     * IndexHandler constructor.
     *
     * @param SessionProvider $sessionProvider The session provider
     */
    public function __construct(SessionProvider $sessionProvider)
    {
        $this->sessionProvider = $sessionProvider;
    }

    /**
     * Handles an request for the main chat page.
     *
     * The request must contain a cookie with a valid session ID. If not, the
     * user will be redirected to the login page.
     *
     * @param Request  $req The HTTP request
     * @param Response $res The HTTP response
     * @return Response The HTTP response
     */
    public function __invoke(Request $req, Response $res): Response
    {
        $cookieParams = $req->getCookieParams();
        if (!isset($cookieParams['session'])) {
            return $res->withRedirect('/login');
        }

        $sessionId = $cookieParams['session'];
        if (!$this->sessionProvider->hasSession($sessionId)) {
            return $res->withRedirect('/login');
        }

        $res->getBody()->write(file_get_contents(SERVER_DIR . '/templates/index.html'));
        return $res;
    }
}