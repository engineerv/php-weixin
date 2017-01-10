<?php
/**
 * 微信账号管理组件
 * @author yyq
 * @version 1.0
 */
namespace Wchat\Services;

trait Account {

    /**
     * 生成带参数的二维码
     * @param array $data 请参考微信相关文档获取key键名
     * @return array
     * @throw \Wchat\Exception
     */
    public function accountQRCodeCreate(array $data) {
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 通过ticket换取二维码
     * @param string $ticket 通过调用accountQRCodeCreate方法后获得的ticket
     * @return string 返回二维码图片内容，请自行写入文本或base64输出图像
     * @throw \Wchat\Exception
     */
    public function accountTicketExchange($ticket) {
        $api = sprintf('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=%s', $this->urlEncode($ticket));
        $result = $this->httpGet($api);
        if (substr($result, 0, 1) == '{') {
            $result = $this->jsonDecode($result);
            $this->throws($result);
        }
        return $result;
    }

    /**
     * 二维码地址长链接转短链接接口
     * @param string $url 通过调用accountQRCodeCreate方法后获得的url
     * @return string 转换后的短链接
     * @throw \Wchat\Exception
     */
    public function accountUrlToShort($url) {
        $data = array('action' => 'long2short', 'long_url' => $url);
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/shorturl?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result['short_url'];
    }
}