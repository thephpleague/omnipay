<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-1 下午9:06
 *
 */
namespace Omnipay\Alipay\Message;

class SecuredPurchaseRequest extends BasePurchaseRequest
{

    protected function validateData()
    {
        parent::validateData();
        $this->validate(
            'out_trade_no',
            'subject',
            'price',
            'quantity',
            'logistics_fee',
            'logistics_type',
            'logistics_payment'
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
            "price"             => $this->getPrice(),
            "quantity"          => $this->getQuantity(),
            "logistics_fee"     => $this->getLogisticsFee(),
            "logistics_type"    => $this->getLogisticsType(),
            "logistics_payment" => $this->getLogisticsPayment(),
            "body"              => $this->getBody(),
            "show_url"          => $this->getShowUrl(),
            "receive_name"      => $this->getReceiveName(),
            "receive_address"   => $this->getReceiveAddress(),
            "receive_zip"       => $this->getReceiveZip(),
            "receive_phone"     => $this->getReceivePhone(),
            "receive_mobile"    => $this->getReceiveMobile(),
            "_input_charset"    => $this->getInputCharset()
        );
        $data              = array_filter($data);
        $data['sign']      = $this->getParamsSignature($data);
        $data['sign_type'] = $this->getSignType();
        return $data;
    }

    public function getPrice()
    {
        return $this->getParameter('price');
    }

    public function setPrice($value)
    {
        $this->setParameter('price', $value);
    }

    public function getQuantity()
    {
        return $this->getParameter('quantity');
    }

    public function setQuantity($value)
    {
        $this->setParameter('quantity', $value);
    }

    public function getLogisticsFee()
    {
        return $this->getParameter('logistics_fee');
    }

    public function setLogisticsFee($value)
    {
        $this->setParameter('logistics_fee', $value);
    }

    public function getLogisticsType()
    {
        return $this->getParameter('logistics_type');
    }

    public function setLogisticsType($value)
    {
        $this->setParameter('logistics_type', $value);
    }

    public function getLogisticsPayment()
    {
        return $this->getParameter('logistics_payment');
    }

    public function setLogisticsPayment($value)
    {
        $this->setParameter('logistics_payment', $value);
    }

    public function getReceiveName()
    {
        return $this->getParameter('receive_name');
    }

    public function setReceiveName($value)
    {
        $this->setParameter('receive_name', $value);
    }

    public function getReceiveAddress()
    {
        return $this->getParameter('receive_address');
    }

    public function setReceiveAddress($value)
    {
        $this->setParameter('receive_address', $value);
    }

    public function getReceiveZip()
    {
        return $this->getParameter('receive_zip');
    }

    public function setReceiveZip($value)
    {
        $this->setParameter('receive_zip', $value);
    }

    public function getReceivePhone()
    {
        return $this->getParameter('receive_phone');
    }

    public function setReceivePhone($value)
    {
        $this->setParameter('receive_phone', $value);
    }

    public function getReceiveMobile()
    {
        return $this->getParameter('receive_mobile');
    }

    public function setReceiveMobile($value)
    {
        $this->setParameter('receive_mobile', $value);
    }
}
