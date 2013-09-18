<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\PagSeguro\Message;

use Omnipay\PagSeguro\Message\ValueObject\Item;
use Omnipay\PagSeguro\Message\ValueObject\Credentials;
use Omnipay\PagSeguro\Message\ValueObject\Payment\PaymentRequest;
use Omnipay\PagSeguro\Message\Service\PaymentService;

/**
 * PagSeguro Authorize Request
 */
class AuthorizeRequest extends AbstractRequest
{
    protected $action = 'Authorization';

    public function getData()
    {
        $data = array();

        // on authentication
        $data['credentials'] = new Credentials(
            $this->getEmail(),
            $this->getToken()
        );

        $data['paymentRequest'] = new PaymentRequest(
            array(
                new Item(
                    $this->getTransactionId(),
                    $this->getDescription(),
                    $this->getAmount()
                )
            )
        );

        return $data;
    }
}
