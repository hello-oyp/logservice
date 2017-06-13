<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Log;

class Config implements \ArrayAccess
{
    protected static $config = [];

    public function __construct($config = [])
    {
        static::$config = array_merge_recursive(static::$config, $config);
    }

    public static function createConfig($config = [])
    {
        return new static($config);
    }

    public function __get($name)
    {
        return static::get($name);
    }

    public static function get($key, $default = null)
    {
        return static::resolveGet(static::$config, $key, $default);
    }

    public static function resolveGet($array, $key = null, $default = null)
    {
        if (!static::accessible($array)) {
            return $default instanceof \Closure ? $default() : $default;
        }
        if (is_null($key)) {
            return $array;
        }
        if (static::exists($array, $key)) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $value) {
            if (static::exists($array, $value) && static::accessible($array)) {
                $array = $array[$value];
            } else {
                return $default instanceof \Closure ? $default() : $default;
            }
        }
        return $array;
    }

    public static function exists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }

    protected static function accessible($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    public function __set($key, $value)
    {
        return static::set($key, $value);
    }

    public static function set($key, $value = null)
    {
        return static::resolveSet(static::$config, $key, $value);
    }

    public static function resolveSet(&$array, $key = null, $value = null)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array =& $array[$key];
        }

        $array[array_shift($keys)] = $value;
        return true;
    }

    public function offsetExists($offset)
    {
        return static::exists(static::$config, $offset);
    }

    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->__set($offset, $value);
    }

    public function offsetUnset($offset)
    {

    }
}