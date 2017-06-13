<?php
/**
 * Created by PhpStorm.
 */
require_once __DIR__ . '/../vendor/autoload.php';

$array = [
    'a' => [
        'b' => 'ab'
    ],
    'b' => 'b'
];
$config = new \Yp\Log\Config($array);

var_dump(\Yp\Log\Config::get(null));

var_dump(\Yp\Log\Config::get('a.b'));

\Yp\Log\Config::set('b.c', 'bc');
var_dump(\Yp\Log\Config::get(null));

var_dump($config['b.c']);

$config['a.c'] = 'ac';
var_dump(\Yp\Log\Config::get(null));
