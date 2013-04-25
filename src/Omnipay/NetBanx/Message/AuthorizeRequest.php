<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\NetBanx\Message;

use Omnipay\Common\CreditCard;

/**
 * NetBanx Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    /**
     * Method
     *
     * @var string
     */
    protected $txnMode = 'ccAuthorize';

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount', 'card');
        $this->getCard()->validate();

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
        /** @var $card CreditCard */
        $card = $this->getCard();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <ccAuthRequestV1
                    xmlns="http://www.optimalpayments.com/creditcard/xmlschema/v1"
                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                    xsi:schemaLocation="http://www.optimalpayments.com/creditcard/xmlschema/v1" />';

        $sxml = new \SimpleXMLElement($xml);

        $merchantAccount = $sxml->addChild('merchantAccount');

        $merchantAccount->addChild('accountNum', $this->getAccountNumber());
        $merchantAccount->addChild('storeID', $this->getStoreId());
        $merchantAccount->addChild('storePwd', $this->getStorePassword());

        $sxml->addChild('merchantRefNum', $this->getCustomerId() ?: 'ref-num - ' . time());
        $sxml->addChild('amount', $this->getAmountDecimal());

        $cardChild = $sxml->addChild('card');

        $cardChild->addChild('cardNum', $card->getNumber());

        $cardExpiry = $cardChild->addChild('cardExpiry');
        $cardExpiry->addChild('month', $card->getExpiryDate('m'));
        $cardExpiry->addChild('year', $card->getExpiryDate('Y'));

        $cardChild->addChild('cardType', $this->getCardType() ?: 'VI');
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

        return $sxml->asXML();
    }
}
