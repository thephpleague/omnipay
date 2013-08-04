<?php

namespace Omnipay\WireCard\Message;

use Omnipay\WireCard\Message\AbstractRequest;

/**
 * WireCard Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $endpoint = 'https://c3-test.wirecard.com/secure/ssl-gateway';

    public function getEndPoint()
    {
        return $this->endpoint;
    }

    public function getCountryCode()
    {
        return $this->getParameter('countryCode');
    }

    public function setCountryCode($value)
    {
        return $this->setParameter('countryCode', $value);
    }

    public function getCardNumber()
    {
        return $this->getCard()->getNumber();
    }

    public function getData()
    {
        return [
            'business_case_signature' => "56501",
            'password'     => 'TestXAPTER',
            'amount'       => '300',//$this->getAmount(),
            'currency'     => $this->getCurrency(),
            'country_code' => $this->getCountryCode(),
            'credit_card_number' => $this->getCard()->getNumber(),
            'expiration_year'    => $this->getCard()->getExpiryYear(),
            'expiration_month'   => $this->getCard()->getExpiryMonth(),
            'card_holder_name'   => $this->getCard()->getName(),
            'cvc2'               => '000',
        ];
    }


    protected function getXml()
    {
        $data = $this->getData();
        $xml = <<<XML
            <?xml version='1.0' encoding='UTF-8'?>
            <WIRECARD_BXML xmlns:xsi='http://www.w3.org/1999/XMLSchema-instance'
                        xsi:noNamespaceSchemaLocation='wirecard.xsd'>
                <W_REQUEST>
                    <W_JOB>
                        <JobID>job 2</JobID>
                        <BusinessCaseSignature>_BUSINESS_CASE_SIGNATURE_</BusinessCaseSignature>
                        <FNC_CC_PURCHASE>
                            <FunctionID>Wire Card Test</FunctionID>
                            <CC_TRANSACTION mode="demo">
                                <TransactionID>2</TransactionID>
                                <Amount>_AMOUNT_</Amount>
                                <Currency>_CURRENCY_</Currency>
                                <CountryCode>_COUNTRY_CODE_</CountryCode>
                                <Usage>Usage</Usage>
                                <RECURRING_TRANSACTION>
                                    <Type>Single</Type>
                                </RECURRING_TRANSACTION>
                                <CREDIT_CARD_DATA>
                                    <CreditCardNumber>_CREDIT_CARD_NUMBER_</CreditCardNumber>
                                    <CVC2>_CVC2_</CVC2>
                                    <ExpirationYear>_EXPIRATION_YEAR_</ExpirationYear>
                                    <ExpirationMonth>_EXPIRATION_MONTH_</ExpirationMonth>
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
        foreach ($data as $k => $v) {
            $xml = str_replace('_' . strtoupper($k) . '_', $v, $xml);
        };

        return $xml;
    }


}
