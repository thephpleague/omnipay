<?php

namespace Omnipay\PagSeguro\ValueObject;

class Credentials
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $token;

    /**
     * @param string $email
     * @param string $token
     */
    public function __construct($email, $token)
    {
        $this->setEmail($email);
        $this->setToken($token);
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    protected function setEmail($email)
    {
        $this->email = substr($email, 0, 60);
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    protected function setToken($token)
    {
        $this->token = substr($token, 0, 32);
    }
}
