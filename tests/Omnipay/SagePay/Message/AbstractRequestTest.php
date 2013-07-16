<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Dave Amphlett <dave@davelopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay\Message;

use Omnipay\TestCase;

class AbstractRequestTest extends TestCase
{
    public function testConvertCardTypeOmniPayToSagePay()
    {
        $targets = array(
            \Omnipay\Common\CreditCard::BRAND_VISA => 'VISA',
            \Omnipay\Common\CreditCard::BRAND_MASTERCARD => 'MC',
            \Omnipay\Common\CreditCard::BRAND_DISCOVER => null,
            \Omnipay\Common\CreditCard::BRAND_AMEX => 'AMEX',
            \Omnipay\Common\CreditCard::BRAND_DINERS_CLUB => 'DC',
            \Omnipay\Common\CreditCard::BRAND_JCB => 'JCB',
            \Omnipay\Common\CreditCard::BRAND_SWITCH => null,
            \Omnipay\Common\CreditCard::BRAND_SOLO => null,
            \Omnipay\Common\CreditCard::BRAND_DANKORT => null,
            \Omnipay\Common\CreditCard::BRAND_MAESTRO => 'MAESTRO',
            \Omnipay\Common\CreditCard::BRAND_FORBRUGSFORENINGEN => null,
            \Omnipay\Common\CreditCard::BRAND_LASER => 'LASER',
            \Omnipay\Common\CreditCard::BRAND_VISA_ELECTRON => 'UKE',
            \Omnipay\Common\CreditCard::BRAND_VISA_DEBIT => 'DELTA',
            'sagepay_PAYPAL' => 'PAYPAL',
        );
        foreach ($targets as $omniPayCardBrand => $expectedSagePayCardType) {
            $actualSagePayCardType = AbstractRequest::convertCardTypeOmniPayToSagePay($omniPayCardBrand);
            $this->assertSame($expectedSagePayCardType, $actualSagePayCardType, "Comparing:".$omniPayCardBrand);
        }
    }

    public function testConvertCardTypeSagePayToOmniPay()
    {
        $targets = array(
            'VISA'      => \Omnipay\Common\CreditCard::BRAND_VISA,
            'MC'        => \Omnipay\Common\CreditCard::BRAND_MASTERCARD,
            'AMEX'      => \Omnipay\Common\CreditCard::BRAND_AMEX,
            'DC'        => \Omnipay\Common\CreditCard::BRAND_DINERS_CLUB,
            'JCB'       => \Omnipay\Common\CreditCard::BRAND_JCB,
            'MAESTRO'   => \Omnipay\Common\CreditCard::BRAND_MAESTRO,
            'LASER'     => \Omnipay\Common\CreditCard::BRAND_LASER,
            'UKE'       => \Omnipay\Common\CreditCard::BRAND_VISA_ELECTRON,
            'DELTA'     => \Omnipay\Common\CreditCard::BRAND_VISA_DEBIT,
            'PAYPAL'    => 'sagepay_PAYPAL',
        );
        foreach ($targets as $sagePayCardType => $expectedOmniPayCardBrand) {
            $actualOmniPayCardBrand = AbstractRequest::convertCardTypeSagePayToOmniPay($sagePayCardType);
            $this->assertSame($expectedOmniPayCardBrand, $actualOmniPayCardBrand, "Comparing:".$sagePayCardType);
        }
    }

}
