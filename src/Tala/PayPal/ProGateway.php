<?php

/*
 * This file is part of the Tala Payments package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tala\PayPal;

use Tala\Request;

/**
 * PayPal Pro Class
 */
class ProGateway extends AbstractGateway
{
    public function authorize(Request $request, $source)
    {
        $data = $this->buildAuthorize($request, $source, 'Authorization');
        $response = $this->send($data);

        return new Response($response);
    }

    public function purchase(Request $request, $source)
    {
        $data = $this->buildAuthorize($request, $source, 'Sale');
        $response = $this->send($data);

        return new Response($response);
    }

    protected function buildAuthorize($request, $source, $action)
    {
        $request->validateRequired('amount');

        $source->validateRequired(array('number', 'firstName', 'lastName', 'expiryMonth', 'expiryYear', 'cvv'));
        $source->validateNumber;

        $data = $this->buildPaymentRequest($request, 'DoDirectPayment', $action);

        // add credit card details
        $data['CREDITCARDTYPE'] = $source->type;
        $data['ACCT'] = $source->number;
        $data['EXPDATE'] = $source->expiryMonth.$source->expiryYear;
        $data['STARTDATE'] = $source->startMonth.$source->startYear;
        $data['CVV2'] = $source->cvv;
        $data['ISSUENUMBER'] = $source->issue;
        $data['IPADDRESS'] = '';
        $data['FIRSTNAME'] = $source->firstName;
        $data['LASTNAME'] = $source->lastName;
        $data['EMAIL'] = $source->email;
        $data['STREET'] = $source->address1;
        $data['STREET2'] = $source->address2;
        $data['CITY'] = $source->city;
        $data['STATE'] = $source->state;
        $data['ZIP'] = $source->postcode;
        $data['COUNTRYCODE'] = strtoupper($source->country);

        return $data;
    }
}
