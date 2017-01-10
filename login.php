<?php
/**
 * 用户登录
 */
require(__DIR__ . '/Wchat/Autoload.php');

$redis = new \Redis();
$redis->pconnect('127.0.0.1', '6379');
$config['appId'] = 'wx0f44563f329b512e';
$config['appSecret'] = '73a8aab508292953ae9d4f238da678c3';
$config['storage'] = $redis;
$wchatApp = new \Wchat\Application($config);

$wchatApp->loginCodeGet('snsapi_login', 'http://my.dev.youlaohu.com/callback.php');