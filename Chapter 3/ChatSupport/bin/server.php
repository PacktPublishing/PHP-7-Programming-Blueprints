
<?php
// import namespaces
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use SupportChat\Chat;
 
// use the autoloader provided by Composer
require dirname(__DIR__) . '/vendor/autoload.php';
 
// create a websocket server
$server = IoServer::factory(
    new WsServer(
        new Chat()
    )
    , 8088
);
 
$server->run();

