<?php

namespace Omnipay\WireCard\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * WireCard Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{
    protected $testEndpoint = 'https://c3-test.wirecard.com/secure/ssl-gateway';
    protected $liveEndpoint = 'https://c3-test.wirecard.com/secure/ssl-gateway';

    public function getSecret()
    {
        return $this->getParameter('Secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('Secret', $value);
    }

    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = [
            'cartId'      => $this->getTransactionId(),
            'desc'        => $this->getDescription(),
            'amount'      => $this->getAmount(),
            'currency'    => $this->getCurrency(),
            'testMode'    => $this->getTestMode() ? 100 : 0,
            'MC_callback' => $this->getReturnUrl(),
            ];

        if ($this->getCard()) {

            $data = array_merge(
                $data, 
                [
                    'name'     => $this->getCard()->getName(),
                    'address1' => $this->getCard()->getAddress1(),
                    'address2' => $this->getCard()->getAddress2(),
                    'town'     => $this->getCard()->getCity(),
                    'region'   => $this->getCard()->getState(),
                    'postcode' => $this->getCard()->getPostcode(),
                    'country'  => $this->getCard()->getCountry(),
                    'tel'      => $this->getCard()->getPhone(),
                    'email'    => $this->getCard()->getEmail(),
                ]
            );

            return $data;

        }

        if ($this->getSecret()) {
            $fields  = 'customerId:shopId:orderIdent';
            $fields .= ':returnUrl:language:javascriptVersion:secret';
            $data['signatureFields'] = $fields; 
            $signature_data = [ 
                $this->getSecret(),
                $data['customerId'], 
                $data['shopId'],
                $data['orderIdent'],
                $data['returnUrl'],
                $data['language'],
                $data['requestFingerprint'],
            ];
            $data['signature'] = md5(implode(':', $signature_data));
        }

        return $data;
    }

    public function sendPreAuth()
    {
        $data = $this->getPreAuthData();
        $xml = $this->getPreauthXMLFromTemplate($data); 
        return $this->getResponse($xml, $data);
    }

    public function sendPayment()
    {
        $data = $this->getPaymentData();
        $xml = $this->getPaymentXMLFromTemplate($data); 
        return $this->getResponse($xml, $data);
    }

    protected function getPreauthXmlFromTemplate(array $preauthData)
    {
        $xml = <<<PREAUTHXML
            <?xml version="1.0" encoding="UTF-8"?> 
            <WIRECARD_BXML>
                <W_REQUEST>
                    <W_JOB>
                        <BusinessCaseSignature>0123456789ABCDEF</BusinessCaseSignature> 
                        <FNC_CC_PREAUTHORIZATION>
                            <FunctionID>authorization 1</FunctionID> 
                            <CC_TRANSACTION mode="demo">
                                <TransactionID>9457892347623478</TransactionID> 
                                <Amount minorunits="2">500</Amount> 
                                <Currency>EUR</Currency> 
                                <CountryCode>DE</CountryCode> 
                                <Usage>OrderNo-FT345S71 Thank you</Usage> 
                                <RECURRING_TRANSACTION>
                                    <Type>Initial</Type> 
                                </RECURRING_TRANSACTION> 
                                <CREDIT_CARD_DATA>
                                    <CreditCardNumber>4200000000000000</CreditCardNumber> 
                                    <CVC2>001</CVC2>
                                    <ExpirationYear>2019</ExpirationYear> 
                                    <ExpirationMonth>01</ExpirationMonth> 
                                    <CardHolderName>John Doe</CardHolderName>
                                </CREDIT_CARD_DATA> 
                                <CONTACT_DATA>
                                    <IPAddress>192.168.1.1</IPAddress> 
                                </CONTACT_DATA>
                            </CC_TRANSACTION> 
                        </FNC_CC_PREAUTHORIZATION>
                    </W_JOB> 
                </W_REQUEST> 
            </WIRECARD_BXML>
PREAUTHXML;
    }

    protected function getRequestXmlFromTemplate(array $requestData)
    {
        $xml = <<<XML
            <?xml version="1.0" encoding="UTF-8"?> <WIRECARD_BXML>
                <W_REQUEST>
                    <W_JOB>
                        <JobID>example ID Purchase J1</JobID> 
                        <BusinessCaseSignature>123</BusinessCaseSignature> 
                        <FNC_CC_PREAUTHORIZATION>
                            <FunctionID>example ID Purchase F1</FunctionID> 
                            <CC_TRANSACTION mode="demo">
                                <TransactionID>Authorization Initial 1</TransactionID> 
                                <Amount>1000</Amount>
                                <Currency>EUR</Currency>
                                <CountryCode>DE</CountryCode>
                                <Usage>Y6162</Usage> 
                                <RECURRING_TRANSACTION> 
                                    <Type>Initial</Type>
                                </RECURRING_TRANSACTION> 
                                <CREDIT_CARD_DATA>
                                    <CardHolderName>John Doe</CardHolderName> 
                                    <CreditCardNumber>5500000000000000</CreditCardNumber> 
                                    <ExpirationYear>2010</ExpirationYear> 
                                    <ExpirationMonth>12</ExpirationMonth> 
                                    <CVC2>471</CVC2>
                                </CREDIT_CARD_DATA> 
                                <CORPTRUSTCENTER_DATA>
                                    <ADDRESS>
                                        <FirstName>John</FirstName> 
                                        <LastName>Doe</LastName>
                                        <Address1>550 South Winchester blvd.</Address1> 
                                        <Address2>P.O. Box 850</Address2>
                                        <City>San Jose</City>
                                        <ZipCode>95128</ZipCode>
                                        <State>CA</State>
                                        <Country>US</Country> 
                                        <Phone>+1(1)8323933406</Phone> 
                                        <Email>John.Doe@email.com</Email>
                                    </ADDRESS>
                                    <PERSONINFO>
                                        <BirthDate>1982-04-17</BirthDate> 
                                    </PERSONINFO>
                                </CORPTRUSTCENTER_DATA> 
                                <CONTACT_DATA>
                                    <IPAddress>127.0.0.1</IPAddress> 
                                </CONTACT_DATA>
                            </CC_TRANSACTION>
                        </FNC_CC_PREAUTHORIZATION> 
                    </W_JOB>
                </W_REQUEST> 
            </WIRECARD_BXML>
XML;
    }

    public function send()
    {
        $this->listenForErrors();

        $httpRequest = $this->httpClient->createRequest(
            $this->getHttpMethod(),
            $this->getEndpoint(),
            null,
            $this->getData()
        );

        $httpResponse = $httpRequest->setHeader(
            'Authorization', 
            'Basic '.base64_encode($this->getApiKey().':')
        )->send();

        $this->response = new Response($this, $httpResponse->json());
        return $this->response;
    }

    protected function listenForErrors()
    {
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );
    }

}
