<?php
/**
 * 微信sdk应用类
 * @author yyq
 */
namespace Wchat;

class Application {

    /**
     * 所有组件
     * @internal trait
     */
    use Foundation\Config;
    use Foundation\Data;
    use Foundation\Security;
    use Foundation\Http;
    use Services\Account;
    use Services\Login;
    use Services\Pay;
    use Services\Jsapi;
    use Services\Material;
    use Services\Menu;
    use Services\Message;
    use Services\User;
    use Services\Customer;
    use Services\Mass;

    /**
     * 构造函数
     * @param array $config 配置选项
     * @internal param
     *  appId       微信公众号AppId
     *  appSecret   微信公众号密钥
     *  storage     有get,set,expire方法的类对象，比如redis对象
     *  mchid       微信支付商户号
     *  payKey      微信支付密钥
     *  certPem     签名证书
     *  certKey     密钥证书
     */
    public function __construct(array $config) {
        // 必须参数
        $this->setAppId($config['appId']);
        $this->setAppSecret($config['appSecret']);
        //$this->setStorage($config['storage']);
        //$this->setAccessToken();
        //$this->setJsapiTicket();
        // 微信支付
        $this->setPayMchid(isset($config['mchid']) ? $config['mchid']: NULL);
        $this->setPayKey(isset($config['payKey']) ? $config['payKey']: NULL);
        $this->setPayCertPem(isset($config['certPem']) ? $config['certPem'] : NULL);
        $this->setPayCertKey(isset($config['certKey']) ? $config['certKey'] : NULL);
    }
}