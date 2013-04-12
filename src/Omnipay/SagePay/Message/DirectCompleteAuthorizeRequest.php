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
 * Sage Pay Direct Complete Authorize Request
 */
class DirectCompleteAuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array(
            'MD' => $this->httpRequest->request->get('MD'),
            'PARes' => $this->httpRequest->request->get('PaRes'), // inconsistent caps are intentional
        );

        if (empty($data['MD']) || empty($data['PARes'])) {
            throw new InvalidResponseException;
        }

        return $data;
    }

    public function getService()
    {
        return 'direct3dcallback';
    }
}
