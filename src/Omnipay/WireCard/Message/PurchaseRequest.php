<?php

namespace Omnipay\WireCard\Message;

use Omnipay\WireCard\Message\AbstractRequest;

/**
 * WireCard Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $xml = <<<XML
            <WIRECARD_BXML xmlns:xsi='http://www.w3.org/1999/XMLSchema-instance'
                        xsi:noNamespaceSchemaLocation='wirecard.xsd'>
                <W_REQUEST>
                    <W_JOB>
                        <JobID>job 2</JobID>
                        <BusinessCaseSignature></BusinessCaseSignature>
                        <FNC_CC_PURCHASE>
                            <FunctionID>Wire Card Test</FunctionID>
                            <CC_TRANSACTION mode="demo">
                                <TransactionID>2</TransactionID>
                                <Amount/>
                                <Currency/>
                                <CountryCode></CountryCode>
                                <Usage>Usage</Usage>
                                <RECURRING_TRANSACTION>
                                    <Type>Single</Type>
                                </RECURRING_TRANSACTION>
                                <CREDIT_CARD_DATA>
                                    <CreditCardNumber></CreditCardNumber>
                                    <CVC2></CVC2>
                                    <ExpirationYear/>
                                    <ExpirationMonth></ExpirationMonth>
                                    <CardHolderName>_CARD_HOLDER_NAME_</CardHolderName>
                                </CREDIT_CARD_DATA>
                                <CONTACT_DATA>
                                    <IPAddress>127.0.0.1</IPAddress>
                                </CONTACT_DATA>
                            </CC_TRANSACTION>
                        </FNC_CC_PURCHASE>
                    </W_JOB>
                </W_REQUEST>
            </WIRECARD_BXML>
XML;
        $xml =  new \SimpleXmlElement($xml);
        $xml->W_REQUEST->W_JOB->BusinessCaseSignature = $this->getSignature(); 
        $xml->W_REQUEST->W_JOB->FNC_CC_PURCHASE->CC_TRANSACTION->Amount = $this->getAmountInteger();
        $xml->W_REQUEST->W_JOB->FNC_CC_PURCHASE->CC_TRANSACTION->Currency = $this->getCurrency();
        $xml->W_REQUEST->W_JOB->FNC_CC_PURCHASE->CC_TRANSACTION->CountryCode = $this->getcountryCode();
        $xml->W_REQUEST->W_JOB->FNC_CC_PURCHASE->CC_TRANSACTION->CREDIT_CARD_DATA->CreditCardNumber = $this->getCard()->getNumber();
        // TODO Change
        $xml->W_REQUEST->W_JOB->FNC_CC_PURCHASE->CC_TRANSACTION->CREDIT_CARD_DATA->CVC2 = '000';
        $xml->W_REQUEST->W_JOB->FNC_CC_PURCHASE->CC_TRANSACTION->CREDIT_CARD_DATA->ExpirationYear = $this->getCard()->getExpiryYear();
        $xml->W_REQUEST->W_JOB->FNC_CC_PURCHASE->CC_TRANSACTION->CREDIT_CARD_DATA->ExpirationMonth = $this->getCard()->getExpiryMonth();
        $xml->W_REQUEST->W_JOB->FNC_CC_PURCHASE->CC_TRANSACTION->CREDIT_CARD_DATA->CardHolderName = $this->getCard()->getName();
        return $xml;
    }

}
