<?php
function purchaseXml()
{
    return "<?xml version='1.0' encoding='UTF-8'?>
        <WIRECARD_BXML xmlns:xsi='http://www.w3.org/1999/XMLSchema-instance'
                    xsi:noNamespaceSchemaLocation='wirecard.xsd'>
            <W_REQUEST>
                <W_JOB>
                    <JobID>job 2</JobID>
                    <BusinessCaseSignature>56501</BusinessCaseSignature>
                    <FNC_CC_PURCHASE>
                        <FunctionID>WireCard Test</FunctionID>
                        <CC_TRANSACTION>
                            <TransactionID>2</TransactionID>
                            <Amount>_AMOUNT_</Amount>
                            <Currency>_CURRENCY_</Currency>
                            <CountryCode>_COUNTRY_CODE_</CountryCode>
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
                            <CORPTRUSTCENTER_DATA>
                                <ADDRESS>
                                    <Address1></Address1>
                                    <City></City>
                                    <ZipCode></ZipCode>
                                    <State></State>
                                    <Country></Country>
                                    <Phone></Phone>
                                    <Email>support@wirecard.com</Email>
                                </ADDRESS>
                            </CORPTRUSTCENTER_DATA>
                        </CC_TRANSACTION>
                    </FNC_CC_PURCHASE>
                </W_JOB>
            </W_REQUEST>
        </WIRECARD_BXML>";
}
 
function send($post) {
    $header = getHeaders();
    $settings = getCredentials();
    $url = $settings['url'];
    $ch = curl_init ();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_POST, 0);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
    if ($header != "") {
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    ob_start ();
    $result = curl_exec ($ch);
    ob_end_clean ();
 
    curl_close ($ch);
 
    return $result;
}
 
function getCredentials()
{
    return [
        'business_case_signature' => "56501",
        'password' => "TestXAPTER",
        'url'   => "https://c3-test.wirecard.com/secure/ssl-gateway",
    ];
}
 
 
function getHeaders()
{
    $settings = getCredentials();
    $header = [
        "Authorization: Basic " . 
        base64_encode(
            $settings['business_case_signature']. ":" . 
            $settings['password'] . "\n"
        ),
        "Content-Type: text/xml"
        ];
    return $header;
}
 
function prepareTemplateForSending($tpl, array $cardDetails, array $amountDetails)
{
    $settings = getCredentials();
    foreach ([$settings, $cardDetails, $amountDetails] as $details) {
        foreach ($details as $k => $v) {
            $toReplace = '_' . strtoupper($k) . '_';
            $tpl = str_replace($toReplace, $v, $tpl);
        }
    }
 
    return $tpl;
};
 
$cardDetails = [
    'credit_card_number' => '4200000000000000',
    'cvc2'               => '000',
    'expiration_year'    => '2014',
    'expiration_month'   => '01',
    'card_holder_name'   => 'Wire Card Test',
];
 
$amountDetails = [ 
    'amount' => 300,
    'currency' => 'EUR',
    'country_code' => 'ES',
];
 
function makePurchaseRequest(array $cardDetails, array $amountDetails)
{
    $xml = purchaseXml();
    return prepareTemplateForSending($xml, $cardDetails, $amountDetails);
}
 
/**
 * Right, so we only have purchase at the moment, but I've 
 * done it so that we just add a template and change data
 * for the relevant request
 * 
 * @TODO Capture, Refund, Pre-Authorize, Authorize
 */
$requestXml = makePurchaseRequest($cardDetails, $amountDetails);
$data = send($requestXml);
 
if ($data == false) {
    print ("unable to contact WireCard payment service");
} else {
    print ("Response:\n\n");
    print ($data);
    print ("\n");
}
