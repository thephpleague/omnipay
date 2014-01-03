<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午2:57
 *
 */
namespace Omnipay\Alipay\Message;

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

    public function getKey()
    {
        return $this->getParameter('key');
    }

    public function setKey($value)
    {
        $this->setParameter('key', $value);
    }

    public function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }

    public function setInputCharset($value)
    {
        $this->setParameter('input_charset', $value);
    }

    public function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    public function setSignType($value)
    {
        $this->setParameter('sign_type', $value);
    }

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        $this->setParameter('partner', $value);
    }

    public function sendData($data)
    {
        return $this->response = new WapExpressPurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
