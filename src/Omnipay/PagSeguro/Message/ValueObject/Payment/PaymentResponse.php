<?php

namespace Omnipay\PagSeguro\Message\ValueObject\Payment;

use DateTime;

class PaymentResponse
{
    /**
     * @var string
     */
    const REDIRECT_URL = 'https://pagseguro.uol.com.br/v2/checkout/payment.html';

    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @param string $code
     * @param \DateTime $date
     */
    public function __construct($code, DateTime $date)
    {
        $this->setCode($code);
        $this->setDate($date);
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    protected function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    protected function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getRedirectionUrl()
    {
        return static::REDIRECT_URL . '?code=' . $this->getCode();
    }
}
