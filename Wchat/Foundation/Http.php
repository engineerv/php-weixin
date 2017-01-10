<?php
/**
 * 微信http请求组件
 * @author yyq
 * @version 1.0
 */
namespace Wchat\Foundation;

trait Http {

    /**
     * 执行get请求
     * @param string $url 请求地址
     * @param array $data 附加参数
     * @return string
     */
    protected function httpGet($url, $data = array()) {
        $url = $data ? sprintf("%s?%s", $url, urldecode(http_build_query($data))) : $url;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($this->getUseCert()) {
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $this->getPayCertPem());
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $this->getPayCertKey());
        }
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * 执行post请求
     * @param string $url 请求地址
     * @param array $data 附加参数
     * @return string
     */
    protected function httpPost($url, $data) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($this->getUseCert()) {
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $this->getPayCertPem());
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $this->getPayCertKey());
        }
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * 文件上传请求
     * @param string $url 请求地址
     * @param array $data 附加参数
     * @return string
     */
    protected function httpUpload($url, array $data) {
        $data = $this->toCURLFile($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        @curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    /**
     * 兼容上传
     * @param array $data 待上传的文件
     * @return array
     */
    protected function toCURLFile($data) {
        if (class_exists('\CURLFile')) {
            foreach ($data as $key => $val) {
                if (is_array($data[$key])) {
                    //递归调用
                    $data[$key] = $this->toCURLFile($val);
                } else if (substr($val, 0, 1) == '@') {
                    $data[$key] = new \CURLFile(substr($val, 1));
                }
            }
        }
        return $data;
    }
}