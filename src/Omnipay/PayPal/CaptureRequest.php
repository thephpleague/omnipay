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

/**
 * PayPal Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('DoCapture');

        $this->validate(array('gatewayReference', 'amount'));

        $data['AMT'] = $request->getAmountDecimal();
        $data['CURRENCYCODE'] = $request->getCurrency();
        $data['AUTHORIZATIONID'] = $request->getGatewayReference();
        $data['COMPLETETYPE'] = 'Complete';

        return $data;
    }
}
