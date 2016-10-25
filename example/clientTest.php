<?php
/**
 * @since  2016-01-26
 */

define("DEBUG", false);

require __DIR__.'/../vendor/autoload.php';
use RedisRpc\Client;

$redisServer = new Redis();
$redisServer->pconnect('127.0.0.1', 6379);
$messageQueue = 'calc';
$timeout      = 1;
$calculator   = new Client($redisServer, $messageQueue, $timeout);

echo $calculator->clr();
echo $calculator->add(5);
echo $calculator->sub(3);
echo $calculator->mul(4);
echo $calculator->div(2);