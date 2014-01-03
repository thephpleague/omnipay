<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-2 上午2:57
 *
 */
namespace Omnipay\Alipay\Message;

use DOMDocument;

class WapExpressAuthorizeRequest extends BaseAbstractRequest
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
        $this->validate(
            'notify_url',
            'return_url',
            'seller_email',
            'out_trade_no',
            'subject',
            'total_fee',
            'cancel_url'
        );
        $format       = '<direct_trade_create_req>' . /**/
          '<notify_url>%s</notify_url>' . /**/
          '<call_back_url>%s</call_back_url>' . /**/
          '<seller_account_name>%s</seller_account_name>' . /**/
          '<out_trade_no>%s</out_trade_no>' . /**/
          '<subject>%s</subject>' . /**/
          '<total_fee>%s</total_fee>' . /**/
          '<merchant_url>%s</merchant_url>' . /**/
          '</direct_trade_create_req>';
        $req_data     = sprintf(
            $format,
            $this->getNotifyUrl(),
            $this->getReturnUrl(),
            $this->getSellerEmail(),
            $this->getOutTradeNo(),
            $this->getSubject(),
            $this->getTotalFee(),
            $this->getCancelUrl()
        );
        $data         = array(
            "service"        => "alipay.wap.trade.create.direct",
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
        return $this->setParameter('key', $value);
    }

    public function getInputCharset()
    {
        return $this->getParameter('input_charset');
    }

    public function setInputCharset($value)
    {
        return $this->setParameter('input_charset', $value);
    }

    public function getSignType()
    {
        return $this->getParameter('sign_type');
    }

    public function setSignType($value)
    {
        return $this->setParameter('sign_type', $value);
    }

    public function getPartner()
    {
        return $this->getParameter('partner');
    }

    public function setPartner($value)
    {
        return $this->setParameter('partner', $value);
    }

    public function getTotalFee()
    {
        return $this->getParameter('total_fee');
    }

    public function setTotalFee($value)
    {
        return $this->setParameter('total_fee', $value);
    }

    public function getSubject()
    {
        return $this->getParameter('subject');
    }

    public function setSubject($value)
    {
        return $this->setParameter('subject', $value);
    }

    public function getOutTradeNo()
    {
        return $this->getParameter('out_trade_no');
    }

    public function setOutTradeNo($value)
    {
        return $this->setParameter('out_trade_no', $value);
    }

    public function getSellerEmail()
    {
        return $this->getParameter('seller_email');
    }

    public function setSellerEmail($value)
    {
        return $this->setParameter('seller_email', $value);
    }

    public function getNotifyUrl()
    {
        return $this->getParameter('notify_url');
    }

    public function setNotifyUrl($value)
    {
        return $this->setParameter('notify_url', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('return_url');
    }

    public function setReturnUrl($value)
    {
        return $this->setParameter('return_url', $value);
    }

    public function getCancelUrl()
    {
        return $this->getParameter('cancel_url');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancel_url', $value);
    }

    public function sendData($data)
    {
        $responseText = $this->httpClient->post($this->endpoint, array(), $this->getData()) /**/
          ->send()->getBody(true);
        //die($responseText);
        $responseData = $this->parseResponse($responseText);
        //var_dump($responseData);
        return $this->response = new WapExpressAuthorizeResponse($this, $responseData);
    }

    public function parseResponse($str_text)
    {
        $str_text   = urldecode($str_text); //URL转码
        $para_split = explode('&', $str_text);
        $data       = array();
        foreach ($para_split as $item) {
            $nPos       = strpos($item, '=');
            $nLen       = strlen($item);
            $key        = substr($item, 0, $nPos);
            $value      = substr($item, $nPos + 1, $nLen - $nPos - 1);
            $data[$key] = $value;
        }
        if (!empty ($data['res_data'])) {
            if ($this->getSignType() == '0001') {
                $data['res_data'] = rsaDecrypt($data['res_data'], $this->getPrivateKey());
            }
            $doc = new DOMDocument();
            $doc->loadXML($data['res_data']);
            $data['request_token'] = $doc->getElementsByTagName("request_token")->item(0)->nodeValue;
        }
        return $data;
    }
}
