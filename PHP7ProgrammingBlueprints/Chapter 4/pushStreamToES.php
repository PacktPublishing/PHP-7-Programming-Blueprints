<?php

require 'vendor/autoload.php';

$client = new Elasticsearch\Client();

ob_start();
$log['body'] = array('hello' => 'world’, 'message' => 'some test');
$log['index'] = 'test';
$log['type'] = 'log';
echo json_encode($log); 

//flush output of echo into $data
$data = ob_get_flush();

$newData = json_decode($data); //turn back to array

$client->index($newData);
