<?php
/**
 * @since  2016-01-26
 */

require __DIR__ . '/FunctionCall.php';

if (!function_exists("debug_print")) {
    if (defined('DEBUG') && true === DEBUG) {
        function debug_print($string, $flag = null) {
            if (!(false === $flag)) {
                print $string . "\n";
            }
        }
    } else {
        function debug_print($string, $flag = null) {
        }
    }
}

class Server {
    /**
     * @var Redis
     */
    private $redisServer;

    /**
     * @var string
     */
    private $messageQueue;

    /**
     * @var mixed
     */
    private $localObject;

    /**
     * @param $redisServer
     * @param $messageQueue
     * @param $localObject
     */
    public function __construct($redisServer, $messageQueue, $localObject) {
        $this->redisServer  = $redisServer;
        $this->messageQueue = $messageQueue;
        $this->localObject  = $localObject;
    }

    /**
     * 启动
     */
    public function run() {
        /**
         * 先把消息队列删除
         */
        $this->redisServer->del($this->messageQueue);
        $timeout = 0;
        while (1) {
            /**
             * Pop a message from the queue.
             */
            list($messageQueue, $message) = $this->redisServer->blpop($this->messageQueue, $timeout);

            debug_print("RPC Request: $message");

            /**
             * Decode the message.
             */
            $rpcRequest    = json_decode($message);
            $responseQueue = $rpcRequest->responseQueue;

            /**
             * Check that the function exists.
             */
            $functionCall = FunctionCall::fromObject($rpcRequest->functionCall);
            if (!method_exists($this->localObject, $functionCall->name)) {
                $rpcResponse = array('exception' => 'method "' . $functionCall->name . '" does not exist');
            } else {
                $code = 'return $this->localObject->' . $functionCall->asPhpCode() . ';';
                debug_print($code);
                try {
                    $value = eval($code);
                    $rpcResponse  = array('value' => $value);
                } catch (Exception $e) {
                    $rpcResponse = array('exception' => $e->getMessage());
                }
            }
            $message = json_encode($rpcResponse);
            debug_print("RPC Response: $message");
            $this->redisServer->rpush($responseQueue, $message);
        }
    }
}