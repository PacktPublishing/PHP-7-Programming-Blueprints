<?php

use Packt\Chp6\App\AuthenticationHandler;
use Packt\Chp6\App\IndexHandler;
use Packt\Chp6\App\LoginFormHandler;
use Packt\Chp6\Authentication\AuthenticationComponent;
use Packt\Chp6\Authentication\SessionProvider;
use Packt\Chp6\Chat\ChatComponent;
use Packt\Chp6\Http\HelloWorldServer;
use Packt\Chp6\Http\SlimAdapterServer;
use Ratchet\App as RatchetApp;
use Slim\App as SlimApp;

require 'vendor/autoload.php';

const SERVER_DIR = __DIR__;

$options = getopt('p:');
$port = $options['p'] ?? 8080;

$sessionProvider = new SessionProvider();

$app = new SlimApp();
$app->get('/', new IndexHandler($sessionProvider));
$app->get('/login', new LoginFormHandler());
$app->post('/authenticate', new AuthenticationHandler($sessionProvider));

$slimApp = new SlimAdapterServer($app);

echo "listening on port $port...\n";

$server = new RatchetApp('localhost', $port, '0.0.0.0');
$server->route('/', $slimApp, ['*']);
$server->route('/chat', new AuthenticationComponent($sessionProvider, new ChatComponent));
$server->route('/hello', new HelloWorldServer, ['*']);
$server->route('/users', $slimApp, ['*']);
$server->route('/login', $slimApp, ['*']);
$server->route('/authenticate', $slimApp, ['*']);
$server->run();