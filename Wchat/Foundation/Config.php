<?php
/**
 * 微信配置组件
 * @author yyq
 * @version 1.0
 */
namespace Wchat\Foundation;

trait Config {

    /**
     * 公众号appId
     * @var string
     */
    protected $appId;

    /**
     * 公众号密钥
     * @var string
     */
    protected $appSecret;

    /**
     * Oauth认证id,有效期7200秒
     * @var string
     */
    protected $accessToken;

    /**
     * Oauth的js认证id,有效期7200秒
     * @var string
     */
    protected $jsapiTicket;

    /**
     * 缓存对象,有get,set,expire三个方法的类对象
     * @var mixed
     */
    protected $storage;

    /**
     * 微信支付商户号
     * @var string
     */
    protected $payMchid;

    /**
     * 微信支付密钥
     * @var string
     */
    protected $payKey;

    /**
     * 是否使用证书
     * @var string
     */
    protected $useCert;

    /**
     * 微信支付证书
     * @var string
     */
    protected $payCertPem;

    /**
     * 微信支付证书密钥
     * @var string
     */
    protected $payCertKey;

    /**
     * 设置公众号appId
     * @param string $appId
     * @return $this
     */
    public function setAppId($appId) {
        $this->appId = $appId;
        return $this;
    }

    /**
     * 获取公众号appId
     * @return string
     */
    public function getAppId() {
        return $this->appId;
    }

    /**
     * 设置公众号密钥
     * @param string $appSecret
     * @return $this
     */
    public function setAppSecret($appSecret) {
        $this->appSecret = $appSecret;
        return $this;
    }

    /**
     * 获取公众号密钥
     * @return string
     */
    public function getAppSecret() {
        return $this->appSecret;
    }

    /**
     * 设置微信支付商户号
     * @param string $payMchid
     * @return $this
     */
    public function setPayMchid($payMchid) {
        $this->payMchid = $payMchid;
        return $this;
    }

    /**
     * 获取微信支付商户号
     * @return string
     */
    public function getPayMchid() {
        return $this->payMchid;
    }

    /**
     * 设置微信支付密钥
     * @param string $payKey
     * @return $this
     */
    public function setPayKey($payKey) {
        $this->payKey = $payKey;
        return $this;
    }

    /**
     * 获取微信支付密钥
     * @return string
     */
    public function getPayKey() {
        return $this->payKey;
    }

    /**
     * 设置微信支付证书
     * @param string $payCertPem
     * @return $this
     */
    public function setPayCertPem($payCertPem) {
        $this->payCertPem = $payCertPem;
        return $this;
    }

    /**
     * 获取微信支付证书
     * @return string
     */
    public function getPayCertPem() {
        return $this->payCertPem;
    }

    /**
     * 设置微信支付证书密钥
     * @param string $payCertKey
     * @return $this
     */
    public function setPayCertKey($payCertKey) {
        $this->payCertKey = $payCertKey;
        return $this;
    }

    /**
     * 获取微信支付证书密钥
     * @return string
     */
    public function getPayCertKey() {
        return $this->payCertKey;
    }

    /**
     * 设置缓存对象
     * @param mixed $storage
     * @return $this
     */
    public function setStorage($storage) {
        $this->storage = $storage;
        return $this;
    }

    /**
     * 获取缓存对象
     * @return \Redis
     */
    protected function getStorage() {
        return $this->storage;
    }

    /**
     * 设置Oauth认证id
     * @return $this
     * @throws \Wchat\Exception
     */
    public function setAccessToken() {
        // 缓存读取
        $cacheKey = sprintf('wchat.%s.accssToken', $this->getAppId());
        $this->accessToken = $this->getStorage()->get($cacheKey);

        // 缓存不存在
        if (!$this->accessToken) {
            // 请求接口
            $api = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
            $api = sprintf($api, $this->getAppId(), $this->getAppSecret());

            // 执行请求
            $result = $this->jsonDecode($this->httpGet($api));
            $this->throws($result);

            // 缓存保存
            $this->getStorage()->set($cacheKey, $result['access_token']);
            $this->getStorage()->expire($cacheKey, $result['expires_in']);

            // 设置结果
            $this->accessToken = $result['access_token'];
        }

        return $this;
    }

    /**
     * 获取Oauth认证id
     * @return string
     */
    public function getAccessToken() {
        return $this->accessToken;
    }

    /**
     * 设置jsapiTicket
     * @return $this
     * @throws \Wchat\Exception
     */
    public function setJsapiTicket() {
        // 缓存读取
        $cacheKey = sprintf('wchat.%s.jsapiTicket', $this->getAppId());
        $this->jsapiTicket = $this->getStorage()->get($cacheKey);

        // 缓存获取失败
        if (!$this->jsapiTicket) {
            // 请求接口
            $api = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi';
            $api = sprintf($api, $this->getAccessToken());

            // 执行请求
            $result = $this->jsonDecode($this->httpGet($api));
            $this->throws($result);

            // 缓存保存
            $this->getStorage()->set($cacheKey, $result['ticket']);
            $this->getStorage()->expire($cacheKey, $result['expires_in']);

            // 设置结果
            $this->jsapiTicket = $result['ticket'];
        }

        return $this;
    }

    /**
     * 获取jsapiTicket
     * @return string
     */
    public function getJsapiTicket() {
        return $this->jsapiTicket;
    }

    protected function setUseCert($isUse) {
        $this->useCert = $isUse;
        return $this;
    }

    protected function getUseCert() {
        return $this->useCert;
    }
}