<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Sips\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Sips\Merchant;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

abstract class Request extends AbstractRequest
{
    protected $merchant;
    protected $sipsFolderPath;
    protected $sipsData;

    /**
     * @param mixed $sipsData
     */
    public function setSipsData($sipsData)
    {
        $this->sipsData = $sipsData;
    }

    /**
     * @return mixed
     */
    public function getSipsData()
    {
        return $this->sipsData;
    }

    /**
     * @param string $sipsFolderPath
     */
    public function setSipsFolderPath($sipsFolderPath)
    {
        $this->sipsFolderPath = $sipsFolderPath;
    }

    /**
     * @return string
     */
    public function getSipsFolderPath()
    {
        return $this->sipsFolderPath;
    }

    /**
     * @return string
     */
    public function getSipsPathFilePath()
    {
        return $this->sipsFolderPath . "/param/pathfile";
    }

    /**
     * @return string
     */
    public function getSipsRequestExecPath()
    {
        return $this->sipsFolderPath . '/bin/request';
    }

    /**
     * @return string
     */
    public function getSipsResponseExecPath()
    {
        return $this->sipsFolderPath . '/bin/response';
    }

    /**
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchant->setId($merchantId);
    }

    /**
     * @param mixed $merchantLanguage
     */
    public function setMerchantLanguage($merchantLanguage)
    {
        $this->merchant->setLanguage($merchantLanguage);
    }

    /**
     * @param mixed $merchantLanguage
     */
    public function setMerchantCountry($merchantCountry)
    {
        $this->merchant->setCountry($merchantCountry);
    }

    /**
     * @return mixed
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * Create a new Request
     *
     * @param ClientInterface $httpClient  A Guzzle client to make API calls with
     * @param HttpRequest     $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct($httpClient, $httpRequest);
        $this->merchant = new Merchant();
    }
}
