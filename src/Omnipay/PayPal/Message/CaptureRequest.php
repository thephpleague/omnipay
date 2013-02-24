<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PayPal\Message;

/**
 * PayPal Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->getBaseData('DoCapture');

        $this->validate(array('gatewayReference', 'amount'));

        $data['AMT'] = $this->getAmountDecimal();
        $data['CURRENCYCODE'] = $this->getCurrency();
        $data['AUTHORIZATIONID'] = $this->getGatewayReference();
        $data['COMPLETETYPE'] = 'Complete';

        return $data;
    }
}
