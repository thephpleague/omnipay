<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Icepay\Message;

use SoapClient;
use stdClass;

abstract class AbstractBasicRequest extends AbstractRequest
{
    protected $endpoint = 'https://connect.icepay.com/webservice/icepay.svc?wsdl';

    public function getTimestamp()
    {
        return $this->getParameter('timestamp');
    }

    public function setTimestamp($value)
    {
        return $this->setParameter('timestamp', $value);
    }

    /**
     * @return SoapClient
     */
    protected function getSoapClient()
    {
        return new SoapClient($this->getEndpoint(), array(
            'location' => $this->getEndpoint(),
            'cache_wsdl' => WSDL_CACHE_NONE,
        ));
    }

    abstract protected function generateSignature(stdClass $data);
}
