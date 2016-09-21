<?php
namespace Packt\Chp6\Http;

use Exception;
use Guzzle\Http\Message\RequestInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
use Slim\App;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Stream;
use Slim\Http\Uri;

/**
 * Simple adapter that allows Ratchet to serve Slim applications
 *
 * @package Packt\Chp6
 * @subpackage Http
 */
class SlimAdapterServer implements HttpServerInterface
{

    /**
     * @var App
     */
    private $app;

    /**
     * SlimAdapterServer constructor.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Event that is called when the connection is closed.
     *
     * Useless for HTTP connections.
     *
     * @param ConnectionInterface $conn An object representing the user's HTTP connection
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
    }

    /**
     * Event that is called when an error occurs somewhere.
     *
     * Useless for HTTP connections.
     *
     * @param ConnectionInterface $conn An object representing the user's HTTP connection
     * @param Exception           $e    The caught exception
     */
    public function onError(ConnectionInterface $conn, Exception $e)
    {
    }

    /**
     * Event that is called when a new connection is opened.
     *
     * @param ConnectionInterface $conn    An object representing the user's HTTP connection
     * @param RequestInterface    $request The HTTP request
     */
    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null)
    {
        $uri     = $request->getUrl(true);
        $psr7Uri = new Uri(
            $uri->getScheme() ?? 'http',
            $uri->getHost() ?? 'localhost',
            $uri->getPort(),
            $uri->getPath(),
            $uri->getQuery() . "",
            $uri->getFragment(),
            $uri->getUsername(),
            $uri->getPassword()
        );

        $headerValues = [];
        foreach ($request->getHeaders() as $headerName => $header) {
            $headerValues[$headerName] = $header->toArray();
        }
        $psr7Headers  = new Headers($headerValues);
        $psr7Request  = new Request(
            $request->getMethod(),
            $psr7Uri,
            $psr7Headers,
            $request->getCookies(),
            [],
            new Stream($request->getBody()->getStream())
        );
        $psr7Response = new Response(200);
        $psr7Response = $this->app->process($psr7Request, $psr7Response);

        $statusLine = sprintf(
            'HTTP/%s %d %s',
            $psr7Response->getProtocolVersion(),
            $psr7Response->getStatusCode(),
            $psr7Response->getReasonPhrase()
        );

        $headerLines = [$statusLine];

        foreach ($psr7Response->getHeaders() as $headerName => $headerValues) {
            foreach ($headerValues as $headerValue) {
                $headerLines[] = $headerName . ': ' . $headerValue;
            }
        }

        $conn->send(implode("\r\n", $headerLines) . "\r\n\r\n");

        $body = $psr7Response->getBody();
        $body->rewind();

        while (!$body->eof()) {
            $conn->send($body->read(1024));
        }
        $conn->close();
    }

    /**
     * Event that is called when a message is received from the user
     *
     * Useless for HTTP connections.
     *
     * @param ConnectionInterface $from An object representing the sender's connection
     * @param string              $msg  The message
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
    }
}