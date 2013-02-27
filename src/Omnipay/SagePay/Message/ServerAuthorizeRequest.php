<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Server Authorize Request
 */
class ServerAuthorizeRequest extends DirectAuthorizeRequest
{
    public function getData()
    {
        $this->validate('returnUrl');

        $data = $this->getBaseAuthorizeData();
        $data['NotificationURL'] = $this->getReturnUrl();

        return $data;
    }

    public function getService()
    {
        return 'vspserver-register';
    }

    protected function createResponse($data)
    {
        return $this->response = new ServerAuthorizeResponse($this, $data);
    }
}
