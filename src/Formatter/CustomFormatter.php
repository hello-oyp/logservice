<?php
/**
 * Created by PhpStorm.
 */

namespace Yp\Log\Formatter;

use Monolog\Formatter\FormatterInterface;

class CustomFormatter implements FormatterInterface
{
    protected $custom_format;

    public function __construct($custom_format)
    {
        $this->custom_format = $custom_format;
    }

    public function format(array $record)
    {
        return $this->normalize($record);
    }

    protected function normalize($data)
    {
        if ($this->custom_format instanceof FormatterInterface) {
            return $this->custom_format->format($data);
        } elseif ($this->custom_format instanceof \Closure) {
            return call_user_func_array($this->custom_format, [$data]);
        }
        return '';
    }

    public function formatBatch(array $records)
    {

    }
}