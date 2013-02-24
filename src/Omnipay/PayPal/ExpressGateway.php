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

use Omnipay\PayPal\Message\ExpressAuthorizeRequest;
use Omnipay\PayPal\Message\ExpressCompleteAuthorizeRequest;
use Omnipay\PayPal\Message\ExpressCompletePurchaseRequest;

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

        return $this;
    }

    public function getLandingPage()
    {
        return $this->landingPage;
    }

    public function setLandingPage($value)
    {
        $this->landingPage = $value;

        return $this;
    }

    public function authorize($options = null)
    {
        $request = new ExpressAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function completeAuthorize($options = null)
    {
        $request = new ExpressCompleteAuthorizeRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }

    public function purchase($options = null)
    {
        return $this->authorize($options);
    }

    public function completePurchase($options = null)
    {
        $request = new ExpressCompletePurchaseRequest($this->httpClient, $this->httpRequest);

        return $request->initialize(array_merge($this->toArray(), (array) $options));
    }
}
