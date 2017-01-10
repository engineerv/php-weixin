<?php
/**
 * 微信素材管理组件
 * @author yyq
 */
namespace Wchat\Services;

trait Material {

    /**
     * 新增临时素材
     * @param string $type 添加的类型
     * @param string $filename 添加的文件名
     * @return array
     */
    public function materialTmpAdd($type, $filename) {
        $data['media'] = "@{$filename}";
        $data['form-data'] = $this->getMediaInfo($filename);

        $api = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s';
        $api = sprintf($api, $this->getAccessToken(), $type);
        $result = $this->jsonDecode($this->httpUpload($api, $data));
        $this->throws($result);
        return $result;
    }


    /**
     * 获取临时素材
     * @param string $mediaId 媒体文件ID
     * @return array
     */
    public function materialTmpQuery($mediaId) {
        $api = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s';
        $api = sprintf($api, $this->getAccessToken(), $mediaId);
        $result = $this->httpGet($api);
        if (substr($result, 0, 1) == '{') {
            $result = $this->jsonDecode($result);
            $this->throws($result);
        }
        return $result;
    }

}