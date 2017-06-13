<?php
/**
 * Created by PhpStorm.
 */
return [
    'file' => [
        'file_name' => '/home/WWW/logservice/log/log.log',
        'max_files' => 0,
        'file_permission' => null,
        'use_locking' => false,
        'log_name' => 'logservice',
        'file_name_format' => '{filename}.{date}',
        'date_format' => 'Y-m-d'
    ],
    'driver' => 'file',
    'level' => 'debug'
];