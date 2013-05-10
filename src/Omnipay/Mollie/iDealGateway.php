<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Mollie;

use Omnipay\Common\AbstractGateway;
use Omnipay\Mollie\Message\ProAuthorizeRequest;
use Omnipay\Mollie\Message\CaptureRequest;
use Omnipay\Mollie\Message\RefundRequest;

/**
 * Mollie Pro Class
 */
class iDealGateway extends AbstractGateway
{
    /**
     * Name of this gateway: Mollie iDeal.
     *
     * Could have been "iDeal" but it's probably at some point other iDeal gateways will be
     * available, like Sisow. Mollie also offers other payment methods besides iDeal.
     *
     * @return string
     */
    public function getName()
    {
        return 'Mollie iDeal';
    }

    /**
     * Parameters for this gateway.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'partnerid' => '',
            'profile_key' => '',
            'bank_id' => 0,
            'testMode' => false,
        );
    }

    /**
     * Authorizes a payment. In the case of Mollie iDeal, this means it creates a transaction
     * record and returns a Redirect Response.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\iDealAuthorizeRequest', $parameters);
    }

    /**
     * Validates the iDeal payment based on a $_GET['transaction_id'] provided by Mollie.
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function completeAuthorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mollie\Message\iDealCompleteAuthorizeRequest', $parameters);
    }

    /**
     * @see self::authorize
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function purchase(array $parameters = array())
    {
        return $this->authorize($parameters);
    }

    /**
     * @see self::completeAuthorize
     *
     * @param array $parameters
     *
     * @return mixed
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->completeAuthorize($parameters);
    }

    /**
     * Gets an associative array of bank IDs and their name from iDeal.
     * This respects testMode and will return a special test bank only
     * if testMode is enabled. If you implement caching, make sure to
     * keep that in mind.
     *
     * @return array
     */
    public function getBanks()
    {
        $banks = array();
        /** @var \Omnipay\Mollie\Message\iDealBanklistRequest $request */
        $request = $this->createRequest('\Omnipay\Mollie\Message\iDealBanklistRequest', array());
        if ($request) {
            $response = $request->send();
            if ($response) {
                $data = $response->getData();
                foreach ($data->bank as $bank) {
                    $banks[(string)$bank->bank_id] = (string)$bank->bank_name;
                }
            }
        }

        return $banks;
    }
}
