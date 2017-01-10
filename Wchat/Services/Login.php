<?php
/**
 * 微信登录组件
 * @author enyccc
 */
namespace Wchat\Services;

trait Login {

    /**
     * 第一步：跳转到微信网站,获取用户的验证码
     * @param string $scope 授权域方式 snsapi_login-网页二维码扫描 | snsapi_userinfo-微网站授权 | snsapi_base-微网站基本授权
     * @param string $redirectUri 回调地址,需要urlencode
     * @param string $state csrf标志
     * @return void
     */
    public function loginCodeGet($scope, $redirectUri, $state) {
        if ($scope == 'snsapi_login') {
            // 网站登录跳转
            $api = 'https://open.weixin.qq.com/connect/qrconnect?appid=%s&redirect_uri=%s';
            $api .= '&response_type=code&scope=%s&state=%s#wechat_redirect';
            $api = sprintf($api, $this->getAppId(), $redirectUri, $scope, $state);
        } else {
            // 微信网站授权
            $api = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s';
            $api .= '&response_type=code&scope=%s&state=%s#wechat_redirect';
            $api = sprintf($api, $this->getAppId(), $redirectUri, $scope, $state);
        }
        header("Location: {$api}");
    }

    /**
     * 第一步：网站登录把二维码嵌入到div进行扫描
     * @param string $redirectUri 回调地址,需要urlencode
     * @return array
     */
    public function loginDivQRcode($redirectUri) {
        $viewsArr['scriptUrl'] = 'http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js';
        $viewsArr['config']['appid'] = $this->getAppId();
        $viewsArr['config']['scope'] = 'snsapi_login';
        $viewsArr['config']['redirect_uri'] = $redirectUri;
        $viewsArr['config']['state'] = 'STATE';
        $viewsArr['config'] = json_encode($viewsArr, JSON_UNESCAPED_UNICODE);
        return $viewsArr;
    }

    /**
     * 第二步：微信扫码后回调我司域名获取用户access_token
     * @param string $code 第一步方法回调给的code码
     * @return array 返回结果中，包含内容：access_token | expires_in | refresh_token | openid | scope | unionid(如果有绑定此机制)
     * @throws \Wchat\Exception
     */
    public function loginUserAccessTokenGet($code) {
        $api = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
        $api = sprintf($api, $this->getAppId(), $this->getAppSecret(), $code);
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     * 第三步：获取用户的微信账号信息（用户名，头像，性别等）如果授权域是snsapi_base请不要调用，权限不足
     * @param string $accessToken 第二步获取到的access_token参数
     * @param string $openId 第二步获取到的用户openid
     * @param string $language 语言，默认不传为：zh_CN
     * @return array 请参考微信文档
     * @throws \Wchat\Exception
     */
    public function loginUserInfoGet($accessToken, $openId, $language = 'zh_CN') {
        $api = sprintf('https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=%s', $accessToken, $openId, $language);
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     * 刷新用户的accessToken
     * @param string $refreshToken 第二步方法获取到的refresh_token参数
     * @return array 请参考微信文档
     * @throws \Wchat\Exception
     */
    public function loginUserAccessTokenRefresh($refreshToken) {
        $api = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s';
        $api = sprintf($api, $this->getAppId(), $refreshToken);
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     * @param string $accessToken 第二步方法获取到的access_token参数
     * @param string $openId 第二步方法获取到的openid参数
     * @return bool
     */
    public function logUserAccessTokenCheck($accessToken, $openId) {
        $api = sprintf('https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s', $accessToken, $openId);
        $result = $this->jsonDecode($this->httpGet($api));
        return $result['errcode'] == 0;
    }
}