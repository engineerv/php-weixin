<?php
/**
 * 微信数据编码解码组件
 * @author yyq
 * @version 1.1
 * @log
 *  1. 2016-12-02 17:23 v1.1 增加getMediaInfo方法
 */
namespace Wchat\Foundation;

use \Wchat\Exception;

trait Data {

    /**
     * json编码
     * @param array|\stdClass $params
     * @return string
     */
    protected function jsonEncode($params) {
        return json_encode($params, JSON_UNESCAPED_UNICODE);
    }

    /**
     * json解码
     * @param string $jsonStr
     * @return array
     * @throws \Wchat\Exception
     */
    protected function jsonDecode($jsonStr) {
        $result = json_decode($jsonStr, TRUE);
        if (json_last_error()) {
            $this->throws(array('errcode' => '9100003', 'errmsg' => 'JSON decode error'));
        }
        return $result;
    }

    /**
     * xml编码
     * @param array|\stdClass $params
     * @param bool $isRecursion
     * @return string
     */
    protected function xmlEncode($params, $isRecursion = FALSE) {
        $xml = $isRecursion ? NULL : "<xml>";
        foreach ($params as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $xml .= "<{$key}>" . $this->xmlEncode($value, TRUE) . "</{$key}>";
            } else {
                $xml .= is_numeric($value) ? "<{$key}>{$value}</{$key}>" : "<{$key}><![CDATA[{$value}]]></{$key}>";
            }
        }
        $xml .= ($isRecursion ? NULL : "</xml>");
        return $xml;
    }

    /**
     * xml解码
     * @param string $xmlStr
     * @return array
     * @throws \Wchat\Exception
     */
    protected function xmlDecode($xmlStr) {
        libxml_disable_entity_loader(TRUE);
        $xmlStr = @simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (!$xmlStr) {
            $this->throws(array('errcode' => '9100002', 'errmsg' => 'XML decode error'));
        }
        return json_decode(json_encode($xmlStr), TRUE);
    }

    /**
     * url编码
     * @param string $params
     * @return string
     */
    protected function urlEncode($params) {
        return urlencode($params);
    }

    /**
     * url 解码
     * @param string $urlStr
     * @return string
     */
    protected function urlDecode($urlStr) {
        return urldecode($urlStr);
    }

    /**
     * 获取随机字符串
     * @return string
     */
    protected function strShuffle() {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $str = '';
        for ($i = 0; $i < 32; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取文件的所有信息
     * @param string $filename 文件名
     * @return array
     */
    protected function getMediaInfo($filename) {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mine = $finfo->file($filename);
        $info = array('filename' => $filename, 'content-type' => $mine, 'filelength' => filesize($filename),);
        return $info;
    }

    /**
     * 获取微信推送的消息
     * @return string
     */
    protected function getPush() {
        return file_get_contents('php://input');
    }

    /**
     * 异常抛出
     * @param array $info 需要判断的异常信息
     * @return void
     * @throws Exception
     */
    protected function throws(array $info) {
        if (isset($info['errcode']) && $info['errcode']) {
            throw new Exception($info['errmsg'], $info['errcode']);
        }
    }
}