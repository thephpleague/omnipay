<?php
namespace Omnipay\PagSeguro\Codec;

use Omnipay\PagSeguro\ValueObject\Payment\PaymentResponse;
use SimpleXMLElement;
use DateTime;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class PaymentDecoder
{
    /**
     * Decode a XML
     *
     * @param string $xml
     *
     * @return \Omnipay\PagSeguro\ValueObject\Payment\PaymentResponse
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
