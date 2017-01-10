<?php

/**
 * 微信支付
 */
require(__DIR__ . '/Wchat/Autoload.php');

$redis = new \Redis();
$redis->pconnect('127.0.0.1', '6379');
$config['appId'] = 'wx0f44563f329b512e';
$config['appSecret'] = '73a8aab508292953ae9d4f238da678c3';
$config['mchid'] = '1318268001';
$config['payKey'] = 'E81f0105415bBDD9Ba72A2A16cB06d63';
//$config['storage'] = $redis;
$wchatApp = new \Wchat\Application($config);

$data['body'] = 'yyq店-食品';
$data['out_trade_no'] = '901021';
$data['total_fee'] = 1;
$data['notify_url'] = 'http://yyq.chenxiaobo.me/callback.php';

if(stripos($_SERVER['HTTP_USER_AGENT'], 'imcromessenger') !== FALSE) {
    $data['trade_type'] = 'JSAPI';
    $data['openid'] = $userModel->getOpenid();
} else {
    $data['trade_type'] = 'NAVITE';
    $data['product_id'] = 1;
}

$data['trade_type'] = stripos($_SERVER['HTTP_USER_AGENT'], 'imcromessenger') ? "JSAPI" : 'Native';

$reuslt = $wchatApp->payUnifiedorder($data);



\Wchat\Foundation\QRcode::png($reuslt['code_url']);