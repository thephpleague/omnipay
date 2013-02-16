<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\AuthorizeNet;

use Omnipay\Common\AbstractResponse;
use Omnipay\Exception;

/**
 * Authorize.Net Response
 */
class SIMResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return isset($this->data['x_response_code']) && '1' === $this->data['x_response_code'];
    }

    public function getGatewayReference()
    {
        return isset($this->data['x_trans_id']) ? $this->data['x_trans_id'] : null;
    }

    public function getMessage()
    {
        return isset($this->data['x_response_reason_text']) ? $this->data['x_response_reason_text'] : null;
    }
}
