<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Log;

use Psr\Log\LoggerInterface;

class Write implements LoggerInterface
{
    protected $log_driver;

    public function __construct($log_driver)
    {
        $this->log_driver = $log_driver;
    }

    public function write($level, $message, array $context = array())
    {
        $this->log_driver->{$level}($message, $context);
    }

    public function emergency($message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);

    }

    public function critical($message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);

    }

    public function error($message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    public function notice($message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    public function info($message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        $this->write(__FUNCTION__, $message, $context);
    }
}
