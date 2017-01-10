<?php
/**
 * 微信安全组件
 * @author yyq
 */
namespace Wchat\Foundation;

trait Security {

    /**
     * 生成微信支付sign签名
     * @param array $params 原始数据
     * @return string
     */
    protected function paySignature(array $params) {
        // 签名步骤零：过滤非法数据
        foreach ($params as $key => $value) {
            if ($key == 'sign' || !$value || is_array($value)) {
                unset($params[$key]);
            }
        }
        // 签名步骤一：按字典序排序参数并生成请求串
        ksort($params);
        $sign = urldecode(http_build_query($params));
        // 签名步骤二：在string后加入KEY
        $sign .= "&key={$this->getPayKey()}";
        // 签名步骤三：MD5加密
        $sign = md5($sign);
        // 签名步骤四：所有字符转为大写
        $sign = strtoupper($sign);
        // 返回签名
        return $sign;
    }

    /**
     * 预支付返回结果检查
     * @param string $xmlStr xml字符串数据
     * @return void
     * @throws \Wchat\Exception
     */
    protected function payPrepareVerify($xmlStr) {
        // 数据来源检查
        if (!$xmlStr) {
            $this->throws(array('errcode' => '70001', 'errmsg' => '来源非法'));
        }
        // 把数据转成xml
        $payInfo = $this->xmlDecode($xmlStr);
        // 签名检查
        if ($this->paySignature($payInfo) !== $payInfo['sign']) {
            $this->throws(array('errcode' => '70002', 'errmsg' => '签名不正确'));
        }
        // 微信方通信是否成功
        if ($payInfo['return_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => '70003', 'errmsg' => $payInfo['return_msg']));
        }
        // 微信业务处理是否失败
        if (isset($payInfo['result_code']) && $payInfo['result_code'] == 'FAIL') {
            $this->throws(array('errcode' => '70004', 'errmsg' => $payInfo['err_code_des']));
        }
    }

    /**
     * 微信来源合法性检查
     * @param string $signature 微信加密签名
     * @param string $timestamp 时间戳
     * @param string $nonce 随机数
     * @param string $token 在微信平台设定的token值
     * @return void
     * @throws \Wchat\Exception
     */
    public function pushVerify($signature, $timestamp, $nonce, $token) {
        $signArr = array($token, $timestamp, $nonce);
        sort($signArr, SORT_STRING);
        if (sha1(implode($signArr)) !== $signature) {
            $this->throws(array('errcode' => '9100001', 'errmsg' => '来源非法'));
        }
    }
}