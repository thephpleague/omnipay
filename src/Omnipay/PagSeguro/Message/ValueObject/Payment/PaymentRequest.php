<?php

namespace Omnipay\PagSeguro\Message\ValueObject\Payment;

use Omnipay\PagSeguro\Message\ValueObject\Shipping;
use Omnipay\PagSeguro\Message\ValueObject\Sender;

class PaymentRequest
{
    /**
     * @var string
     */
    private $currency;

    /**
     * @var multitype:\Omnipay\PagSeguro\Message\ValueObject\Item
     */
    private $items;

    /**
     * @var string
     */
    private $reference;

    /**
     * @var \Omnipay\PagSeguro\Message\ValueObject\Sender
     */
    private $sender;

    /**
     * @var \Omnipay\PagSeguro\Message\ValueObject\Shipping
     */
    private $shipping;

    /**
     * @var float
     */
    private $extraAmount;

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @var int
     */
    private $maxUses;

    /**
     * @var int
     */
    private $maxAge;

    /**
     * @param multitype:\Omnipay\PagSeguro\Message\ValueObject\Item $items
     * @param string $reference
     * @param \Omnipay\PagSeguro\Message\ValueObject\Sender $sender
     * @param \Omnipay\PagSeguro\Message\ValueObject\Shipping $shipping
     * @param float $extraAmount
     * @param string $redirectUrl
     * @param int $maxUses
     * @param int $maxAge
     */
    public function __construct(
        array $items,
        $reference = null,
        Sender $sender = null,
        Shipping $shipping = null,
        $extraAmount = null,
        $redirectUrl = null,
        $maxUses = null,
        $maxAge = null
    ) {
        $this->setCurrency('BRL');
        $this->setItems($items);
        $this->setSender($sender);
        $this->setShipping($shipping);

        if ($reference !== null) {
            $this->setReference($reference);
        }

        if ($extraAmount !== null) {
            $this->setExtraAmount($extraAmount);
        }

        if ($redirectUrl !== null) {
            $this->setRedirectUrl($redirectUrl);
        }

        if ($maxUses !== null) {
            $this->setMaxUses($maxUses);
        }

        if ($maxAge !== null) {
            $this->setMaxAge($maxAge);
        }
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    protected function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    protected function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    protected function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return \Omnipay\PagSeguro\Message\ValueObject\Sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Sender $sender
     */
    protected function setSender(Sender $sender = null)
    {
        $this->sender = $sender;
    }

    /**
     * @return \Omnipay\PagSeguro\Message\ValueObject\Shipping
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Shipping $shipping
     */
    protected function setShipping(Shipping $shipping = null)
    {
        $this->shipping = $shipping;
    }

    /**
     * @return number
     */
    public function getExtraAmount()
    {
        return $this->extraAmount;
    }

    /**
     * @param number $extraAmount
     */
    protected function setExtraAmount($extraAmount)
    {
        $this->extraAmount = $extraAmount;
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @param string $redirectUrl
     */
    protected function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return number
     */
    public function getMaxUses()
    {
        return $this->maxUses;
    }

    /**
     * @param number $maxUses
     */
    protected function setMaxUses($maxUses)
    {
        $this->maxUses = $maxUses;
    }

    /**
     * @return number
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }

    /**
     * @param number $maxAge
     */
    protected function setMaxAge($maxAge)
    {
        $this->maxAge = $maxAge;
    }
}
