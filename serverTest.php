<?php
/**
 * @since  2016-01-26
 */

define("DEBUG", true);

require __DIR__ . '/Server.php';
require __DIR__ . '/Calculator.php';

$redisServer = new Redis();
$redisServer->pconnect('127.0.0.1', 6379);
$messageQueue = 'calc';
$localObject  = new Calculator();
$server       = new Server($redisServer, $messageQueue, $localObject);
$server->run();