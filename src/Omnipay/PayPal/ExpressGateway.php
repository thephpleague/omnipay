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
        $request = new ExpressAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this);
    }

    public function completeAuthorize($options = null, $action = 'Authorization')
    {
        $request = new ExpressCompleteAuthorizeRequest(array_merge($this->toArray(), (array) $options));

        return $request->setGateway($this)->setPaymentAction($action);
    }

    public function purchase($options = null)
    {
        return $this->authorize($options);
    }

    public function completePurchase($options = null)
    {
        return $this->completeAuthorize($options, 'Sale');
    }
}
