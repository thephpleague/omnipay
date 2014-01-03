<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-3 上午1:27
 *
 */
namespace Omnipay\Alipay\Message;

use Exception;
use Omnipay\Common\Message\AbstractRequest;

abstract class BaseAbstractRequest extends AbstractRequest
{

    protected function getParamsSignature($data)
    {
        ksort($data);
        reset($data);
        $query_string = http_build_query($data);
        $query_string = urldecode($query_string);
        switch (strtoupper($this->getSignType())) {
            case "MD5":
                $sign = $this->md5Sign($query_string);
                break;
            case "RSA":
                $sign = $this->rsaSign($query_string, $this->getPrivateKey());
                break;
            case "0001":
                $sign = $this->rsaSign($query_string, $this->getPrivateKey());
                break;
            default:
                $sign = '';
        }
        return $sign;
    }

    protected function rsaSign($data, $private_key_path)
    {
        $priKey = file_get_contents($private_key_path);
        $res    = openssl_pkey_get_private($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * @param $query_string
     *
     * @return string
     */
    protected function md5Sign($query_string)
    {
        return md5($query_string . $this->getKey());
    }

    public function getPrivateKey()
    {
        return $this->getParameter('private_key');
    }

    public function setPrivateKey($value)
    {
        $this->setParameter('private_key', $value);
    }

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        $this->setParameter('key', $value);
    }

    public function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    public function setSignType($value)
    {
        if (in_array($value, array('md5', 'rsa'))) {
            throw new Exception('sign_type should be upper case');
        }
        $this->setParameter('sign_type', $value);
    }
}
