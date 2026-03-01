<?php

/**
 * PayTheFly Abstract Request.
 *
 * Base class for all PayTheFly requests. Handles EIP-712
 * typed structured data signing for payment authentication.
 */

namespace Omnipay\PayTheFly\Message;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * PayTheFly Abstract Request.
 *
 * EIP-712 Domain:
 *   name: 'PayTheFlyPro'
 *   version: '1'
 *
 * PaymentRequest struct:
 *   projectId (string)
 *   token (address)
 *   amount (uint256)
 *   serialNo (string)
 *   deadline (uint256)
 *
 * IMPORTANT: PayTheFly uses Keccak-256 (NOT SHA3-256).
 * This implementation uses PHP's native keccak256 when available,
 * or throws an exception if no Keccak library is found.
 * NEVER fall back to SHA3-256 as it produces different hashes.
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * PayTheFly payment page base URL.
     *
     * @var string
     */
    protected $endpoint = 'https://pro.paythefly.com/pay';

    /**
     * Get project ID.
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->getParameter('projectId');
    }

    /**
     * Set project ID.
     *
     * @param string $value
     * @return $this
     */
    public function setProjectId($value)
    {
        return $this->setParameter('projectId', $value);
    }

    /**
     * Get project key for webhook HMAC verification.
     *
     * @return string
     */
    public function getProjectKey()
    {
        return $this->getParameter('projectKey');
    }

    /**
     * Set project key.
     *
     * @param string $value
     * @return $this
     */
    public function setProjectKey($value)
    {
        return $this->setParameter('projectKey', $value);
    }

    /**
     * Get private key for EIP-712 signing.
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->getParameter('privateKey');
    }

    /**
     * Set private key.
     *
     * @param string $value
     * @return $this
     */
    public function setPrivateKey($value)
    {
        return $this->setParameter('privateKey', $value);
    }

    /**
     * Get chain ID (56 for BSC, 728126428 for TRON).
     *
     * @return int
     */
    public function getChainId()
    {
        return $this->getParameter('chainId');
    }

    /**
     * Set chain ID.
     *
     * @param int $value
     * @return $this
     */
    public function setChainId($value)
    {
        return $this->setParameter('chainId', $value);
    }

    /**
     * Get token contract address.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->getParameter('token');
    }

    /**
     * Set token contract address.
     *
     * @param string $value
     * @return $this
     */
    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }

    /**
     * Get serial number (order reference).
     *
     * @return string
     */
    public function getSerialNo()
    {
        return $this->getParameter('serialNo');
    }

    /**
     * Set serial number.
     *
     * @param string $value
     * @return $this
     */
    public function setSerialNo($value)
    {
        return $this->setParameter('serialNo', $value);
    }

    /**
     * Get deadline timestamp.
     *
     * @return int
     */
    public function getDeadline()
    {
        return $this->getParameter('deadline');
    }

    /**
     * Set deadline timestamp.
     *
     * @param int $value
     * @return $this
     */
    public function setDeadline($value)
    {
        return $this->setParameter('deadline', $value);
    }

    /**
     * Compute Keccak-256 hash.
     *
     * CRITICAL: Must use Keccak-256, NOT SHA3-256.
     * SHA3-256 uses different padding and produces different output.
     *
     * @param string $data Raw binary data to hash
     * @return string Hex-encoded hash
     * @throws \RuntimeException If no Keccak-256 implementation is available
     */
    protected function keccak256($data)
    {
        // Try kornrunner/keccak library first
        if (class_exists('\kornrunner\Keccak')) {
            return \kornrunner\Keccak::hash($data, 256);
        }

        // Try native hash function (PHP 8.1+ with ext-sha3 may support this)
        if (in_array('keccak256', hash_algos())) {
            return hash('keccak256', $data);
        }

        // NEVER fall back to SHA3-256 - they are NOT the same algorithm!
        // SHA3-256 uses NIST padding (0x06) while Keccak-256 uses original padding (0x01)
        throw new \RuntimeException(
            'No Keccak-256 implementation available. ' .
            'Install kornrunner/keccak: composer require kornrunner/keccak. ' .
            'DO NOT use SHA3-256 as a substitute - it produces different hashes.'
        );
    }

    /**
     * Validate chain ID is supported.
     *
     * @throws InvalidRequestException
     */
    protected function validateChainId()
    {
        $chainId = $this->getChainId();
        $supported = [56, 728126428]; // BSC, TRON

        if (!in_array($chainId, $supported, true)) {
            throw new InvalidRequestException(
                "Unsupported chain ID: {$chainId}. Supported: BSC (56), TRON (728126428)"
            );
        }
    }

    /**
     * Get the number of decimals for the current chain.
     *
     * @return int 18 for BSC, 6 for TRON
     */
    protected function getChainDecimals()
    {
        return $this->getChainId() === 728126428 ? 6 : 18;
    }
}
