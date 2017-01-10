<?php
/**
 * Created by PhpStorm.
 * User: yyq
 * Date: 16-11-23
 * Time: 下午3:21
 */
namespace Wchat\Services;

trait Pay {

    /**
     * 统一下单
     * @param array $payOrigin 参数见文档
     * @throws \Wchat\Exception
     * @return array
     */
    public function payUnifiedorder(array $payOrigin) {
        $pays['appid'] = $this->getAppId();
        $pays['mch_id'] = $this->getPayMchid();
        $pays['time_start'] = date('YmdHis');
        $pays['nonce_str'] = $this->strShuffle();
        $pays = array_merge($pays, $payOrigin);
        switch ($pays['trade_type']) {
            case 'NAVTIE':
                // 服务器的ip
                $pays['spbill_create_ip'] = $_SERVER['SERVER_ADDR'];
                break;
            default:
                // 用户的ip
                $pays['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        }
        $pays['sign'] = $this->paySignature($pays);

        $api = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $result = $this->xmlDecode($this->httpPost($api, $this->xmlEncode($pays)));
        if ($result['return_code'] !== 'SUCCESS') {
            $this->throws(array('errcode' => 80001, 'errmsg' => $result['return_msg']));
        }
        if ($result['result_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80002, 'errmsg' => $result['err_code_des']));
        }

        // 根据方式返回
        $return = array();
        switch ($pays['trade_type']) {
            case 'JSAPI':
                // 微网站支付
                $return['timeStamp'] = time();
                $return['appId'] = $this->getAppId();
                $return['nonceStr'] = $this->strShuffle();
                $return['signType'] = 'MD5';
                $return['package'] = "prepay_id={$result['prepay_id']}";
                $return['paySign'] = $this->paySignature($return);
                $return['timestamp'] = $return['timeStamp'];
                unset($return['timeStamp'], $return['appId']);
                break;
            case 'NATIVE':
                // 扫码支付
                $return['prepay_id'] = $result['prepay_id'];
                $return['code_url'] = $result['code_url'];
                break;
            case 'APP':
                // app支付
                $return['prepayid'] = $result['prepay_id'];
                $return['appid'] = $this->getAppId();
                $return['partnerid'] = $this->getPayMchid();
                $return['noncestr'] = $this->strShuffle();
                $return['package'] = 'Sign=WXPay';
                $return['timestamp'] = time();
                $return['sign'] = $this->paySignature($return);
                break;
            default:
                $this->throws(array('errcode' => 80003, 'errmsg' => 'not valid pay_type'));
        }

        return $return;
    }

    /**
     * 支付结果通用通知
     */
    public function payCallback() {
        // 通知微信我方已经收到通知，不要再通知了
        echo $this->xmlEncode(array('return_code' => 'SUCCESS', 'return_msg' => 'OK'));

        // 安全性检查
        $result = $this->xmlDecode($this->getPush());
        $flag = $result && empty($result['sign']) && $result['sign'] == $this->paySignature($result);
        if (!$flag) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }

        return $result;
    }

    /**
     * 订单查询
     * @param array $data 具体参数见文档
     * @throws \Wchat\Exception
     * @return array
     */
    public function payQuery(array $data) {
        $query['appid'] = $this->getAppId();
        $query['mch_id'] = $this->getPayMchid();
        $query['nonce_str'] = $this->strShuffle();
        $query = array_merge($query, $data);
        $query['sign'] = $this->paySignature($query);

        $api = 'https://api.mch.weixin.qq.com/pay/orderquery';

        $result = $this->xmlDecode($this->httpPost($api, $this->xmlEncode($query)));
        if ($result['return_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80004, 'errmsg' => $result['return_msg']));
        }

        if ($result['result_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80005, 'errmsg' => $result['err_code_des']));
        }

        return $result;
    }

    /**
     * 关闭订单
     * @param array $data 参数见文档
     * @throws \Wchat\Exception
     * @return array
     */
    public function payClose(array $data) {
        $close['appid'] = $this->getAppId();
        $close['mch_id'] = $this->getPayMchid();
        $close['nonce_str'] = $this->strShuffle();
        $close = array_merge($close, $data);
        $close['sign'] = $this->paySignature($close);

        $api = 'https://api.mch.weixin.qq.com/pay/closeorder';
        $result = $this->xmlDecode($this->httpPost($api, $this->xmlEncode($close)));

        if ($result['return_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80004, 'errmsg' => $result['return_msg']));
        }

        if ($result['result_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80005, 'errmsg' => $result['err_code_des']));
        }

        return $result;
    }

    /**
     * 申请退款
     * @param array $data 参数见文档
     * @throws \Wchat\Exception
     * @return array
     */
    public function payRefund(array $data) {
        $refund['appid'] = $this->getAppId();
        $refund['mch_id'] = $this->getPayMchid();
        $refund['nonce_str'] = $this->strShuffle();
        $refund['op_user_id'] = $this->getPayMchid();
        $refund = array_merge($refund, $data);
        $refund['sign'] = $this->paySignature($refund);

        $api = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

        $this->setUseCert(TRUE);
        $result = $this->xmlDecode($this->httpPost($api, $this->xmlEncode($refund)));
        if ($result['return_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80004, 'errmsg' => $result['return_msg']));
        }

        if ($result['result_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80005, 'errmsg' => $result['err_code_des']));
        }
        $this->setUseCert(FALSE);

        return $result;
    }

    /**
     * 查询退款
     * @param array $data 参数见文档
     * @throws \Wchat\Exception
     * @return array
     */
    public function payRefundQuery(array $data) {
        $refund['appid'] = $this->getAppId();
        $refund['mch_id'] = $this->getPayMchid();
        $refund['nonce_str'] = $this->strShuffle();
        $refund = array_merge($refund, $data);
        $refund['sign'] = $this->paySignature($refund);

        $api = 'https://api.mch.weixin.qq.com/pay/refundquery';

        $result = $this->xmlDecode($this->httpPost($api, $this->xmlEncode($refund)));
        if ($result['return_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80004, 'errmsg' => $result['return_msg']));
        }

        if ($result['result_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80005, 'errmsg' => $result['err_code_des']));
        }

        return $result;
    }

    /**
     * 下载对账单
     * @param array $data 参数见文档
     * @return mixed
     */
    public function payDownLoadBill(array $data){
        $bills['appid'] = $this->getAppId();
        $bills['mch_id'] = $this->getPayMchid();
        $bills['nonce_str'] = $this->strShuffle();
        $bills = array_merge($bills, $data);
        $bills['sign'] = $this->paySignature($bills);

        $api = 'https://api.mch.weixin.qq.com/pay/downloadbill';
        $result = $this->xmlDecode($this->httpPost($api, $this->xmlEncode($bills)));
        if (isset($result['return_code']) && $result['return_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80004, 'errmsg' => $result['return_msg']));
        }

        return $result;
    }

    /**
     * 转换短链接
     * @param string $url 需要转换的URL
     * @throws \Wchat\Exception
     * @return string
     */
    public function payShortUrl($url){
        $short['appid'] = $this->getAppId();
        $short['mch_id'] = $this->getPayMchid();
        $short['nonce_str'] = $this->strShuffle();
        $short['long_url'] = $url;
        $short['sign_type'] = 'MD5';
        $short['sign'] = $this->paySignature($short);

        $api = 'https://api.mch.weixin.qq.com/tools/shorturl';
        $result = $this->xmlDecode($this->httpPost($api, $this->xmlEncode($short)));
        if ($result['return_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80004, 'errmsg' => $result['return_msg']));
        }

        if ($result['result_code'] != 'SUCCESS') {
            $this->throws(array('errcode' => 80005, 'errmsg' => $result['err_code_des']));
        }

        return $result['url'];
    }
}