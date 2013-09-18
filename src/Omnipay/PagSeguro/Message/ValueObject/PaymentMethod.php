<?php

namespace Omnipay\PagSeguro\Message\ValueObject;

class PaymentMethod
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var int
     */
    private $code;

    /**
     * @param int $type
     * @param int $code
     */
    public function __construnct($type, $code)
    {
        $this->setType($type);
        $this->setCode($code);
    }

    /**
     * @return number
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param number $type
     */
    protected function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return number
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param number $code
     */
    protected function setCode($code)
    {
        $this->code = $code;
    }
}
