<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://live.sagepay.com/gateway/service';
    protected $testEndpoint = 'https://test.sagepay.com/gateway/service';
    protected $simulatorEndpoint = 'https://test.sagepay.com/Simulator';

    public function getVendor()
    {
        return $this->getParameter('vendor');
    }

    public function setVendor($value)
    {
        return $this->setParameter('vendor', $value);
    }

    public function getSimulatorMode()
    {
        return $this->getParameter('simulatorMode');
    }

    public function setSimulatorMode($value)
    {
        return $this->setParameter('simulatorMode', $value);
    }

    public function getService()
    {
        return $this->getParameter('action');
    }

    protected function getBaseData()
    {
        $data = array();
        $data['VPSProtocol'] = '2.23';
        $data['TxType'] = $this->action;
        $data['Vendor'] = $this->getVendor();

        return $data;
    }

    public function send()
    {
        $httpResponse = $this->httpClient->post($this->getEndpoint(), null, $this->getData())->send();

        return $this->createResponse($httpResponse->getBody());
    }

    public function getEndpoint()
    {
        $service = strtolower($this->getService());

        if ($this->getSimulatorMode()) {
            // hooray for consistency
            if ($service == 'vspdirect-register') {
                return $this->simulatorEndpoint.'/VSPDirectGateway.asp';
            } elseif ($service == 'vspserver-register') {
                return $this->simulatorEndpoint.'/VSPServerGateway.asp?Service=VendorRegisterTx';
            } elseif ($service == 'direct3dcallback') {
                return $this->simulatorEndpoint.'/VSPDirectCallback.asp';
            }

            return $this->simulatorEndpoint.'/VSPServerGateway.asp?Service=Vendor'.ucfirst($service).'Tx';
        }

        if ($this->getTestMode()) {
            return $this->testEndpoint."/$service.vsp";
        }

        return $this->liveEndpoint."/$service.vsp";
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    //
    // Helper Methods used to switch between OmniPay cardBrand and SagePay CardType
    //
    
    public static function convertCardTypeOmniPayToSagePay($omniPayCardType)
    {
        switch ($omniPayCardType) {
            case \Omnipay\Common\CreditCard::BRAND_VISA:
                return 'VISA';
            case \Omnipay\Common\CreditCard::BRAND_VISA_DEBIT:
                return 'DELTA';
            case \Omnipay\Common\CreditCard::BRAND_VISA_ELECTRON:
                return 'UKE';
            case \Omnipay\Common\CreditCard::BRAND_MASTERCARD:
                return 'MC';
            case \Omnipay\Common\CreditCard::BRAND_AMEX:
                return 'AMEX';
            case \Omnipay\Common\CreditCard::BRAND_DINERS_CLUB:
                return 'DC';
            case \Omnipay\Common\CreditCard::BRAND_JCB:
                return 'JCB';
            case \Omnipay\Common\CreditCard::BRAND_MAESTRO:
                return 'MAESTRO';
            case \Omnipay\Common\CreditCard::BRAND_LASER:
                return 'LASER';

            /* the remaining Omnipay Card Types aren't handled by SagePay */
            default:
                if (strpos($omniPayCardType, 'sagepay_') !== false) {
                    // a sagepay specific cardtype such as sagepay_PAYPAL
                    return substr($omniPayCardType, strlen('sagepay_'));
                } else {
                    return null;
                }
        }
    }

    public static function convertCardTypeSagePayToOmniPay($sagePayCardType)
    {
        switch ($sagePayCardType) {
            case 'VISA':
                return \Omnipay\Common\CreditCard::BRAND_VISA;
            case 'MC':
                return \Omnipay\Common\CreditCard::BRAND_MASTERCARD;
            case 'DELTA':
                return \Omnipay\Common\CreditCard::BRAND_VISA_DEBIT;
            case 'MAESTRO':
                return \Omnipay\Common\CreditCard::BRAND_MAESTRO;
            case 'UKE':
                return \Omnipay\Common\CreditCard::BRAND_VISA_ELECTRON;
            case 'AMEX':
                return \Omnipay\Common\CreditCard::BRAND_AMEX;
            case 'DC':
                return \Omnipay\Common\CreditCard::BRAND_DINERS_CLUB;
            case 'JCB':
                return \Omnipay\Common\CreditCard::BRAND_JCB;
            case 'LASER':
                return \Omnipay\Common\CreditCard::BRAND_LASER;

            /* the remaining SagePay Card Types aren't handled specifically by Omnipay */
            default:
                // pass out sagepay specific cardtype such as sagepay_PAYPAL
                return 'sagepay_'.$sagePayCardType;
        }
    }
}
