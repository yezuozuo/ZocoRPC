<?php

namespace RedisRpc;

/**
 * Class Calculator
 *
 * @package RedisRpc
 */
class Calculator {
    /**
     * @var float 累加器
     */
    private $acc = 0.0;

    /**
     * @return float
     */
    public function clr() {
        $this->acc = 0.0;

        return $this->acc;
    }

    /**
     * @param $number
     * @return float
     */
    public function add($number) {
        $this->acc += $number;

        return $this->acc;
    }

    /**
     * @param $number
     * @return float
     */
    public function div($number) {
        $this->acc /= $number;

        return $this->acc;
    }

    /**
     * @param $number
     * @return float
     */
    public function mul($number) {
        $this->acc *= $number;

        return $this->acc;
    }

    /**
     * @param $number
     * @return float
     */
    public function sub($number) {
        $this->acc -= $number;

        return $this->acc;
    }

    /**
     * @return float
     */
    public function val() {
        return $this->acc;
    }
}
