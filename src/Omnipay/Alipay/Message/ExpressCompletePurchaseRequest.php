<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 下午11:29
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

class ExpressCompletePurchaseRequest extends AbstractRequest
{

    protected $endpoint = 'http://notify.alipay.com/trade/notify_query.do?';

    protected $endpoint_https = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';

    public $verifyResponse;

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('request_params', 'transport', 'partner', 'ca_cert_path', 'sign_type', 'key');
        $this->validateRequestParams('trade_status', 'out_trade_no', 'trade_no');
        return $this->getParameters();
    }

    public function validateRequestParams()
    {
        foreach (func_get_args() as $key) {
            $value = $this->getRequestParam($key);
            if ($value === null) {
                throw new InvalidRequestException("The request_params.$key parameter is required");
            }
        }
    }

    public function getParamsToSign()
    {
        $params = $this->getRequestParams();
        unset($params['sign']);
        unset($params['sign_type']);
        return $params;
    }

    public function getRequestParams()
    {
        return $this->getParameter('request_params');
    }

    public function setRequestParams($value)
    {
        return $this->setParameter('request_params', $value);
    }

    public function getRequestParam($key)
    {
        $params = $this->getRequestParams();
        if (isset($params[$key])) {
            return $params[$key];
        } else {
            return null;
        }
    }

    public function setRequestParam($key, $value)
    {
        $params       = $this->getRequestParams();
        $params[$key] = $value;
        return $this;
    }

    public function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    public function setSignType($value)
    {
        return $this->setParameter('sign_type', $value);
    }

    public function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }

    public function setInputCharset($value)
    {
        return $this->setParameter('input_charset', $value);
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        return $this->setParameter('key', $value);
    }

    public function getTransport()
    {
        return $this->getParameter('transport');
    }

    public function setTransport($value)
    {
        return $this->setParameter('transport', $value);
    }

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }

    public function getCaCertPath()
    {
        return $this->getParameter('ca_cert_path');
    }

    public function setCaCertPath($value)
    {
        if (!is_file($value)) {
            throw new InvalidRequestException("The ca_cert_path($value) is not exists");
        }
        return $this->setParameter('ca_cert_path', $value);
    }

    public function getNotifyId()
    {
        return $this->getRequestParam('notify_id');
    }

    public function setNotifyId($value)
    {
        return $this->setParameter('notify_id', $value);
    }

    public function getAliPubicKey()
    {
        return $this->getRequestParam('ali_public_key');
    }

    public function setAliPubicKey($value)
    {
        return $this->setParameter('ali_public_key', $value);
    }

    public function getTradeStatus()
    {
        return $this->getRequestParam('trade_status');
    }

    public function setTradeStatus($value)
    {
        return $this->setRequestParam('trade_status', $value);
    }

    public function getEndpoint()
    {
        if (strtolower($this->getTransport()) == 'http') {
            return $this->endpoint;
        } else {
            return $this->endpoint_https;
        }
    }

    protected function getParamsSignature($data)
    {
        ksort($data);
        reset($data);
        $query_string = http_build_query($data);
        $query_string = urldecode($query_string);
        $sign_type    = $this->getSignType();
        if ($sign_type == 'MD5') {
            $sign = md5($query_string . $this->getKey());
        } elseif ($sign_type == 'RSA') {
            $sign = $this->rsaVerify($query_string, trim($this->getAliPubicKey()), $this->getRequestParam('sign'));
        } elseif ($sign_type == '0001') {
            $sign = $this->rsaVerify($query_string, trim($this->getAliPubicKey()), $this->getRequestParam('sign'));
        } else {
            $sign = '';
        }
        return $sign;
    }

    protected function rsaVerify($data, $ali_public_key_path, $sign)
    {
        $pubKey = file_get_contents($ali_public_key_path);
        $res    = openssl_pkey_get_public($pubKey);
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        openssl_free_key($res);
        return $result;
    }

    public function sendData($data)
    {
        $notify_id = $this->getNotifyId();
        $sign = $this->getRequestParam('sign');
        $validateSign = !empty($sign);

        $this->verifyResponse = 'true';
        if (!is_null($notify_id)) {
            $this->verifyResponse = $this->getVerifyResponse($this->getNotifyId());
            $validateSign = true;
        }
        
        $data                    = array();
        $data['verify_response'] = $this->verifyResponse;
        if ($this->isResponseOk($this->verifyResponse) && (!$validateSign || $this->isSignMatch())) {
            $data['verify_success'] = true;
        } else {
            $data['verify_success'] = false;
        }
        return $this->response = new ExpressCompletePurchaseResponse($this, $data);
    }

    protected function isResponseOk()
    {
        if (preg_match("/true$/i", $this->verifyResponse)) {
            return true;
        } else {
            return false;
        }
    }

    protected function isSignMatch()
    {
        if ($this->getRequestParam('sign') == $this->getParamsSignature($this->getParamsToSign())) {
            return true;
        } else {
            return false;
        }
    }

    protected function getVerifyResponse($notify_id)
    {
        $partner     = $this->getPartner();
        $verify_url  = $this->getEndpoint();
        $verify_url  = $verify_url . "partner=" . $partner . "&notify_id=" . $notify_id;
        $responseTxt = $this->getHttpResponseGET($verify_url, $this->getCacertPath());
        return $responseTxt;
    }

    protected function getHttpResponseGET($url, $cacert_url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, $cacert_url);
        $responseText = curl_exec($curl);
        curl_close($curl);
        return $responseText;
    }
}
