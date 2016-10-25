<?php

namespace RedisRpc;

/**
 * Class FunctionCall
 *
 * @package RedisRpc
 */
class FunctionCall {

    /**
     * @var
     */
    public $name;

    /**
     * @var null
     */
    public $args;

    /**
     * @param      $name
     * @param null $args
     */
    public function __construct($name, $args = null) {
        $this->name = $name;
        $this->args = $args;
    }

    /**
     * @param $object
     * @return FunctionCall
     */
    static public function fromObject($object) {
        if (isset($object->args)) {
            return new FunctionCall($object->name, $object->args);
        }

        return new FunctionCall($object->name);
    }

    /**
     * @return string
     */
    public function asPhpCode() {
        if (isset($this->args)) {
            return $this->name . '(' . implode(',', $this->args) . ')';
        }

        return $this->name . '()';
    }
}