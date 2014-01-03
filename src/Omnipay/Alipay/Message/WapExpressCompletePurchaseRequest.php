<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 下午11:29
 *
 */
namespace Omnipay\Alipay\Message;

use DOMDocument;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;

class WapExpressCompletePurchaseRequest extends AbstractRequest
{

    protected $endpoint = 'http://notify.alipay.com/trade/notify_query.do?';

    protected $endpoint_https = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('request_params', 'partner', 'private_key');
        $this->validateRequestParams('notify_data', 'trade_status');
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

    public function rsaDecrypt($content, $private_key)
    {
        $res     = openssl_pkey_get_private($private_key);
        $content = base64_decode($content);
        $result  = '';
        for ($i = 0; $i < strlen($content) / 128; $i++) {
            $data = substr($content, $i * 128, 128);
            openssl_private_decrypt($data, $decrypt, $res);
            $result .= $decrypt;
        }
        openssl_free_key($res);
        return $result;
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

    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }

    public function setPrivateKey($value)
    {
        return $this->setParameter('private_key', $value);
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
        return $this->setRequestParam('notify_id', $value);
    }

    public function getNotifyData()
    {
        return $this->getRequestParam('notify_data');
    }

    public function setNotifyData($value)
    {
        return $this->setRequestParam('notify_data', $value);
    }

    public function getOutTradeNo()
    {
        return $this->getRequestParam('out_trade_no');
    }

    public function setOutTradeNO($value)
    {
        return $this->setRequestParam('out_trade_no', $value);
    }

    public function getTradeNo()
    {
        return $this->getRequestParam('trade_no');
    }

    public function setTradeNO($value)
    {
        return $this->setRequestParam('trade_no', $value);
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

    public function decrypt($str)
    {
        return $this->rsaDecrypt($str, $this->getPrivateKey());
    }

    public function sendData($data)
    {
        $data        = array();
        $notify_data = $this->decrypt($this->getNotifyData());
        $doc         = new DOMDocument();
        $doc->loadXML($notify_data);
        if (!empty($doc->getElementsByTagName("notify")->item(0)->nodeValue)) {
            $out_trade_no = $doc->getElementsByTagName("out_trade_no")->item(0)->nodeValue;
            $trade_no     = $doc->getElementsByTagName("trade_no")->item(0)->nodeValue;
            $trade_status = $doc->getElementsByTagName("trade_status")->item(0)->nodeValue;
            $this->setOutTradeNO($out_trade_no);
            $this->setTradeNO($trade_no);
            $this->setTradeStatus($trade_status);
            if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                $data['verify_success'] = true;
            } else {
                $data['verify_success'] = false;
            }
        }
        return $this->response = new ExpressCompletePurchaseResponse($this, $data);
    }
}
