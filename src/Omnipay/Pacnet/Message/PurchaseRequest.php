<?php

namespace Omnipay\Pacnet\Message;

/**
 * Pacnet Purchase Request
 */
class PurchaseRequest extends SubmitRequest
{
	public function getData()
	{
		$data = parent::getData();

		$data['Amount'] = $this->getAmountInteger();
		$data['PymtType'] = 'cc_debit';
		$data['CurrencyCode'] = $this->getCurrency();
		$data['CardNumber'] = $this->getCard()->getNumber();
		$data['ExpiryDate'] = str_pad($this->getCard()->getExpiryMonth(), 2, '0', STR_PAD_LEFT) . substr($this->getCard()->getExpiryYear(), -2, 2);

		if ($this->getCard()->getCvv()) {
			$data['CVV2'] = $this->getCard()->getCvv();
		}

		if ($this->getCard()->getName()) {
			$data['AccountName'] = $this->getCard()->getName();
		}

		if ($this->getCard()->getBrand()) {
			$data['CardBrand'] = $this->getCard()->getBrand();
		}

		$data['Signature'] = $this->generateSignature($data);

        return $data;
	}
}