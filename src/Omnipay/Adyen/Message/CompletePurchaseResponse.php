<?php

namespace Omnipay\Adyen\Message;
use Omnipay\Common\Message\AbstractResponse;
/**
 * Adyen Complete Purchase Response
 */
class CompletePurchaseResponse extends AbstractResponse
{

    public function getResponse() { return $this; }

    public function getResponseError() { return $this->response; }

    public function isSuccessful() { 
		if($this->data['authResult'] === 'AUTHORISED'){
			return true;
		}else return false;
	}

}
