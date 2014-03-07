<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-1 下午9:06
 *
 */
namespace Omnipay\Alipay\Message;

class ExpressPurchaseRequest extends BasePurchaseRequest
{

    protected function validateData()
    {
        parent::validateData();
        $this->validate(
            'total_fee'
        );
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validateData();
        $data              = array(
            "service"           => $this->getService(),
            "partner"           => $this->getPartner(),
            "payment_type"      => 1,
            "notify_url"        => $this->getNotifyUrl(),
            "return_url"        => $this->getReturnUrl(),
            "seller_email"      => $this->getSellerEmail(),
            "out_trade_no"      => $this->getOutTradeNo(),
            "subject"           => $this->getSubject(),
            "total_fee"         => $this->getTotalFee(),
            "currency"          => $this->getCurrency(),
            "body"              => $this->getBody(),
            "show_url"          => $this->getShowUrl(),
            "anti_phishing_key" => $this->getAntiPhishingKey(),
            "exter_invoke_ip"   => $this->getExterInvokeIp(),
            "paymethod"         => $this->getPayMethod(),
            "defaultbank"       => $this->getDefaultBank(),
            "_input_charset"    => $this->getInputCharset(),
        );
        $data              = array_filter($data);
        $data['sign']      = $this->getParamsSignature($data);
        $data['sign_type'] = $this->getSignType();
        return $data;
    }

    public function getTotalFee()
    {
        return $this->getParameter('total_fee');
    }

    public function setTotalFee($value)
    {
        $this->setParameter('total_fee', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($value)
    {
        $this->setParameter('currency', $value);
    }

    public function getDefaultBank()
    {
        return $this->getParameter('default_bank');
    }

    public function setDefaultBank($value)
    {
        $this->setParameter('default_bank', $value);
    }

    public function getPayMethod()
    {
        return $this->getParameter('pay_method');
    }

    public function setPayMethod($value)
    {
        $this->setParameter('pay_method', $value);
    }
}
