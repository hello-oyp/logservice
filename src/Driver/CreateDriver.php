<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Log\Driver;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CreateDriver implements CreateInterface
{
    protected $drivers = [
        'file' => 'createFileDriver',
        'redis' => 'createRedisDriver'
    ];

    protected static $instance;

    public static function getInstance()
    {
        if (static::$instance) {
            return static::$instance;
        }
        return static::$instance = new static();
    }

    public function getDriver($driver)
    {
        if (isset($this->drivers[$driver]) && method_exists($this, $this->drivers[$driver])) {
            return function ($config) use ($driver) {
                return $this->{$this->drivers[$driver]}($config);
            };
        }
        return null;
    }

    public function createFileDriver($config)
    {
        $logger = new Logger($config['log_name'] ?: 'unKnown');
        $logger->pushHandler(
            $handle = new RotatingFileHandler(
                $config['file_name'],
                $config['max_files'],
                $config['level'],
                false,
                $config['file_permission'],
                $config['use_locking']
            )
        );
        $handle->setFilenameFormat($config['file_name_format'], $config['date_format']);
        return [$logger, $handle];
    }
}
