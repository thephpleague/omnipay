<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午2:57
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Mollie\Message\AbstractRequest;

class WapExpressPurchaseRequest extends BaseAbstractRequest
{

    protected $endpoint = 'http://wappaygw.alipay.com/service/rest.htm';

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        //必填
        $req_data = sprintf(
            '<auth_and_execute_req><request_token>%s</request_token></auth_and_execute_req>',
            $this->getToken()
        );
        /************************************************************/
        //构造要请求的参数数组，无需改动
        $data         = array(
            "service"        => "alipay.wap.auth.authAndExecute",
            "partner"        => $this->getPartner(),
            "sec_id"         => $this->getSignType(),
            "format"         => 'xml',
            "v"              => '2.0',
            "req_id"         => microtime(true) . '',
            "req_data"       => $req_data,
            "_input_charset" => $this->getInputCharset()
        );
        $data['sign'] = $this->getParamsSignature($data);
        return $data;
    }

    function getKey()
    {
        return $this->getParameter('key');
    }

    function setKey($value)
    {
        $this->setParameter('key', $value);
    }

    function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }

    function setInputCharset($value)
    {
        $this->setParameter('input_charset', $value);
    }

    function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    function setSignType($value)
    {
        $this->setParameter('sign_type', $value);
    }

    function getPartner()
    {
        return $this->getParameter('partner');
    }

    function setPartner($value)
    {
        $this->setParameter('partner', $value);
    }

    function sendData($data)
    {
        return $this->response = new WapExpressPurchaseResponse($this, $data);
    }

    function getEndpoint()
    {
        return $this->endpoint;
    }
}