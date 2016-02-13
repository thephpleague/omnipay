<?php


namespace League\Omnipay\Common;


class CustomerTest extends \PHPUnit_Framework_TestCase 
{
    
    public function testTitle()
    {
        $customer = new Customer();
        $customer->setTitle('Mr.');
        $this->assertEquals('Mr.', $customer->getTitle());
    }

    public function testFirstName()
    {
        $customer = new Customer();
        $customer->setFirstName('Bob');
        $this->assertEquals('Bob', $customer->getFirstName());
    }

    public function testLastName()
    {
        $customer = new Customer();
        $customer->setLastName('Smith');
        $this->assertEquals('Smith', $customer->getLastName());
    }

    public function testGetName()
    {
        $customer = new Customer();
        $customer->setFirstName('Bob');
        $customer->setLastName('Smith');
        $this->assertEquals('Bob Smith', $customer->getName());
    }

    public function testSetName()
    {
        $customer = new Customer();
        $customer->setName('Bob Smith');
        $this->assertEquals('Bob', $customer->getFirstName());
        $this->assertEquals('Smith', $customer->getLastName());
    }

    public function testSetNameWithOneName()
    {
        $customer = new Customer();
        $customer->setName('Bob');
        $this->assertEquals('Bob', $customer->getFirstName());
        $this->assertEquals('', $customer->getLastName());
    }

    public function testSetNameWithMultipleNames()
    {
        $customer = new Customer();
        $customer->setName('Bob John Smith');
        $this->assertEquals('Bob', $customer->getFirstName());
        $this->assertEquals('John Smith', $customer->getLastName());
    }

    public function testCompany()
    {
        $customer = new Customer();
        $customer->setCompany('FooBar');
        $this->assertEquals('FooBar', $customer->getCompany());
    }

    public function testAddress1()
    {
        $customer = new Customer();
        $customer->setAddress1('31 Spooner St');
        $this->assertEquals('31 Spooner St', $customer->getAddress1());
    }

    public function testAddress2()
    {
        $customer = new Customer();
        $customer->setAddress2('Suburb');
        $this->assertEquals('Suburb', $customer->getAddress2());
    }

    public function testCity()
    {
        $customer = new Customer();
        $customer->setCity('Quahog');
        $this->assertEquals('Quahog', $customer->getCity());
    }

    public function testPostcode()
    {
        $customer = new Customer();
        $customer->setPostcode('12345');
        $this->assertEquals('12345', $customer->getPostcode());
    }

    public function testState()
    {
        $customer = new Customer();
        $customer->setState('RI');
        $this->assertEquals('RI', $customer->getState());
    }

    public function testCountry()
    {
        $customer = new Customer();
        $customer->setCountry('US');
        $this->assertEquals('US', $customer->getCountry());
    }

    public function testPhone()
    {
        $customer = new Customer();
        $customer->setPhone('12345');
        $this->assertEquals('12345', $customer->getPhone());
    }

    public function testPhoneExtension()
    {
        $customer = new Customer();
        $customer->setPhoneExtension('001');
        $this->assertEquals('001', $customer->getPhoneExtension());
    }

    public function testFax()
    {
        $customer = new Customer();
        $customer->setFax('54321');
        $this->assertEquals('54321', $customer->getFax());
    }

    public function testEmail()
    {
        $customer = new Customer();
        $customer->setEmail('adrian@example.com');
        $this->assertEquals('adrian@example.com', $customer->getEmail());
    }

    public function testBirthday()
    {
        $customer = new Customer();
        $customer->setBirthday('01-02-2000');
        $this->assertEquals('2000-02-01', $customer->getBirthday());
        $this->assertEquals('01/02/2000', $customer->getBirthday('d/m/Y'));
    }

    public function testBirthdayEmpty()
    {
        $customer = new Customer();
        $customer->setBirthday('');
        $this->assertNull($customer->getBirthday());
    }

    public function testGender()
    {
        $customer = new Customer();
        $customer->setGender('female');
        $this->assertEquals('female', $customer->getGender());
    }
}
