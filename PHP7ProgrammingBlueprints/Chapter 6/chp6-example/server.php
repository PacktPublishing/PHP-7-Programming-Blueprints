<?php
require 'vendor/autoload.php';

use Packt\Chp6\Example\PingComponent;
use Packt\Chp6\Example\ReverseEchoComponent;
use Ratchet\App;
use React\EventLoop\Factory;

$loop = Factory::create();

$app = new App('localhost', 8080, '0.0.0.0', $loop);
$app->route('/reverse', new ReverseEchoComponent());
$app->route('/ping', new PingComponent($loop));
$app->run();
