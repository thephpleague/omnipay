<?php

namespace Omnipay\Sips\Message;

use Omnipay\Sips\Message\RequestCall;

/**
 * Sips Authorize Response
 */
class ResponseResult extends SipsBinaryResult
{
    protected function getResultComponents()
    {
        return array(
            'code',
            'error',
            'merchantId',
            'merchantCountry',
            'amount',
            'transactionId',
            'paymentMeans',
            'transmissionDate',
            'paymentTime',
            'paymentDate',
            'responseCode',
            'paymentCertificate',
            'authorisationId',
            'currencyCode',
            'cardNumber',
            'cvvFlag',
            'cvvResponseCode',
            'bankResponseCode',
            'complementaryCode',
            'complementaryInfo',
            'returnContext',
            'caddie',
            'receiptComplement',
            'merchantLanguage',
            'language',
            'customerId',
            'orderId',
            'customerEmail',
            'customerIpAddress',
            'captureDay',
            'captureMode',
            'data'
        );
    }

    /**
     * A list of payment means
     *
     * @var string
     */
    private $paymentMeans;

    /**
     * The payment time
     *
     * @var string
     */
    private $paymentTime;

    /**
     * The payment date
     *
     * @var string
     */
    private $paymentDate;

    /**
     * The response code
     *
     * @var string
     */
    private $responseCode;

    /**
     * The payment certificate
     *
     * @var string
     */
    private $paymentCertificate;

    /**
     * The authorisation id
     * (only if the payment if authorised)
     *
     * @var string
     */
    private $authorisationId;

    /**
     * The Cvv flag
     *
     * @var string
     */
    private $cvvFlag;

    /**
     * The Cvv response code
     *
     * @var string
     */
    private $cvvResponseCode;

    /**
     * The bank response code
     *
     * @var string
     */
    private $bankResponseCode;

    /**
     * The complementary code
     *
     * @var string
     */
    private $complementaryCode;

    /**
     * The complementary info
     *
     * @var string
     */
    private $complementaryInfo;

    /**
     * The number of day before transaction
     *
     * @var string
     */
    private $captureDay;

    /**
     * The capture mode
     *
     * @var string
     */
    private $captureMode;

    /**
     * Gets the authorization id
     * (defined only if the payment is authorized)
     * @return mixed
     */
    public function getAuthorisationId()
    {
        return $this->authorisationId;
    }

    /**
     * Sets the authorization id
     * (only if the payment is authorized)
     *
     * @param mixed $authorisationId
     */
    public function setAuthorisationId($authorisationId)
    {
        $this->authorisationId = $authorisationId;
    }

    /**
     * Gets the bank response code
     *
     * @return mixed
     */
    public function getBankResponseCode()
    {
        return $this->bankResponseCode;
    }

    /**
     * Sets the Bank response code
     *
     * @param $bankResponseCode
     */
    public function setBankResponseCode($bankResponseCode)
    {
        $this->bankResponseCode = $bankResponseCode;
    }

    /**
     * Sets the complementary code
     *
     * @param mixed $complementaryCode
     */
    public function setComplementaryCode($complementaryCode)
    {
        $this->complementaryCode = $complementaryCode;
    }

    /**
     * Gets the complementary code
     *
     * @return mixed
     */
    public function getComplementaryCode()
    {
        return $this->complementaryCode;
    }

    /**
     * Sets the complementary info
     *
     * @param mixed $complementaryInfo
     */
    public function setComplementaryInfo($complementaryInfo)
    {
        $this->complementaryInfo = $complementaryInfo;
    }

    /**
     * Gets the complementary info
     *
     * @return mixed
     */
    public function getComplementaryInfo()
    {
        return $this->complementaryInfo;
    }

    /**
     * Sets the CVV flags
     * (only if their is a control number
     * and an authorisation process)
     *
     * @param mixed $cvvFlag
     */
    public function setCvvFlag($cvvFlag)
    {
        $this->cvvFlag = $cvvFlag;
    }

    /**
     * Gets the CVV flags
     * (defined only if their is a control number
     * and an authorisation process)
     *
     * @return mixed
     */
    public function getCvvFlag()
    {
        return $this->cvvFlag;
    }

    /**
     *  Gets the CVV response code
     * (defined only if their is a control number
     * and an authorisation process)
     *
     * @param mixed $cvvResponseCode
     */
    public function setCvvResponseCode($cvvResponseCode)
    {
        $this->cvvResponseCode = $cvvResponseCode;
    }

    /**
     * Sets the CVV response code
     * (defined only if their is a control number
     * and an authorisation process)
     *
     * @return mixed
     */
    public function getCvvResponseCode()
    {
        return $this->cvvResponseCode;
    }

    /**
     * Sets the payment certificate
     * (only if transaction is authorised)
     *
     * @param mixed $paymentCertificate
     */
    public function setPaymentCertificate($paymentCertificate)
    {
        $this->paymentCertificate = $paymentCertificate;
    }

    /**
     * Gets the payment certificate
     * (defined only if transaction is authorised)
     *
     * @return mixed
     */
    public function getPaymentCertificate()
    {
        return $this->paymentCertificate;
    }

    /**
     * Sets the payment date
     *
     * @param mixed $paymentDate
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;
    }

    /**
     * Gets the payment date
     *
     * @return mixed
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Sets the payment means
     *
     * @param mixed $paymentMeans
     */
    public function setPaymentMeans($paymentMeans)
    {
        $this->paymentMeans = $paymentMeans;
    }

    /**
     * Gets the payment mean
     *
     * @return mixed
     */
    public function getPaymentMeans()
    {
        return $this->paymentMeans;
    }

    /**
     * Sets the payment time
     * (only if payment succeed)
     *
     * @param mixed $paymentTime
     */
    public function setPaymentTime($paymentTime)
    {
        $this->paymentTime = $paymentTime;
    }

    /**
     * Gets the payment time
     * (defined only if payment succeed)
     * @return mixed
     */
    public function getPaymentTime()
    {
        return $this->paymentTime;
    }

    /**
     * Sets the response code
     *
     * @param mixed $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     * Sets the number of days before capture
     *
     * @param string $captureDay
     */
    public function setCaptureDay($captureDay)
    {
        $this->captureDay = $captureDay;
    }

    /**
     * Gets the number of days before capture
     *
     * @return string
     */
    public function getCaptureDay()
    {
        return $this->captureDay;
    }

    /**
     * Sets the capture mode
     *
     * @param string $captureMode
     */
    public function setCaptureMode($captureMode)
    {
        $this->captureMode = $captureMode;
    }

    /**
     * Gets the capture mode
     *
     * @return string
     */
    public function getCaptureMode()
    {
        return $this->captureMode;
    }

    /**
     * Gets a response status from the response code
     *
     * @return string
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
}
