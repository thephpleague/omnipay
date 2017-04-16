<?php

namespace Omnipay\Payu\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * PayU Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $liveEndPoint = 'https://secure.payu.com.tr/order/alu/v2';
    protected $testEndPoint = 'https://secure.payu.com.tr/order/alu/v2';

    public function getMerchant()
    {
        return $this->getParameter('MERCHANT');
    }

    public function setMerchant($value)
    {
        return $this->setParameter('MERCHANT', $value);
    }

    public function getSecretKey()
    {
        return $this->getParameter('SECRET_KEY');
    }

    public function setSecretKey($value)
    {
        return $this->setParameter('SECRET_KEY', $value);
    }

    public function getData()
    {
        $data = array();
        $data['MERCHANT'] = $this->getMerchant();
        $data['ORDER_REF'] = rand(1000, 9999);
        $data['ORDER_DATE'] = gmdate('Y-m-d H:i:s');
        $data['PRICES_CURRENCY'] = $this->getCurrency();
        $data['PAY_METHOD'] = 'CCVISAMC';
        $card = $this->getCard();
        if ($card) {
            $data['SELECTED_INSTALLMENTS_NUMBER'] = '';
            $data['CC_NUMBER'] = $card->getNumber();
            $data['EXP_MONTH'] = $card->getExpiryMonth();
            $data['EXP_YEAR'] = $card->getExpiryYear();
            $data['CC_CVV'] = $card->getCvv();
            $data['CC_OWNER'] = $card->getName();
            $data['BACK_REF'] = '';
            $data['CLIENT_IP'] = $this->getClientIp();
            $data['BILL_LNAME'] = $card->getBillingFirstName();
            $data['BILL_FNAME'] = $card->getBillingLastName();
            $data['BILL_EMAIL'] = $card->getEmail();
            $data['BILL_PHONE'] = $card->getBillingPhone();
            $data['BILL_COUNTRYCODE'] = $card->getBillingCountry();
            $data['DELIVERY_FNAME'] = $card->getShippingFirstName();
            $data['DELIVERY_LNAME'] = $card->getShippingLastName();
            $data['DELIVERY_PHONE'] = $card->setShippingPhone();
            $data['DELIVERY_ADDRESS'] = $card->setShippingAddress1();
            $data['DELIVERY_ZIPCODE'] = $card->getShippingPostcode();
            $data['DELIVERY_CITY'] = $card->getShippingCity();
            $data['DELIVERY_STATE'] = $card->getShippingState();
            $data['DELIVERY_COUNTRYCODE'] = 'TR';
        }
        $items = $this->getItems();
        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $data['ORDER_PNAME['.$key.']'] = $item->getName();
                $data['ORDER_PCODE['.$key.']'] = $item->getName();
                $data['ORDER_PINFO['.$key.']'] = $item->getDescription();
                $data['ORDER_PPRICE['.$key.']'] = $item->getPrice();
                $data['ORDER_PQTY['.$key.']'] = $item->getQuantity();
            }

        }
        $data["ORDER_HASH"] = $this->generateHash($data);
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data, $this->getEndpoint());
    }
    private function generateHash($data){
        if ($this->getSecretKey()) {
            //begin HASH calculation
            ksort($data);
            $hashString = "";
            foreach ($data as $key => $val) {
                $hashString .= strlen($val) . $val;
            }
            return  hash_hmac("md5", $hashString, $this->getSecretKey());
        }

    }
}