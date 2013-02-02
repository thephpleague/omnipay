<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\Billing\PayPal;

use Tala\RedirectResponse;
use Tala\Request;

/**
 * PayPal Express Class
 */
class ExpressGateway extends AbstractGateway
{
    public function getDefaultSettings()
    {
        $settings = parent::getDefaultSettings();
        $settings['solutionType'] = array('Sole', 'Mark');
        $settings['landingPage'] = array('Billing', 'Login');

        return $settings;
    }

    public function authorize(Request $request, $source)
    {
        $data = $this->buildAuthorize($request, $source);
        $response = $this->send($data);

        return new RedirectResponse(
            $this->getCurrentCheckoutEndpoint().'?'.http_build_query(
                array(
                    'cmd' => '_express-checkout',
                    'useraction' => 'commit',
                    'token' => $response['TOKEN'],
                )
            )
        );
    }

    public function completeAuthorize(Request $request)
    {
        $data = $this->confirmReturn($request, 'Authorization');

        return new Response($data);
    }

    public function purchase(Request $request, $source)
    {
        // authorize first then process as 'Sale' in DoExpressCheckoutPayment
        return $this->authorize($request, $source);
    }

    public function completePurchase(Request $request)
    {
        $data = $this->confirmReturn($request, 'Sale');

        return new Response($data);
    }

    protected function buildAuthorize(Request $request, $source)
    {
        $request->validateRequired(array('returnUrl', 'cancelUrl'));

        $prefix = 'PAYMENTREQUEST_0_';
        $data = $this->buildPaymentRequest($request, 'SetExpressCheckout', 'Authorization', $prefix);

        // pp express specific fields
        $data['SOLUTIONTYPE'] = $this->getSolutionType();
        $data['LANDINGPAGE'] = $this->getLandingPage();
        $data['NOSHIPPING'] = 1;
        $data['ALLOWNOTE'] = 0;
        $data['RETURNURL'] = $request->returnUrl;
        $data['CANCELURL'] = $request->cancelUrl;

        $data[$prefix.'SHIPTONAME'] = $source->name;
        $data[$prefix.'SHIPTOSTREET'] = $source->address1;
        $data[$prefix.'SHIPTOSTREET2'] = $source->address2;
        $data[$prefix.'SHIPTOCITY'] = $source->city;
        $data[$prefix.'SHIPTOSTATE'] = $source->state;
        $data[$prefix.'SHIPTOCOUNTRYCODE'] = $source->country;
        $data[$prefix.'SHIPTOZIP'] = $source->postcode;
        $data[$prefix.'SHIPTOPHONENUM'] = $source->phone;
        $data['EMAIL'] = $source->email;

        return $data;
    }

    protected function confirmReturn($request, $action)
    {
        $prefix = 'PAYMENTREQUEST_0_';
        $data = $this->buildPaymentRequest($request, 'DoExpressCheckoutPayment', $action, $prefix);

        $data['TOKEN'] = isset($_POST['token']) ? $_POST['token'] : '';
        $data['PAYERID'] = isset($_POST['PayerID']) ? $_POST['PayerID'] : '';

        return $this->send($data);
    }
}
