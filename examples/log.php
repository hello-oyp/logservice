<?php
/**
 * Created by PhpStorm.
 */
ini_set('date.timezone', 'Asia/Shanghai');
require_once __DIR__ . '/../vendor/autoload.php';

$log = new \Yp\Log\LogManager(require __DIR__ . '/../config/config.php');

//如果 想用自己的logdriver。
$log->extendDriver($log->getDefaultDriver(), function ($config) {
    $logger = new \Monolog\Logger($config['log_name'] ?: 'unKnown');
    $logger->pushHandler(
        $handle = new \Monolog\Handler\RotatingFileHandler(
            $config['file_name'],
            $config['max_files'],
            $config['level'],
            false,
            $config['file_permission'],
            $config['use_locking']
        )
    );
    $handle->setFilenameFormat('{filename}-{date}', $config['date_format']);
    return [$logger, $handle];
});

$log->error('wwwwwwwww');
//设置当前driver的内容格式
$log->setDriverFormat(function ($data) {
    return json_encode($data) . "\n";
});

$log->removeExtendDriver();
$log->error('qqqqqqq2121');