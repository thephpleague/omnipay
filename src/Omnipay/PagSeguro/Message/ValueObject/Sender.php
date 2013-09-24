<?php
namespace Omnipay\PagSeguro\Message\ValueObject;

class Sender
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Omnipay\PagSeguro\Message\ValueObject\Phone
     */
    private $phone;

    /**
     * @param string $email
     * @param string $name
     * @param \Omnipay\PagSeguro\Message\ValueObject\Phone $phone
     */
    public function __construct(
        $email,
        $name = null,
        Phone $phone = null
    ) {
        $this->setPhone($phone);
        $this->setEmail($email);

        if ($name !== null) {
            $this->setName($name);
        }
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    protected function setName($name)
    {
        $this->name = substr($name, 0, 50);
    }

    /**
     * @return \Omnipay\PagSeguro\Message\ValueObject\Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param \Omnipay\PagSeguro\Message\ValueObject\Phone $phone
     */
    protected function setPhone(Phone $phone = null)
    {
        $this->phone = $phone;
    }
}
