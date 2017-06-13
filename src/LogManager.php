<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Log;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\HandlerInterface;
use Psr\Log\LoggerInterface;
use Yp\Log\Formatter\CustomFormatter;
use Yp\Log\Driver\CreateDriver;
use Yp\Log\Driver\CreateInterface;

class LogManager
{
    protected $config;

    protected $drivers;

    protected $create_driver;

    protected $extend_drivers;

    protected $driver_format;

    public function __construct($config = [])
    {
        Config::createConfig($config);
    }

    public function driver($name = null)
    {
        $driver = $name ?: $this->getDefaultDriver();
        if (isset($this->drivers[$driver])) {
            return $this->drivers[$driver];
        }
        return $this->drivers[$driver] = $this->resolve($driver);
    }

    protected function resolve($driver)
    {
        do {
            $config = Config::get($driver);
            if (isset($this->extend_drivers[$driver])) {
                $resolve_driver = $this->extend_drivers[$driver];
                break;
            }
            if ($config == null || empty($config)) {
                throw new \InvalidArgumentException("The $driver driver config is not define");
            }
            $create_driver = $this->getDefaultCreateDriver()->getDriver($driver);
            if ($create_driver != null) {
                $resolve_driver = $create_driver;
                break;
            } else {
                throw new \InvalidArgumentException("The $driver driver method is not define");
            }
        } while (0);
        $config['level'] = $this->getDefaultLevel();
        $logger = $this->parserDriver($resolve_driver, $config);
        return new Write($logger);
    }

    protected function parserDriver($driver, $config)
    {
        $call_return = call_user_func_array($driver, [(array)$config]);
        if (!is_array($call_return)) {
            $call_return = [$call_return];
        }
        $logger = array_shift($call_return);
        $handle = array_shift($call_return);
        if (!$logger instanceof LoggerInterface) {
            throw new \InvalidArgumentException('The logger driver is must instance LoggerInterface');
        }
        if ($this->getDriverFormat() != null && $handle instanceof HandlerInterface) {
            $handle->setFormatter(new CustomFormatter($this->getDriverFormat()));
        }
        return $logger;
    }

    public function setDriverFormat($format)
    {
        if ($format instanceof FormatterInterface || $format instanceof \Closure) {
            $this->driver_format[$this->getDefaultDriver()] = $format;
            if (isset($this->drivers[$this->getDefaultDriver()])) {
                unset($this->drivers[$this->getDefaultDriver()]);
            }
            return true;
        }
        throw new \InvalidArgumentException('The format is instance FormatterInterface or Closure');
    }

    public function getDriverFormat()
    {
        if (isset($this->driver_format[$this->getDefaultDriver()])) {
            return $this->driver_format[$this->getDefaultDriver()];
        }
        return null;
    }

    public function extendDriver($driver, \Closure $closure)
    {
        $this->extend_drivers[$driver] = $closure;
    }

    public function removeExtendDriver($driver = null)
    {
        if ($driver == null) {
            $this->extend_drivers = [];
        } else {
            unset($this->extend_drivers[$driver]);
            unset($this->drivers[$driver]);
        }
        return true;
    }

    public function getDefaultDriver()
    {
        return Config::get('driver');
    }

    public function setDefaultDriver($driver)
    {
        return Config::set('driver', $driver);
    }

    public function getDefaultLevel()
    {
        return Config::get('level') ?: 'debug';
    }

    public function getDefaultCreateDriver()
    {
        return $this->create_driver ?: CreateDriver::getInstance();
    }

    public function setDefaultCreateDriver(CreateInterface $driver)
    {
        $this->create_driver = $driver;
    }

    public function __call($name, $arguments)
    {
        return $this->driver()->$name(...$arguments);
    }

}
