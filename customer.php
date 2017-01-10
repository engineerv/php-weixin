<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-12-5
 * Time: 上午9:34
 */

require(__DIR__ . '/Wchat/Autoload.php');

$redis = new \Redis();
$redis->pconnect('127.0.0.1', '6379');
$config['appId'] = 'wx0f44563f329b512e';
$config['appSecret'] = '73a8aab508292953ae9d4f238da678c3';
$config['storage'] = $redis;
$wchatApp = new \Wchat\Application($config);

//添加客服账号
$data = array('kf_account'=>'123456@yyq', 'kf_nick'=>'yyq', 'kf_id'=>'123456', 'nickname'=>'yyq');
$wchatApp->customerAccountAdd($data);

//查找所有账号名
//print_r($wchatApp->customerGetAccount());