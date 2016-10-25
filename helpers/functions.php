<?php
/**
 * Created by PhpStorm.
 * User: zoco
 * Date: 16/8/16
 * Time: 17:06
 */

if (!function_exists('z_debug')) {
    function z_debug($var)
    {
        echo '<div style="direction: ltr !important; text-align: left !important;"><pre>';
        print_r($var);
        echo '</pre></div>';
        exit();
    }
}

if (!function_exists('z_log')) {
    function z_log($message)
    {
        if(is_array($message))
        {
            $message = json_encode($message);
        }
        $message = date('Y-m-d H:i:s')."\t".$message."\n";
        $date = date('Ymd');
        file_put_contents("/tmp/spas/{$date}.log",$message,FILE_APPEND);
    }
}

if (!function_exists('z_timer')) {
    function z_timer() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}

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

if (!function_exists('randomString')) {
    function randomString($size) {
        $validChars    = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $res           = "";
        $numValidChars = strlen($validChars);

        for ($i = 0; $i < $size; $i++) {
            $randomPick = mt_rand(1, $numValidChars);
            $randomChar = $validChars[$randomPick - 1];
            $res .= $randomChar;
        }

        return $res;
    }
}