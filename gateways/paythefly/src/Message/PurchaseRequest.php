<?php

/**
 * PayTheFly Purchase Request.
 *
 * Generates a PayTheFly payment URL with EIP-712 signature parameters.
 * The user is redirected to this URL to complete payment on-chain.
 *
 * Payment link format:
 * https://pro.paythefly.com/pay?chainId=56&projectId=xxx&amount=0.01&serialNo=xxx&deadline=xxx&signature=0x...&token=0x...
 *
 * IMPORTANT: The amount parameter is human-readable (e.g., "0.01"), NOT raw units.
 */

namespace Omnipay\PayTheFly\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{
    /**
     * Get the data for this request.
     *
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('projectId', 'amount', 'token', 'serialNo');
        $this->validateChainId();

        $deadline = $this->getDeadline();
        if (empty($deadline)) {
            // Default deadline: 30 minutes from now
            $deadline = time() + 1800;
        }

        return array(
            'chainId'   => $this->getChainId(),
            'projectId' => $this->getProjectId(),
            'amount'    => $this->getAmount(), // Human-readable amount, NOT raw units
            'token'     => $this->getToken(),
            'serialNo'  => $this->getSerialNo(),
            'deadline'  => $deadline,
        );
    }

    /**
     * Send the request.
     *
     * PayTheFly uses off-site redirect payments. This method generates
     * the payment URL with all required parameters including the EIP-712
     * signature. No HTTP request is made at this point.
     *
     * @param array $data
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
