<?php

namespace Omnipay\NetBanx\Message;

/**
 * NetBanx Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    /**
     * Method
     *
     * @var string
     */
    protected $txnMode = 'ccSettlement';

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount', 'transactionReference');

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
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <ccPostAuthRequestV1
                    xmlns="http://www.optimalpayments.com/creditcard/xmlschema/v1"
                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                    xsi:schemaLocation="http://www.optimalpayments.com/creditcard/xmlschema/v1" />';

        $sxml = new \SimpleXMLElement($xml);

        $merchantAccount = $sxml->addChild('merchantAccount');

        $merchantAccount->addChild('accountNum', $this->getAccountNumber());
        $merchantAccount->addChild('storeID', $this->getStoreId());
        $merchantAccount->addChild('storePwd', $this->getStorePassword());

        $sxml->addChild('confirmationNumber', $this->getTransactionReference());
        $sxml->addChild('merchantRefNum', $this->getCustomerId());
        $sxml->addChild('amount', $this->getAmount());

        return $sxml->asXML();
    }
}
