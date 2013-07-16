<?php

namespace Omnipay\Sips\Message;

use Omnipay\Sips\Message\AuthorizeRequest;

/**
 * Sips Authorize Response
 */
class ReturnResponse extends Response
{
    protected function getResultComponents()
    {
        return array(
            'code' => 1,
            'debug' => 2,
            'merchantId' => 3,
            'merchantCountry' => 4,
            'amount' => 5,
            'transactionId' => 6,
            'paymentMeans' => 7,
            'transmissionDate' => 8,
            'paymentTime' => 9,
            'paymentDate' => 10,
            'responseCode' => 11,
            'paymentCertificate' => 12,
            'authorisationId' => 13,
            'currencyCode' => 14,
            'cardNumber' => 15,
            'cvvFlag' => 16,
            'cvvResponseCode' => 17,
            'bankResponseCode' => 18,
            'complementaryCode' => 19,
            'complementaryInfo' => 20,
            'returnContext' => 21,
            'caddie' => 22,
            'receiptComplement' => 23,
            'merchantLanguage' => 24,
            'language' => 25,
            'customerId' => 26,
            'orderId' => 27,
            'customerEmail' => 28,
            'customerIpAddress' => 29,
            'captureDay' => 30,
            'captureMode' => 31,
            'data' => 32
        );
    }
}
