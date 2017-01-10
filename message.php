<?php

/**
 * 微信消息管理案例
 * @author yyq
 */
require(__DIR__ . '/Wchat/Autoload.php');

$redis = new \Redis();
$redis->pconnect('127.0.0.1', '6379');
$config['appId'] = 'wx34bed096ae44c5c4';
$config['appSecret'] = '320ccaedb22b4d668ac5ea1b45ff511e';
$config['storage'] = $redis;
$wchatApp = new \Wchat\Application($config);

define('APIKEY', '007b63ff6f10dc23da634f7cee04f14d');

// 响应用户发送的内容
$wchatApp->messageEvent('text,image', function ($data){
    $starttime = time();
    $reply['type'] = 'text';
    $content = trim($data['Content']);

    // 用户没输入
    if(empty($content)) {
        $reply['Content'] = '请输入您要查询天气的城市';
        return $reply;
    }

    // 获取城市id
    $ch = curl_init();
    $url = sprintf('http://apis.baidu.com/apistore/weatherservice/citylist?cityname=%s', urlencode($data['Content']));
    $header = array('apikey: ' . APIKEY);
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
    $arr = json_decode($res, TRUE);
    if($arr['errNum'] == -1 || empty($arr['retData'])) {
        $reply['Content'] = "暂时无法查询【{$data['Content']}】的天气状况";
        return $reply;
    }

    $arr = $arr['retData'][0];
    $ch = curl_init();
    $url = sprintf('http://apis.baidu.com/apistore/weatherservice/cityid?cityid=%s', $arr['area_id']);
    $header = array('apikey: ' . APIKEY,);
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
    $arr = json_decode($res, TRUE);
    if($arr['errNum'] == -1) {
        $reply['Content'] = "暂时无法查询{$data['Content']}的天气状况";
        return $reply;
    }

    $reply['Content'] = '当前城市：' . $data['Content'] . "\r\n";
    $reply['Content'] .= '当前时间：' . $arr['retData']['date'] . ' ' . $arr['retData']['time'] . "\r\n";
    $reply['Content'] .= '天气情况：' . $arr['retData']['weather'] . "\r\n";
    $reply['Content'] .= '当前气温：' . $arr['retData']['temp'] . "\r\n";
    $reply['Content'] .= '最低气温：' . $arr['retData']['l_tmp'] . "\r\n";
    $reply['Content'] .= '最高气温：' . $arr['retData']['h_tmp'] . "\r\n";
    $reply['Content'] .= '当前风力：' . $arr['retData']['WS'] . "\r\n";
    $reply['Content'] .= '用时查询：' . (time() - $starttime) . '秒';

    return $reply;
});

// 响应用户关注事件
$wchatApp->messageEvent('subscribe', function($data) {
    $reply['Content'] = '感谢您的关注，你可以输入城市名查询天气';
    $reply['type'] = "text";

    return $reply;
});

// 监听事件
$wchatApp->messageListen();

