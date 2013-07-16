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

use Omnipay\Common\CreditCard;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Sips\Merchant;
use Omnipay\Sips\Message\Request;

abstract class Response extends AbstractResponse
{
    private $code = -1;
    private $debug;
    private $message;
    private $transactionId;
    private $paymentMeans;
    private $transmissionDate;
    private $paymentTime;
    private $paymentDate;
    private $responseCode;
    private $paymentCertificate;
    private $authorisationId;
    private $cvvFlag;
    private $cvvResponseCode;
    private $bankResponseCode;
    private $complementaryCode;
    private $complementaryInfo;
    private $returnContext;
    private $caddie;
    private $receiptComplement;
    private $language;
    private $customerId;
    private $orderId;
    private $captureDay;
    private $captureMode;
    private $dataPrivate;

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        /** @var Request $request */
        $request = $this->request;
        $request->setAmount($amount);
    }

    /**
     * @param mixed $authorisationId
     */
    public function setAuthorisationId($authorisationId)
    {
        $this->authorisationId = $authorisationId;
    }

    /**
     * @return mixed
     */
    public function getAuthorisationId()
    {
        return $this->authorisationId;
    }

    /**
     * @param mixed $bankResponseCode
     */
    public function setBankResponseCode($bankResponseCode)
    {
        $this->bankResponseCode = $bankResponseCode;
    }

    /**
     * @return mixed
     */
    public function getBankResponseCode()
    {
        return $this->bankResponseCode;
    }

    /**
     * @param mixed $caddie
     */
    public function setCaddie($caddie)
    {
        $this->caddie = $caddie;
    }

    /**
     * @return mixed
     */
    public function getCaddie()
    {
        return $this->caddie;
    }

    /**
     * @param mixed $captureDay
     */
    public function setCaptureDay($captureDay)
    {
        $this->captureDay = $captureDay;
    }

    /**
     * @return mixed
     */
    public function getCaptureDay()
    {
        return $this->captureDay;
    }

    /**
     * @param mixed $captureMode
     */
    public function setCaptureMode($captureMode)
    {
        $this->captureMode = $captureMode;
    }

    /**
     * @return mixed
     */
    public function getCaptureMode()
    {
        return $this->captureMode;
    }

    /**
     * @param mixed $cardNumber
     */
    public function setCardNumber($cardNumber)
    {
        /** @var Request $request */
        $request = $this->request;

        /** @var CreditCard $card */
        $card = $request->getCard();

        $card->setNumber($cardNumber);

    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $complementaryCode
     */
    public function setComplementaryCode($complementaryCode)
    {
        $this->complementaryCode = $complementaryCode;
    }

    /**
     * @return mixed
     */
    public function getComplementaryCode()
    {
        return $this->complementaryCode;
    }

    /**
     * @param mixed $complementaryInfo
     */
    public function setComplementaryInfo($complementaryInfo)
    {
        $this->complementaryInfo = $complementaryInfo;
    }

    /**
     * @return mixed
     */
    public function getComplementaryInfo()
    {
        return $this->complementaryInfo;
    }

    /**
     * @param mixed $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        /** @var Request $request */
        $request = $this->request;
        $request->setCurrency($currencyCode);
    }

    /**
     * @param mixed $customerEmail
     */
    public function setCustomerEmail($customerEmail)
    {
        /** @var Request $request */
        $request = $this->request;

        /** @var CreditCard $card */
        $card = $request->getCard();

        $card->setEmail($customerEmail);
    }

    /**
     * @param mixed $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerIpAddress
     */
    public function setCustomerIpAddress($customerIpAddress)
    {
        /** @var Request $request */
        $request = $this->request;

        $request->setClientIp($customerIpAddress);
    }

    /**
     * @param mixed $cvvFlag
     */
    public function setCvvFlag($cvvFlag)
    {
        $this->cvvFlag = $cvvFlag;
    }

    /**
     * @return mixed
     */
    public function getCvvFlag()
    {
        return $this->cvvFlag;
    }

    /**
     * @param mixed $cvvResponseCode
     */
    public function setCvvResponseCode($cvvResponseCode)
    {
        $this->cvvResponseCode = $cvvResponseCode;
    }

    /**
     * @return mixed
     */
    public function getCvvResponseCode()
    {
        return $this->cvvResponseCode;
    }

    /**
     * @param mixed $dataPrivate
     */
    public function setDataPrivate($dataPrivate)
    {
        $this->dataPrivate = $dataPrivate;
    }

    /**
     * @return mixed
     */
    public function getDataPrivate()
    {
        return $this->dataPrivate;
    }

    /**
     * @param mixed $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return mixed
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $merchantCountry
     */
    public function setMerchantCountry($merchantCountry)
    {
        /** @var Request $request */
        $request = $this->request;

        /** @var Merchant $merchant */
        $merchant = $request->getMerchant();

        $merchant->setCountry($merchantCountry);
    }

    /**
     * @param mixed $merchantId
     */
    public function setMerchantId($merchantId)
    {
        /** @var Request $request */
        $request = $this->request;

        /** @var Merchant $merchant */
        $merchant = $request->getMerchant();

        $merchant->setId($merchantId);
    }

    /**
     * @param mixed $merchantLanguage
     */
    public function setMerchantLanguage($merchantLanguage)
    {
        /** @var Request $request */
        $request = $this->request;

        /** @var Merchant $merchant */
        $merchant = $request->getMerchant();

        $merchant->setLanguage($merchantLanguage);
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $paymentCertificate
     */
    public function setPaymentCertificate($paymentCertificate)
    {
        $this->paymentCertificate = $paymentCertificate;
    }

    /**
     * @return mixed
     */
    public function getPaymentCertificate()
    {
        return $this->paymentCertificate;
    }

    /**
     * @param mixed $paymentDate
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;
    }

    /**
     * @return mixed
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @param mixed $paymentMeans
     */
    public function setPaymentMeans($paymentMeans)
    {
        $this->paymentMeans = $paymentMeans;
    }

    /**
     * @return mixed
     */
    public function getPaymentMeans()
    {
        return $this->paymentMeans;
    }

    /**
     * @param mixed $paymentTime
     */
    public function setPaymentTime($paymentTime)
    {
        $this->paymentTime = $paymentTime;
    }

    /**
     * @return mixed
     */
    public function getPaymentTime()
    {
        return $this->paymentTime;
    }

    /**
     * @param mixed $receiptComplement
     */
    public function setReceiptComplement($receiptComplement)
    {
        $this->receiptComplement = $receiptComplement;
    }

    /**
     * @return mixed
     */
    public function getReceiptComplement()
    {
        return $this->receiptComplement;
    }

    /**
     * @param mixed $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return mixed
     */
    public function getResponseStatus()
    {
        // TODO Translattion
        $statuses = array(
            '00' => 'Autorisation acceptée',
            '02' => 'Demande d’autorisation par téléphone à la banque à cause d’un dépassement de plafond d’autorisation sur la carte',
            '03' => 'Contrat de vente inexistant',
            '05' => 'Autorisation refusée',
            '12' => 'Transaction invalide, vérifier les paramètres transmis',
            '17' => 'Annulation de l\'internaute',
            '30' => 'Erreur de format',
            '34' => 'Suspicion de fraude',
            '75' => 'Nombre de tentatives de saisie du numéro de carte dépassé',
            '90' => 'Service temporairement indisponible'
        );

        if(!isset($this->responseCode)){
            return 'Pas de code réponse';
        }
        else if (isset($statuses[$this->responseCode])) {
            return $statuses[$this->responseCode];
        }
        else {
            return 'Code réponse inconnu : '.$this->responseCode;
        }
    }

    /**
     * @param mixed $returnContext
     */
    public function setReturnContext($returnContext)
    {
        $this->returnContext = $returnContext;
    }

    /**
     * @return mixed
     */
    public function getReturnContext()
    {
        return $this->returnContext;
    }

    /**
     * @param mixed $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param mixed $transmissionDate
     */
    public function setTransmissionDate($transmissionDate)
    {
        $this->transmissionDate = $transmissionDate;
    }

    /**
     * @return mixed
     */
    public function getTransmissionDate()
    {
        return $this->transmissionDate;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return ($this->code == 0);
    }

    /**
     * Is the response a cancellation
     *
     * @return boolean
     */
    public function isCancel()
    {
        return ($this->code == 1);
    }

    public function getTransactionReference()
    {
        /** @var AuthorizeRequest $request */
        $request = $this->request;
        return $request->getTransactionReference();
    }

    public function __construct(Request $request, $data, $class = '\Omnipay\Sips\Message\Response')
    {
        parent::__construct($request, $data);

        $results = explode("!", "$data");
        $resultsCount = count($results);

        $reflection = new \ReflectionClass($class);

        foreach ($this->getResultComponents() as $key => $value) {
            if ($value < $resultsCount) {
                $setter = 'set' . ucfirst($key);
                if ($reflection->hasMethod($setter)) {
                    $this->$setter($results[$value]);
                }
            }
        }
    }

    protected function getResultComponents()
    {
        return array();
    }
}
