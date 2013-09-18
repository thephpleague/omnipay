<?php

namespace Omnipay\PagSeguro\Message\Codec;

use Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentResponse;
use SimpleXMLElement;
use DateTime;

class PaymentDecoder
{
    /**
     * @param string $xml
     * @return \Omnipay\PagSeguro\MessageValueObject\Payment\PaymentResponse
     */
    public function decode($xml)
    {
        $obj = new SimpleXMLElement($xml);

        return new PaymentResponse(
            (string) $obj->code,
            new DateTime((string) $obj->date)
        );
    }
}
