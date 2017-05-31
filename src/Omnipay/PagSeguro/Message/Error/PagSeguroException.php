<?php

namespace Omnipay\PagSeguro\Message\Error;

use SimpleXMLElement;

class PagSeguroException extends \RuntimeException
{
    /**
     * @param SimpleXMLElement $xml
     * @return PagSeguroException
     */
    public static function createFromXml(SimpleXMLElement $xml)
    {
        $message = 'Some errors occurred:';

        foreach ($xml->error as $error) {
            $message .= PHP_EOL
                        . '[' . (string) $error->code . '] '
                        . (string) $error->message;
        }

        return new static($message);
    }
}
