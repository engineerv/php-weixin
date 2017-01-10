<?php
/**
 * 微信用户管理组件
 * @author enyccc
 */
namespace Wchat\Services;

trait User {

    /**
     * 创建标签
     * @param string $name 标签名
     * @return array
     * @throws \Wchat\Exception
     */
    public function userTagCreate($name) {
        $data['tag']['name'] = $name;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/create?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 获取公众号已创建的标签
     * @return array
     * @throws \Wchat\Exception
     */
    public function userTagQuery() {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/get?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     *  编辑标签
     * @param int $id 用户标签id
     * @param string $name 用户标签名
     * @throws \Wchat\Exception
     */
    public function userTagEdit($id, $name) {
        $data['tag']['id'] = $id;
        $data['tag']['name'] = $name;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/update?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 删除标签
     * @param int $id 用户标签id
     * @return mixed
     * @throws \Wchat\Exception
     */
    public function userTagDelete($id) {
        $data['tag']['id'] = $id;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 获取标签下粉丝列表
     * @param int $tagid 用户标签id
     * @param array $openid 第一个拉取的OPENID，不填默认从头开始拉取
     * @return array
     * @throws \Wchat\Exception
     */
    public function userTagFansListGet($tagid, $openid = array()) {
        $data['tagid'] = $tagid;
        $data['next_openid'] = $openid;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 批量为用户打标签
     * @param string $tagId 标签id
     * @param array $data 粉丝openid列表
     * @throws \Wchat\Exception
     */
    public function userTagBatchAdd($tagId, array $data) {
        $data['openid_list'] = $data;
        $data['tagid'] = $tagId;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 批量为用户取消标签
     * @param string $tagid 用户标签id
     * @param array $opendid 粉丝openid列表
     * @throws \Wchat\Exception
     */
    public function userTagBatchCancel($tagid, $opendid = array()) {
        $data['tagid'] = $tagid;
        $data['openid_list'] = $opendid;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 获取用户身上的标签列表
     * @param string $openid 粉丝openid
     * @return array
     * @throws \Wchat\Exception
     */
    public function userTagListGet($openid) {
        $data['openid'] = $openid;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 获取用户列表
     * @param string $openid 第一个拉取的OPENID，不填默认从头开始拉取
     * @return array
     * @throws \Wchat\Exception
     */
    public function userListGet($openid = NULL) {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s&next_openid=%s', $this->getAccessToken(), $openid);
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     * 设置用户备注名
     * @param string $openid 用户openid
     * @param string $name 用户备注名
     * @throws \Wchat\Exception
     */
    public function userNoteNameSet($openid, $name) {
        $data['openid'] = $openid;
        $data['remark'] = $name;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 获取用户基本信息
     * @param string $openid 普通用户的标识，对当前公众号唯一
     * @param string $lang 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语 默认为 zh_CN 简体
     * @return array
     * @throws \Wchat\Exception
     */
    public function userInformationGet($openid, $lang = 'zh_CN') {
        $api = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=%s';
        $api = sprintf($api, $this->getAccessToken(), $openid, $lang);
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     * 批量获取用户基本信息
     * @param $data array key值为：openid(普通用户的标识，对当前公众号唯一),lang(lang默认为zh-CN);
     * @return array
     * @throws \Wchat\Exception
     */
    public function userBatchInformationGet(array $data) {
        $data['user_list'] = $data;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 拉黑用户
     * @param array $data 需要拉入黑名单的用户的openid，一次拉黑最多允许20个
     * @throws \Wchat\Exception
     */
    public function userBlacklistAdd(array $data) {
        $data['openid_list'] = $data;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     *  获取公众号的黑名单列表
     * @param string $openid 普通用户的标识，对当前公众号唯一 可为空
     * @return array
     * @throws \Wchat\Exception
     */
    public function userBlackListGet($openid = NULL) {
        $data['begin_oepnid'] = $openid;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 取消拉黑用户
     * @param array $data 需要取消拉入黑名单的用户的openid，一次拉黑最多允许20个
     * @throws \Wchat\Exception
     */
    public function userBlacklistCancel($data) {
        $data['openid_list'] = $data;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }
}