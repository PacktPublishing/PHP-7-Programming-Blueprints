<?php
require "predis/autoload.php";
PredisAutoloader::register();

try {
    $redis = new PredisClient();
/*
    $redis = new PredisClient(array(
        "scheme" => "tcp",
        "host" => "127.0.0.1",
        "port" => 6379));
*/
    echo "Successfully connected to Redis";
}
catch (Exception $e) {
    echo "Couldn't connected to Redis";
    echo $e->getMessage();
}


$value = $redis->get("");

