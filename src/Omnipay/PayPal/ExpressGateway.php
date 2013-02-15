<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal;

use Omnipay\Common\RedirectResponse;
use Omnipay\Common\Request;

/**
 * PayPal Express Class
 */
class ExpressGateway extends ProGateway
{
    protected $solutionType;
    protected $landingPage;

    public function getName()
    {
        return 'PayPal Express';
    }

    public function defineSettings()
    {
        $settings = parent::defineSettings();
        $settings['solutionType'] = array('Sole', 'Mark');
        $settings['landingPage'] = array('Billing', 'Login');

        return $settings;
    }

    public function getSolutionType()
    {
        return $this->solutionType;
    }

    public function setSolutionType($value)
    {
        $this->solutionType = $value;
    }

    public function getLandingPage()
    {
        return $this->landingPage;
    }

    public function setLandingPage($value)
    {
        $this->landingPage = $value;
    }

    public function authorize($options)
    {
        $data = $this->buildExpressAuthorize($options);
        $response = $this->send($data);

        if (!$response->isSuccessful()) {
            return $response;
        }

        return new RedirectResponse(
            $this->getCurrentCheckoutEndpoint().'?'.http_build_query(
                array(
                    'cmd' => '_express-checkout',
                    'useraction' => 'commit',
                    'token' => $response->getExpressRedirectToken(),
                )
            )
        );
    }

    public function completeAuthorize($options)
    {
        $data = $this->buildCompleteAuthorizeOrPurchase($options, 'Authorization');

        return $this->send($data);
    }

    public function purchase($options)
    {
        // authorize first then process as 'Sale' in DoExpressCheckoutPayment
        return $this->authorize($options);
    }

    public function completePurchase($options)
    {
        $data = $this->buildCompleteAuthorizeOrPurchase($options, 'Sale');

        return $this->send($data);
    }

    protected function buildExpressAuthorize($options)
    {
        $request = new Request($options);
        $request->validate(array('returnUrl', 'cancelUrl'));

        $prefix = 'PAYMENTREQUEST_0_';
        $data = $this->buildPaymentRequest($request, 'SetExpressCheckout', 'Authorization', $prefix);

        // pp express specific fields
        $data['SOLUTIONTYPE'] = $this->getSolutionType();
        $data['LANDINGPAGE'] = $this->getLandingPage();
        $data['NOSHIPPING'] = 1;
        $data['ALLOWNOTE'] = 0;
        $data['RETURNURL'] = $request->getReturnUrl();
        $data['CANCELURL'] = $request->getCancelUrl();

        $source = $request->getCard();
        if ($source) {
            $data[$prefix.'SHIPTONAME'] = $source->getName();
            $data[$prefix.'SHIPTOSTREET'] = $source->getAddress1();
            $data[$prefix.'SHIPTOSTREET2'] = $source->getAddress2();
            $data[$prefix.'SHIPTOCITY'] = $source->getCity();
            $data[$prefix.'SHIPTOSTATE'] = $source->getState();
            $data[$prefix.'SHIPTOCOUNTRYCODE'] = $source->getCountry();
            $data[$prefix.'SHIPTOZIP'] = $source->getPostcode();
            $data[$prefix.'SHIPTOPHONENUM'] = $source->getPhone();
            $data['EMAIL'] = $source->getEmail();
        }

        return $data;
    }

    protected function buildCompleteAuthorizeOrPurchase($options, $action)
    {
        $prefix = 'PAYMENTREQUEST_0_';
        $request = new Request($options);
        $data = $this->buildPaymentRequest($request, 'DoExpressCheckoutPayment', $action, $prefix);

        $data['TOKEN'] = isset($_POST['token']) ? $_POST['token'] : '';
        $data['PAYERID'] = isset($_POST['PayerID']) ? $_POST['PayerID'] : '';

        return $data;
    }
}
