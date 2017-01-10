<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-12-9
 * Time: 下午4:56
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
$result = $wchatApp->payCallback();

//
$mapper->begin();
$model = $mapper->findAndLock();  // 3个请求 上锁成功了, 2个上锁没成功的... 释放锁了 2上锁了
if($model->getStatus() == 'success') {
    $mapper->rollback();
    return;
}

if($model->getTotalFee() > ($callback['total_fee']/100)) {
    header('HTTP/1.1 403 Forbidden');
    return;
}

$model->setStatus($callback['return_code'] == 'SUCCESS' ? 'success' : 'error');
$model->setPaytime(date('YmdHis'));
$mapper->update($model);

$mapper->commit();