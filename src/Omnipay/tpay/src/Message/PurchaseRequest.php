<?php

namespace Omnipay\Tpay\Message;

use Exception;
use InvalidArgumentException;
use Omnipay\Common\Exception\InvalidCreditCardException;

/**
 * Tpay Purchase Request
 */
class PurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        $this->validate('amount', 'currency');
        $this->isCardSupported();

        $data['method'] = 'securesale';
        $data['card'] = $this->hashCardData();
        $data['name'] = $this->getCard()->getBillingFirstName() . ' ' . $this->getCard()->getBillingLastName();
        $data['email'] = $this->getCard()->getEmail();
        $data['desc'] = $this->getDescription();
        $data['amount'] = $this->getAmount();
        $data['currency'] = $this->getCurrencyNumeric();
        if (!is_null($this->getOrderId())) {
            $data['order_id'] = $this->getOrderId();
        }
        if (is_null($this->getCardSave())) {
            $data['onetimer'] = 1;
        }
        $data['language'] = $this->getLanguage();
        $data['enable_pow_url'] = 1;
        $data['sign'] = $this->getSign($data);
        if (!is_null($this->getReturnUrl())) {
            $data['pow_url'] = $this->getReturnUrl();
        }
        if (!is_null($this->getCancelUrl())) {
            $data['pow_url_blad'] = $this->getCancelUrl();
        }
        $data['api_password'] = $this->getApiPassword();

        return $data;

    }

    private function isCardSupported()
    {
        $brand = $this->getCard()->getBrand();
        if (!($brand === 'maestro' || $brand === 'visa' || $brand === 'mastercard')) {
            throw new InvalidCreditCardException('This credit card is not supported' . $brand);
        }
    }

    private function hashCardData()
    {
        if (is_null($this->getCurrentDomain())) {
            throw new Exception('Please set domain request URL');
        }
        $card = $this->getCard();
        $cardData = $card->getNumber() . '|' . $card->getExpiryMonth() . '/' . $card->getExpiryYear() . '|'
            . $card->getCvv() . '|' . $this->getCurrentDomain();

        if (!openssl_public_encrypt($cardData, $encrypted, base64_decode($this->getRsaKey()))) {
            throw new InvalidArgumentException('Unable to encrypt card data.');
        }

        return base64_encode($encrypted);

    }

}
