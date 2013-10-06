<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Capture Request
 */
class CaptureRequest extends AbstractRequest
{
    protected $action = 'RELEASE';

    public function getData()
    {
        $this->validate('amount', 'transactionReference');
        $reference = json_decode($this->getTransactionReference(), true);

        $data = $this->getBaseData();
        $data['ReleaseAmount'] = $this->getAmount();
        $data['VendorTxCode'] = $reference['VendorTxCode'];
        $data['VPSTxId'] = $reference['VPSTxId'];
        $data['SecurityKey'] = $reference['SecurityKey'];
        $data['TxAuthNo'] = $reference['TxAuthNo'];

        return $data;
    }
}
