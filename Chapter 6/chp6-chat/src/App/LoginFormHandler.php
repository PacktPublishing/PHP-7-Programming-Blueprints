<?php
namespace Packt\Chp6\App;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Slim handler for rendering the login form
 *
 * @package Packt\Chp6
 * @subpackage App
 */
class LoginFormHandler
{
    /**
     * Handles an request for the log-in form
     *
     * @param Request  $req The HTTP request
     * @param Response $res The HTTP response
     * @return Response The HTTP response
     */
    public function __invoke(Request $req, Response $res): Response
    {
        $res->getBody()->write(file_get_contents(SERVER_DIR . '/templates/login.html'));
        return $res;
    }
}