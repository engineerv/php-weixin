<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-11-23
 * Time: 下午3:23
 */

namespace Wchat\Services;


trait Jsapi {

    /**
     * 注册接口
     * @param $url
     * @return mixed
     */
    public function jsapiConfigReg($url) {
        // 要准备的数据
        $config['appId'] = $this->getAppId();
        $config['timestamp'] = time();
        $config['noncestr'] = $this->strShuffle();
        $config['jsapi_ticket'] = $this->getJsapiTicket();
        $config['url'] = $url;

        // 进行签名
        $sign = "jsapi_ticket={$config['jsapi_ticket']}&noncestr={$config['noncestr']}&";
        $sign .= "&timestamp={$config['timestamp']}&url={$config['url']}";
        $config['signature'] = sha1($sign);

        // 毁尸灭迹
        $config['nonceStr'] = $config['noncestr'];
        unset($config['jsapi_ticket'], $config['noncestr'], $config['url']);

    	return $config;
    }
}