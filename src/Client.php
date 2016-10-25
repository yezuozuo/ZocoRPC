<?php

namespace RedisRpc;

/**
 * RPC 客户端
 *
 * Class Client
 *
 * @package RedisRpc
 */
class Client {

    /**
     * @var \Redis
     */
    private $redisServer;

    /**
     * @var
     */
    private $messageQueue;

    /**
     * @param     $redisServer
     * @param     $messageQueue
     * @param int $timeout
     */
    public function __construct($redisServer, $messageQueue, $timeout = 0) {
        $this->redisServer  = $redisServer;
        $this->messageQueue = $messageQueue;
        $this->timeout      = $timeout;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments) {
        /**
         * Construct the RPC Request message from the $name and $arguments.
         */
        $functionCall = array('name' => $name);
        if (count($arguments) > 0) {
            $functionCall['args'] = $arguments;
        }
        $responseQueue = "$this->messageQueue:rpc:" . randomString(8);
        $rpcRequest    = array(
            'functionCall'  => $functionCall,
            'responseQueue' => $responseQueue
        );
        $message       = json_encode($rpcRequest);
        debug_print("RPC Request: $message");

        /**
         * Send the RPC Request to Redis.
         */
        $this->redisServer->rPush($this->messageQueue, $message);

        /**
         * Block on the RPC Response from Redis.
         */
        $result = $this->redisServer->blPop($responseQueue,$this->timeout);
        if ($result == null) {
            echo 'empty result';
            exit;
        }
        list($messageQueue, $message) = $result;
        debug_print("RPC Response: $message\n");

        /**
         * Extract the return value.
         */
        $rpcResponse = json_decode($message);
        if (array_key_exists('exception', $rpcResponse) && $rpcResponse->exception != null) {
            echo 'exception: ' . $rpcResponse->exception;
        }
        if (!array_key_exists('value', $rpcResponse)) {
            echo 'value not exists';
        }

        return $rpcResponse->value;
    }
}

