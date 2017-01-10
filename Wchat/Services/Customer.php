<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-12-2
 * Time: 下午2:50
 */
namespace Wchat\Services;

trait Customer {

    /**
     * 添加客服帐号
     * @param array $data 包含列表如下
     * kf_account  必须    完整客服账号，格式为：账号前缀@公众号微信号
     * kf_nick 必须 客服昵称
     * kf_id 必须 客服工号
     * nickname    必须    客服昵称，最长6个汉字或12个英文字符
     * password    可选    客服账号登录密码，格式为密码明文的32位加密MD5值。该密码仅用于在公众平台官网的多客服功能中使用，若不使用多客服功能，则不必设置密码
     * @throws \Wchat\Exception
     */
    public function customerAccountAdd(array $data) {
        $api = sprintf('https://api.weixin.qq.com/customservice/kfaccount/add?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 修改客服帐号
     * @param array $data 包含列表如下
     * kf_account  必须    完整客服账号，格式为：账号前缀@公众号微信号
     * kf_nick 必须 客服昵称
     * kf_id 必须 客服工号
     * nickname    必须    客服昵称，最长6个汉字或12个英文字符
     * password    可选    客服账号登录密码，格式为密码明文的32位加密MD5值。该密码仅用于在公众平台官网的多客服功能中使用，若不使用多客服功能，则不必设置密码
     * @throws \Wchat\Exception
     */
    public function customerAccountModify(array $data) {
        $api = sprintf('https://api.weixin.qq.com/customservice/kfaccount/update?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 删除客服帐号
     * @param array $data 包含列表如下
     * kf_account  必须    完整客服账号，格式为：账号前缀@公众号微信号
     * kf_nick 必须 客服昵称
     * kf_id 必须 客服工号
     * nickname    必须    客服昵称，最长6个汉字或12个英文字符
     * password    可选    客服账号登录密码，格式为密码明文的32位加密MD5值。该密码仅用于在公众平台官网的多客服功能中使用，若不使用多客服功能，则不必设置密码
     * @throws \Wchat\Exception
     */
    public function customerAccountDelete(array $data) {
        $api = sprintf('https://api.weixin.qq.com/customservice/kfaccount/del?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 设置客服帐号的头像
     * @param string $account 客服账号
     * @param string $filename 上传头像地址名
     * @throws \Wchat\Exception
     */
    public function customerAccountAvatar($account, $filename) {
        $data['media'] = "@{$filename}";
        $data['form-data'] = $this->getMediaInfo($filename);
        $api = 'http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token=%s&kf_account=%s';
        $api = sprintf($api, $this->getAccessToken(), $account);
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 获取所有客服账号
     * @return array
     * @throws \Wchat\Exception
     */
    public function customerGetAccount() {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     * 客服接口-发消息
     * @param array $data 参数见文档
     * @throws \Wchat\Exception
     */
    public function customerSendMessage(array $data) {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }
}