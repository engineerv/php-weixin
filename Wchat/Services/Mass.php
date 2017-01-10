<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-12-5
 * Time: 上午11:00
 */
namespace Wchat\Services;

trait Mass{

    /**
     *上传图文消息内的图片获取URL
     * @param  string $fileName 上传的图片名
     * @throws \Wchat\Exception
     * @return array
     */
    public function massUpMessGetImageUrl($fileName){
        $data['media'] = "@{$fileName}";
        $data['form-data'] = $this->getMediaInfo($fileName);
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     *上传图文消息素材
     * @param array $data 图文消息，一个图文消息支持1到8条图文 参数如下所示
     * thumb_media_id 必须 图文消息缩略图的media_id，可以在基础支持-上传多媒体文件接口中获得
     * author 可选 图文消息的作者
     * title 必须 图文消息的标题
     * content_source_url 可选 在图文消息页面点击“阅读原文”后的页面，受安全限制，如需跳转Appstore，
     *                        可以使用itun.es或appsto.re的短链服务，并在短链后增加 #wechat_redirect 后缀。
     * content 必须 图文消息页面的内容，支持HTML标签。具备微信支付权限的公众号，可以使用a标签，其他公众号不能使用
     * digest 可选 图文消息的描述
     * show_cover_pic 可选 是否显示封面，1为显示，0为不显示
     * @throws \Wchat\Exception
     * @return array
     */
    public function MassUpMessMaterial(array $data){
        $data['articles'] = $data;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 根据标签进行群发
     * @param array $data 参数见文档
     * @throws \Wchat\Exception
     * @return array
     */
    public function MassAccordTagSends(array $data){
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 删除群发
     * @param string $msg_id 发送出去的消息ID
     * @throws \Wchat\Exception
     */
    public function MassSendDelete($msg_id){
        $data['msg_id'] = $msg_id;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
    }

    /**
     * 预览接口
     * @param array $data 参数见文档
     * @throws \Wchat\Exception
     * @return array
     */
    public function MassMessPreview($data){
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }

    /**
     * 查询群发消息发送状态
     * @param string $msg_id 发送出去的消息ID
     * @throws \Wchat\Exception
     * @return array
     */
    public function MassMessStateQuery($msg_id){
        $data['msg_id'] = $msg_id;
        $api = sprintf('https://api.weixin.qq.com/cgi-bin/message/mass/get?access_token=%s', $this->getAccessToken());
        $result = $this->jsonDecode($this->httpPost($api, $this->jsonEncode($data)));
        $this->throws($result);
        return $result;
    }
}