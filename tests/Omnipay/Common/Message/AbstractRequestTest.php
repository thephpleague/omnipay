<?php

namespace Omnipay\Common\Message;

use Mockery as m;
use Omnipay\Common\CreditCard;
use Omnipay\Common\ItemBag;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = m::mock('\Omnipay\Common\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    /**
     * Allow changing a protected property using reflections.
     *
     * @param $property
     * @param bool|true $value
     */
    private function changeProtectedProperty($property, $value = true)
    {
        $reflection = new \ReflectionClass($this->request);

        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($this->request, $value);
        $reflection_property->setAccessible(false);
    }

    public function testConstruct()
    {
        $this->request = new AbstractRequestTest_MockAbstractRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->assertSame(array(), $this->request->getParameters());
    }

    public function testInitializeWithParams()
    {
        $this->assertSame($this->request, $this->request->initialize(array('amount' => '1.23')));
        $this->assertSame('1.23', $this->request->getAmount());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage Request cannot be modified after it has been sent!
     */
    public function testInitializeAfterRequestSent()
    {
        $this->request = new AbstractRequestTest_MockAbstractRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->send();

        $this->request->initialize();
    }

    public function testCard()
    {
        // no type checking on card parameter
        $card = new CreditCard;
        $this->assertSame($this->request, $this->request->setCard($card));
        $this->assertSame($card, $this->request->getCard());
    }

    public function testSetCardWithArray()
    {
        // passing array should create CreditCard object
        $this->assertSame($this->request, $this->request->setCard(array('number' => '1234')));

        $card = $this->request->getCard();
        $this->assertInstanceOf('\Omnipay\Common\CreditCard', $card);
        $this->assertSame('1234', $card->getNumber());
    }

    public function testToken()
    {
        $this->assertSame($this->request, $this->request->setToken('12345'));
        $this->assertSame('12345', $this->request->getToken());
    }

    public function testCardReference()
    {
        $this->assertSame($this->request, $this->request->setCardReference('12345'));
        $this->assertSame('12345', $this->request->getCardReference());
    }

    public function testAmount()
    {
        $this->assertSame($this->request, $this->request->setAmount('2.00'));
        $this->assertSame('2.00', $this->request->getAmount());
    }

    public function testAmountWithFloat()
    {
        $this->assertSame($this->request, $this->request->setAmount(2.0));
        $this->assertSame('2.00', $this->request->getAmount());
    }

    public function testAmountWithEmpty()
    {
        $this->assertSame($this->request, $this->request->setAmount(null));
        $this->assertSame(null, $this->request->getAmount());
    }

    public function testAmountZeroFloat()
    {
        $this->assertSame($this->request, $this->request->setAmount(0.0));
        $this->assertSame('0.00', $this->request->getAmount());
    }

    public function testAmountZeroString()
    {
        $this->assertSame($this->request, $this->request->setAmount('0.000000'));
        $this->assertSame('0.00', $this->request->getAmount());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     * @expectedExceptionMessage A zero amount is not allowed.
     */
    public function testAmountZeroNotAllowed()
    {
        $this->changeProtectedProperty('zeroAmountAllowed', false);
        $this->request->setAmount('0.00');
        $this->request->getAmount();
    }

    // See https://github.com/thephpleague/omnipay-common/issues/69
    public function testAmountPrecision()
    {
        // The default precision for PHP is 6 decimal places.
        ini_set('precision', 6);
        $this->assertSame($this->request, $this->request->setAmount('67.10'));
        $this->assertSame('67.10', $this->request->getAmount());

        // At 17 decimal places, 67.10 will echo as 67.09999...
        // This is *why* PHP sets the default precision at 6.
        ini_set('precision', 17);
        $this->assertSame('67.10', $this->request->getAmount());

        $this->assertSame($this->request, $this->request->setAmount('67.01'));
        $this->assertSame('67.01', $this->request->getAmount());

        $this->assertSame($this->request, $this->request->setAmount('0.10'));
        $this->assertSame('0.10', $this->request->getAmount());

        $this->assertSame($this->request, $this->request->setAmount('0.01'));
        $this->assertSame('0.01', $this->request->getAmount());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     *
     * We still want to catch obvious fractions of the minor units that are
     * not precision errors at a much lower level.
     */
    public function testAmountPrecisionTooHigh()
    {
        $this->assertSame($this->request, $this->request->setAmount('123.005'));
        $this->assertSame('123.005', $this->request->getAmount());
    }

    public function testGetAmountNoDecimals()
    {
        $this->assertSame($this->request, $this->request->setCurrency('JPY'));
        $this->assertSame($this->request, $this->request->setAmount('1366'));
        $this->assertSame('1366', $this->request->getAmount());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testGetAmountNoDecimalsRounding()
    {
        // There will not be any rounding; the amount is sent as requested or not at all.
        $this->assertSame($this->request, $this->request->setAmount('136.5'));
        $this->assertSame($this->request, $this->request->setCurrency('JPY'));
        $this->request->getAmount();
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testAmountWithIntThrowsException()
    {
        // ambiguous value, avoid errors upgrading from v0.9
        $this->assertSame($this->request, $this->request->setAmount(10));
        $this->request->getAmount();
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testAmountWithIntStringThrowsException()
    {
        // ambiguous value, avoid errors upgrading from v0.9
        // Some currencies only take integers, so an integer (in a string) should be valid.
        $this->assertSame($this->request, $this->request->setAmount('10'));
        $this->request->getAmount();
    }

    public function testGetAmountInteger()
    {
        $this->assertSame($this->request, $this->request->setAmount('13.66'));
        $this->assertSame(1366, $this->request->getAmountInteger());
    }

    public function testGetAmountIntegerNoDecimals()
    {
        $this->assertSame($this->request, $this->request->setCurrency('JPY'));
        $this->assertSame($this->request, $this->request->setAmount('1366'));
        $this->assertSame(1366, $this->request->getAmountInteger());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testAmountThousandsSepThrowsException()
    {
        $this->assertSame($this->request, $this->request->setAmount('1,234.00'));
        $this->request->getAmount();
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testAmountInvalidFormatThrowsException()
    {
        $this->assertSame($this->request, $this->request->setAmount('1.234.00'));
        $this->request->getAmount();
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testAmountInvalidTypeThrowsException()
    {
        $this->assertSame($this->request, $this->request->setAmount(true));
        $this->request->getAmount();
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testAmountNegativeStringThrowsException()
    {
        $this->assertSame($this->request, $this->request->setAmount('-123.00'));
        $this->request->getAmount();
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidRequestException
     */
    public function testAmountNegativeFloatThrowsException()
    {
        $this->assertSame($this->request, $this->request->setAmount(-123.00));
        $this->request->getAmount();
    }

    public function testCurrency()
    {
        $this->assertSame($this->request, $this->request->setCurrency('USD'));
        $this->assertSame('USD', $this->request->getCurrency());
    }

    public function testCurrencyLowercase()
    {
        $this->assertSame($this->request, $this->request->setCurrency('usd'));
        $this->assertSame('USD', $this->request->getCurrency());
    }

    public function testCurrencyNumeric()
    {
        $this->assertSame($this->request, $this->request->setCurrency('USD'));
        $this->assertSame('840', $this->request->getCurrencyNumeric());
    }

    public function testCurrencyDecimals()
    {
        $this->assertSame($this->request, $this->request->setCurrency('JPY'));
        $this->assertSame(0, $this->request->getCurrencyDecimalPlaces());
    }

    public function testFormatCurrency()
    {
        $this->assertSame('1234.00', $this->request->formatCurrency(1234));
    }

    public function testFormatCurrencyNoDecimals()
    {
        $this->request->setCurrency('JPY');
        $this->assertSame('1234', $this->request->formatCurrency(1234));
    }

    public function testDescription()
    {
        $this->assertSame($this->request, $this->request->setDescription('Cool product'));
        $this->assertSame('Cool product', $this->request->getDescription());
    }

    public function testTransactionId()
    {
        $this->assertSame($this->request, $this->request->setTransactionId(87));
        $this->assertSame(87, $this->request->getTransactionId());
    }

    public function testTransactionReference()
    {
        $this->assertSame($this->request, $this->request->setTransactionReference('xyz'));
        $this->assertSame('xyz', $this->request->getTransactionReference());
    }

    public function testItemsArray()
    {
        $this->assertSame($this->request, $this->request->setItems(array(
            array('name' => 'Floppy Disk'),
            array('name' => 'CD-ROM'),
        )));

        $itemBag = $this->request->getItems();
        $this->assertInstanceOf('\Omnipay\Common\ItemBag', $itemBag);

        $items = $itemBag->all();
        $this->assertSame('Floppy Disk', $items[0]->getName());
        $this->assertSame('CD-ROM', $items[1]->getName());
    }

    public function testItemsBag()
    {
        $itemBag = new ItemBag;
        $itemBag->add(array('name' => 'Floppy Disk'));

        $this->assertSame($this->request, $this->request->setItems($itemBag));
        $this->assertSame($itemBag, $this->request->getItems());
    }

    public function testClientIp()
    {
        $this->assertSame($this->request, $this->request->setClientIp('127.0.0.1'));
        $this->assertSame('127.0.0.1', $this->request->getClientIp());
    }

    public function testReturnUrl()
    {
        $this->assertSame($this->request, $this->request->setReturnUrl('https://www.example.com/return'));
        $this->assertSame('https://www.example.com/return', $this->request->getReturnUrl());
    }

    public function testCancelUrl()
    {
        $this->assertSame($this->request, $this->request->setCancelUrl('https://www.example.com/cancel'));
        $this->assertSame('https://www.example.com/cancel', $this->request->getCancelUrl());
    }

    public function testNotifyUrl()
    {
        $this->assertSame($this->request, $this->request->setNotifyUrl('https://www.example.com/notify'));
        $this->assertSame('https://www.example.com/notify', $this->request->getNotifyUrl());
    }

    public function testIssuer()
    {
        $this->assertSame($this->request, $this->request->setIssuer('some-bank'));
        $this->assertSame('some-bank', $this->request->getIssuer());
    }

    public function testPaymentMethod()
    {
        $this->assertSame($this->request, $this->request->setPaymentMethod('ideal'));
        $this->assertSame('ideal', $this->request->getPaymentMethod());
    }

    public function testInitializedParametersAreSet()
    {
        $params = array('testMode' => 'success');

        $this->request->initialize($params);

        $this->assertSame($this->request->getTestMode(), 'success');
    }

    public function testGetParameters()
    {
        $this->request->setTestMode(true);
        $this->request->setToken('asdf');

        $expected = array(
            'testMode' => true,
            'token' => 'asdf',
        );
        $this->assertEquals($expected, $this->request->getParameters());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage Request cannot be modified after it has been sent!
     */
    public function testSetParameterAfterRequestSent()
    {
        $this->request = new AbstractRequestTest_MockAbstractRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->send();

        $this->request->setCurrency('PHP');
    }

    public function testCanValidateExistingParameters()
    {
        $this->request->setTestMode(true);
        $this->request->setToken('asdf');

        $this->assertNull($this->request->validate('testMode', 'token'));
    }

    /**
     * @expectedException \Omnipay\Common\Exception\InvalidRequestException
     */
    public function testInvalidParametersThrowsException()
    {
        $this->request->setTestMode(true);

        $this->request->validate('testMode', 'token');
    }

    public function testNoCurrencyReturnedIfCurrencyNotSet()
    {
        $this->assertNull($this->request->getCurrencyNumeric());
    }

    public function testSend()
    {
        $response = m::mock('\Omnipay\Common\Message\ResponseInterface');
        $data = array('request data');

        $this->request->shouldReceive('getData')->once()->andReturn($data);
        $this->request->shouldReceive('sendData')->once()->with($data)->andReturn($response);

        $this->assertSame($response, $this->request->send());
    }

    /**
     * @expectedException \Omnipay\Common\Exception\RuntimeException
     * @expectedExceptionMessage You must call send() before accessing the Response!
     */
    public function testGetResponseBeforeRequestSent()
    {
        $this->request = new AbstractRequestTest_MockAbstractRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->getResponse();
    }

    public function testGetResponseAfterRequestSent()
    {
        $this->request = new AbstractRequestTest_MockAbstractRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->send();

        $response = $this->request->getResponse();
        $this->assertInstanceOf('\Omnipay\Common\Message\ResponseInterface', $response);
    }
}

class AbstractRequestTest_MockAbstractRequest extends AbstractRequest
{
    public function getData() {}

    public function sendData($data)
    {
        $this->response = m::mock('\Omnipay\Common\Message\AbstractResponse');
    }
}
