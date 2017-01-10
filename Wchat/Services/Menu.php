<?php
/**
 * 微信自定义菜单组件
 * @author yyq
 * @version 1.0
 */
namespace Wchat\Services;

trait Menu {

    /**
     * 自定义菜单创建
     * @param  array $buttons 请参考微信相关文档
     * @return void
     * @throw \Wchat\Exception
     */
    public function menuCreate(array $buttons) {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($buttons)));
        $this->throws($result);
    }

    /**
     * 自定义菜单清空
     * @return void
     * @throw \Wchat\Exception
     */
    public function menuDelete() {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
    }

    /**
     * 查询自定义菜单
     * @return array 返回结果请参考微信文档
     * @throws \Wchat\Exception
     */
    public function menuQuery() {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     * 查询自定义菜单，如果是微信后台设置的则使用此方法才能获取到
     * @return array 返回结果请参考微信文档
     * @throws \Wchat\Exception
     */
    public function menuCurrent() {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpGet($api));
        $this->throws($result);
        return $result;
    }

    /**
     * 创建个性化的自定义菜单
     * @param array $buttons 请参考微信相关文档
     * @return void
     * @throws \Wchat\Exception
     */
    public function menuConditionalCreate(array $buttons) {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($buttons)));
        $this->throws($result);
    }

    /**
     * 删除个性化的自定义菜单
     * @param array $buttons 请参考微信相关文档
     * @return void
     * @throws \Wchat\Exception
     */
    public function menuConditionalDelete(array $buttons) {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($buttons)));
        $this->throws($result);
    }
}