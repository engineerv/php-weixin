<?php
/**
 * 用户账号
 */
require(__DIR__ . '/Wchat/Autoload.php');

$redis = new \Redis();
$redis->pconnect('127.0.0.1', '6379');
$config['appId'] = 'wx34bed096ae44c5c4';
$config['appSecret'] = '320ccaedb22b4d668ac5ea1b45ff511e';
$config['storage'] = $redis;
$wchatApp = new \Wchat\Application($config);


$data['expire_seconds'] = '2592000';
$data['action_name'] = 'QR_LIMIT_STR_SCENE';
$data['action_info']['scene']['scene_str'] = 'yyqcxb10291229';
$result = $wchatApp->accountQRCodeCreate($data);

/*$shortUrl = $wchatApp->accountUrlToShort($result['url']);*/
$image = $wchatApp->accountTicketExchange($result['ticket']);

echo '<img src="data:image/jpg;base64,'.base64_encode($image).'">';
