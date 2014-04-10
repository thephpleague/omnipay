<?php

namespace Omnipay\Common;

use Omnipay\Tests\TestCase;

class IssuerTest extends TestCase
{
    public function testConstruct()
    {
        $issuer = new Issuer('99', 'Acme Corp');

        $this->assertSame('99', $issuer->getId());
        $this->assertSame('Acme Corp', $issuer->getName());
        $this->assertNull($issuer->getPaymentMethod());
    }

    public function testConstructWithPaymentMethod()
    {
        $issuer = new Issuer('99', 'Acme Corp', 'ideal');

        $this->assertSame('99', $issuer->getId());
        $this->assertSame('Acme Corp', $issuer->getName());
        $this->assertSame('ideal', $issuer->getPaymentMethod());
    }
}
