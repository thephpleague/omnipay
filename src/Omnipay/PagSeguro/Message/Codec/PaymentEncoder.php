<?php

namespace Omnipay\PagSeguro\Message\Codec;

use Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest;
use Omnipay\PagSeguro\Message\ValueObject\Credentials;
use Omnipay\PagSeguro\Message\ValueObject\Address;
use Omnipay\PagSeguro\Message\ValueObject\Item;

class PaymentEncoder
{
    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Credentials $credentials
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     * @return string
     */
    public function encode(Credentials $credentials, PaymentRequest $request)
    {
        $data = $this->createElement($credentials);

        $this->appendCurrency($data, $request);
        $this->appendItems($data, $request);
        $this->appendReference($data, $request);
        $this->appendSender($data, $request);
        $this->appendShipping($data, $request);
        $this->appendExtraAmount($data, $request);
        $this->appendRedirectUrl($data, $request);
        $this->appendMaxUses($data, $request);
        $this->appendMaxAge($data, $request);

        return $data;
    }

    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Credentials $credentials
     * @return array
     */
    protected function createElement(Credentials $credentials)
    {
        return array(
            'email' => $credentials->getEmail(),
            'token' => $credentials->getToken()
        );
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendCurrency(array &$data, PaymentRequest $request)
    {
        $data['currency'] = $request->getCurrency();
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendItems(array &$data, PaymentRequest $request)
    {
        foreach ($request->getItems() as $index => $item) {
            $this->appendItem($data, $index + 1, $item);
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Item $item
     */
    protected function appendItem(array &$data, $index, Item $item)
    {
        $data['itemId' . $index] = $item->getId();
        $data['itemDescription' . $index] = $item->getDescription();
        $data['itemAmount' . $index] = number_format($item->getAmount(), 2, '.', '');
        $data['itemQuantity' . $index] = $item->getQuantity();

        if ($item->getShippingCost()) {
            $data['itemShippingCost' . $index] = $item->getShippingCost();
        }

        if ($item->getWeight()) {
            $data['itemWeight' . $index] = $item->getWeight();
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendReference(array &$data, PaymentRequest $request)
    {
        if ($request->getReference()) {
            $data['reference'] = $request->getReference();
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendSender(array &$data, PaymentRequest $request)
    {
        if (!$request->getSender()) {
            return ;
        }

        $data['senderEmail'] = $request->getSender()->getEmail();

        if ($request->getSender()->getName()) {
            $data['senderName'] = $request->getSender()->getName();
        }

        if ($request->getSender()->getPhone()) {
            $data['senderAreaCode'] = $request->getSender()->getPhone()->getAreaCode();
            $data['senderPhone'] = $request->getSender()->getPhone()->getNumber();
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendShipping(array &$data, PaymentRequest $request)
    {
        if (!$request->getShipping()) {
            return ;
        }

        $data['shippingType'] = $request->getShipping()->getType();

        if ($request->getShipping()->getAddress()) {
            $this->appendAddress($data, $request->getShipping()->getAddress());
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Address $address
     */
    protected function appendAddress(array &$data, Address $address)
    {
        $data['shippingAddressCountry'] = $address->getCountry();

        if ($address->getState()) {
            $data['shippingAddressState'] = $address->getState();
        }

        if ($address->getCity()) {
            $data['shippingAddressCity'] = $address->getCity();
        }

        if ($address->getPostalCode()) {
            $data['shippingAddressPostalCode'] = $address->getPostalCode();
        }

        if ($address->getDistrict()) {
            $data['shippingAddressDistrict'] = $address->getDistrict();
        }

        if ($address->getStreet()) {
            $data['shippingAddressStreet'] = $address->getStreet();
        }

        if ($address->getNumber()) {
            $data['shippingAddressNumber'] = $address->getNumber();
        }

        if ($address->getComplement()) {
            $data['shippingAddressComplement'] = $address->getComplement();
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendExtraAmount(array &$data, PaymentRequest $request)
    {
        if ($request->getExtraAmount()) {
            $data['extraAmount'] = number_format($request->getExtraAmount(), 2, '.', '');
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendRedirectUrl(array &$data, PaymentRequest $request)
    {
        if ($request->getRedirectUrl()) {
            $data['redirectURL'] = $request->getRedirectUrl();
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendMaxUses(array &$data, PaymentRequest $request)
    {
        if ($request->getMaxUses()) {
            $data['maxUses'] = $request->getMaxUses();
        }
    }

    /**
     * @param array $data
     * @param \Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest $request
     */
    protected function appendMaxAge(array &$data, PaymentRequest $request)
    {
        if ($request->getMaxAge()) {
            $data['maxAge'] = $request->getMaxAge();
        }
    }
}
