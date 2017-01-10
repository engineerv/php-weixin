<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-11-30
 * Time: 上午9:38
 */

require(__DIR__ . '/Wchat/Autoload.php');

$redis = new \Redis();
$redis->pconnect('127.0.0.1', '6379');
$config['appId'] = 'wx34bed096ae44c5c4';
$config['appSecret'] = '320ccaedb22b4d668ac5ea1b45ff511e';
$config['storage'] = $redis;
$wchatApp = new \Wchat\Application($config);

echo "<pre>";
//创建标签
//print_r($wchatApp->userTagCreate('厦门'));

//编辑标签
//$wchatApp->userTagEdit(101, '男人');

//删除标签
//$wchatApp->userTagDelete(101);

//获取用户标签
print_r($wchatApp->userTagQuery());

//获取标签下的粉丝列表
//print_r($wchatApp->userTagGetFansList(104));

//获取用户列表
//print_r($wchatApp->userGetList());

//批量为用户打标签
//$wchatApp->userTagBatchAdd(104, array('oSNhLxA9x5M65fuSG0Oo5QChKyx0', 'oSNhLxAf_CnRCV3rylXkWua7vN6g'));

//获取用户身上的标签列表
//print_r($wchatApp->userTagGetList('oSNhLxA9x5M65fuSG0Oo5QChKyx0'));

//批量为用户取消标签
//$wchatApp->userTagBatchCancel(104, array('oSNhLxA9x5M65fuSG0Oo5QChKyx0', 'oSNhLxAf_CnRCV3rylXkWua7vN6g'));

//设置用户备注名
//$wchatApp->userSetNoteName('oSNhLxAf_CnRCV3rylXkWua7vN6g', '小贱人');

//获取用户基本信息
//print_r($wchatApp->userGetInformation('oSNhLxAf_CnRCV3rylXkWua7vN6g'));

//批量获取用户基本信息
/*$data = array(
    array(
        'openid'=>'oSNhLxAf_CnRCV3rylXkWua7vN6g',
    ),
    array(
        'openid'=>'oSNhLxA9x5M65fuSG0Oo5QChKyx0',
    )
);
print_r($wchatApp->userGetBatchInformation($data));*/

//拉黑用户
//$wchatApp->userBlacklistAdd(array('oSNhLxAf_CnRCV3rylXkWua7vN6g'));

// 获取公众号的黑名单列表
//print_r($wchatApp->userGetBlackList());

//取消拉黑用户
//$wchatApp->userBlacklistCancel(array('oSNhLxAf_CnRCV3rylXkWua7vN6g'));

//1

