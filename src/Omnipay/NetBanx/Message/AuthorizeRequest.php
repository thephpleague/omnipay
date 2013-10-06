<?php

namespace Omnipay\NetBanx\Message;

use Omnipay\Common\CreditCard;

/**
 * NetBanx Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    const MODE_AUTH = 'ccAuthorize';
    const MODE_STORED_DATA_AUTH = 'ccStoredDataAuthorize';

    /**
     * Method
     *
     * @var string
     */
    protected $txnMode;

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if ($this->getTransactionReference() || $this->getCardReference()) {
            $this->txnMode = $this->getStoredDataMode();
            $this->validate('amount');
        } else {
            $this->txnMode = $this->getBasicMode();
            $this->validate('amount', 'card');
            $this->getCard()->validate();
        }

        $data = $this->getBaseData();
        $data['txnRequest'] = $this->getXmlString();

        return $data;
    }

    /**
     * Get XML string
     *
     * @return string
     */
    protected function getXmlString()
    {
        if ($this->getTransactionReference() || $this->getCardReference()) {
            $xmlRoot = 'ccStoredDataRequestV1';
        } else {
            $xmlRoot = 'ccAuthRequestV1';
        }

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
                <{$xmlRoot}
                    xmlns=\"http://www.optimalpayments.com/creditcard/xmlschema/v1\"
                    xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
                    xsi:schemaLocation=\"http://www.optimalpayments.com/creditcard/xmlschema/v1\" />";

        $sxml = new \SimpleXMLElement($xml);

        $merchantAccount = $sxml->addChild('merchantAccount');

        $merchantAccount->addChild('accountNum', $this->getAccountNumber());
        $merchantAccount->addChild('storeID', $this->getStoreId());
        $merchantAccount->addChild('storePwd', $this->getStorePassword());

        $sxml->addChild('merchantRefNum', $this->getCustomerId() ?: 'ref-num - ' . time());

        if ($this->getTransactionReference() || $this->getCardReference()) {
            $sxml->addChild('confirmationNumber', $this->getTransactionReference() ?: $this->getCardReference());
            $sxml->addChild('amount', $this->getAmount());
        } else {
            /** @var $card CreditCard */
            $card = $this->getCard();

            $sxml->addChild('amount', $this->getAmount());

            $cardChild = $sxml->addChild('card');

            $cardChild->addChild('cardNum', $card->getNumber());

            $cardExpiry = $cardChild->addChild('cardExpiry');
            $cardExpiry->addChild('month', $card->getExpiryDate('m'));
            $cardExpiry->addChild('year', $card->getExpiryDate('Y'));

            $cardChild->addChild('cardType', $this->translateCardType($card->getBrand()));
            $cardChild->addChild('cvdIndicator', '1');
            $cardChild->addChild('cvd', $card->getCvv());

            $billingDetails = $sxml->addChild('billingDetails');

            $billingDetails->addChild('cardPayMethod', 'WEB');
            $billingDetails->addChild('firstName', $card->getBillingFirstName());
            $billingDetails->addChild('lastName', $card->getBillingLastName());
            $billingDetails->addChild('street', $card->getBillingAddress1());
            $billingDetails->addChild('street2', $card->getBillingAddress2());
            $billingDetails->addChild('city', $card->getBillingCity());
            $billingDetails->addChild('state', $card->getBillingState());
            $billingDetails->addChild('country', $card->getBillingCountry());
            $billingDetails->addChild('zip', $card->getBillingPostcode());
            $billingDetails->addChild('phone', $card->getBillingPhone());
            $billingDetails->addChild('email', $card->getEmail());

            $shippingDetails = $sxml->addChild('shippingDetails');

            $shippingDetails->addChild('firstName', $card->getShippingFirstName());
            $shippingDetails->addChild('lastName', $card->getShippingLastName());
            $shippingDetails->addChild('street', $card->getShippingAddress1());
            $shippingDetails->addChild('street2', $card->getShippingAddress2());
            $shippingDetails->addChild('city', $card->getShippingCity());
            $shippingDetails->addChild('state', $card->getShippingState());
            $shippingDetails->addChild('country', $card->getShippingCountry());
            $shippingDetails->addChild('zip', $card->getShippingPostcode());
            $shippingDetails->addChild('phone', $card->getShippingPhone());
            $shippingDetails->addChild('email', $card->getEmail());
        }

        return $sxml->asXML();
    }

    /**
     * Get Stored Data Mode
     *
     * @return string
     */
    protected function getStoredDataMode()
    {
        return self::MODE_STORED_DATA_AUTH;
    }

    /**
     * Get Stored Data Mode
     *
     * @return string
     */
    protected function getBasicMode()
    {
        return self::MODE_AUTH;
    }
}
