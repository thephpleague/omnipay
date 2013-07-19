<?php

namespace Omnipay\Sips\Message;

use Guzzle\Http\ClientInterface;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Sips\Merchant;

use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class Request
 * @package Omnipay\Sips\Message
 */
abstract class SipsBinaryCall extends AbstractRequest
{
    /**
     * The Merchant
     *
     * @var Merchant
     */
    protected $merchant;

    /**
     * The path of the folder containing
     * the Sips files (binaries, params...)
     *
     * @var string
     */
    protected $sipsFolderPath;

    /**
     * The data to add to the request
     *
     * @var
     */
    protected $sipsData;

    /**
     * Sets the data to add to the request
     *
     * @param mixed $sipsData
     */
    public function setSipsData($sipsData)
    {
        $this->sipsData = $sipsData;
    }

    /**
     * Gets the data to add to the request
     *
     * @return mixed
     */
    public function getSipsData()
    {
        return $this->sipsData;
    }

    /**
     * Sets The path of the folder containing
     * the Sips files (binaries, params...)
     *
     * @param string $sipsFolderPath
     */
    public function setSipsFolderPath($sipsFolderPath)
    {
        $this->sipsFolderPath = $sipsFolderPath;
    }

    /**
     * Gets the path of the folder containing
     * the Sips files (binaries, params...)
     *
     * @return string
     */
    public function getSipsFolderPath()
    {
        return $this->sipsFolderPath;
    }

    /**
     * Gets the path to the Sips PathFile
     *
     * @return string
     */
    public function getSipsPathFilePath()
    {
        return $this->sipsFolderPath . "/param/pathfile";
    }

    /**
     * Gets the path to the Sips request binary
     *
     * @return string
     */
    public function getSipsRequestExecPath()
    {
        return $this->sipsFolderPath . '/bin/request';
    }

    /**
     * Gets the path to the Sips response binary
     *
     * @return string
     */
    public function getSipsResponseExecPath()
    {
        return $this->sipsFolderPath . '/bin/response';
    }

    /**
     * Sets the merchant id
     *
     * @param string $merchantId
     */
    public function setMerchantId($merchantId)
    {
        $this->merchant->setId($merchantId);
    }

    /**
     * Sets the merchant language
     *
     * @param mixed $merchantLanguage
     */
    public function setMerchantLanguage($merchantLanguage)
    {
        $this->merchant->setLanguage($merchantLanguage);
    }

    /**
     * Sets the merchant country
     *
     * @param mixed $merchantLanguage
     */
    public function setMerchantCountry($merchantCountry)
    {
        $this->merchant->setCountry($merchantCountry);
    }

    /**
     * Gets the merchant information
     *
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
     * @param HttpRequest $httpRequest A Symfony HTTP request object
     */
    public function __construct(ClientInterface $httpClient, HttpRequest $httpRequest)
    {
        parent::__construct($httpClient, $httpRequest);
        $this->merchant = new Merchant();
    }
}
