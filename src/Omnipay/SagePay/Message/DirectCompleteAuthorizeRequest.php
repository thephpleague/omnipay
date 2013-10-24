<?php

namespace Omnipay\SagePay\Message;

use Omnipay\Common\Exception\InvalidResponseException;

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
