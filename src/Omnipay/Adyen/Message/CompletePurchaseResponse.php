<?php

namespace Omnipay\Adyen\Message;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Adyen Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function getResponse()
    {
        return $this;
    }

    public function isSuccessful()
    {
        $request = $this->request->getParameters();
        if ($request['testMode'] === true) {
            if ($this->data['authResult'] == 'AUTHORISED') {
                return true;
            } else {
                return false;
            }
        } else {
            $response = print_r($this, true);
            if (strstr($response, 'authResult=AUTHORISED')) {
                return true;
            } else {
                return false;
            }
        }
    }
}
