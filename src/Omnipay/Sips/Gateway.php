<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Sips;

use Omnipay\Common\AbstractGateway;
use Omnipay\Sips\Message\AuthorizeRequest;
use Omnipay\Sips\Message\ReturnRequest;

/**
 * Sips Gateway
 */
class Gateway extends AbstractGateway
{

    /**
     * @return string
     */
    public function getName()
    {
        return 'Sips';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'merchantId' => '',
            'sipsFolderPath' => ''
        );
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @return mixed
     */
    public function getSipsFolderPath()
    {
        return $this->getParameter('sipsFolderPath');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @param $value
     * @return $this
     */
    public function setSipsFolderPath($value)
    {
        return $this->setParameter('sipsFolderPath', $value);
    }

    /**
     * Creates a request to the gateway
     *
     * @param array $parameters
     * @return AuthorizeRequest
     */
    public function purchase(array $parameters = array())
    {
        $parameters['merchandId'] = $this->getMerchantId();
        $parameters['sipsFolderPath'] = $this->getSipsFolderPath();

        return $this->createRequest('\Omnipay\Sips\Message\AuthorizeRequest', $parameters);
    }

    /**
     * Handles a response from the gateway
     *
     * @param array $parameters
     * @return ReturnRequest
     */
    public function returnPurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Sips\Message\ReturnRequest', $parameters);
    }
}
