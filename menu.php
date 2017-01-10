<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-11-29
 * Time: 下午4:35
 */

require(__DIR__ . '/Wchat/Autoload.php');

$redis = new \Redis();
$redis->pconnect('127.0.0.1', '6379');
$config['appId'] = 'wx34bed096ae44c5c4';
$config['appSecret'] = '320ccaedb22b4d668ac5ea1b45ff511e';
$config['storage'] = $redis;
$wchatApp = new \Wchat\Application($config);

echo "<pre>";
print_r($wchatApp->menuInfo());