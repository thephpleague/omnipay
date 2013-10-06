<?php

namespace Omnipay\SagePay\Message;

/**
 * Sage Pay Refund Request
 */
class RefundRequest extends AbstractRequest
{
    protected $action = 'REFUND';

    public function getData()
    {
        $this->validate('amount', 'transactionReference');
        $reference = json_decode($this->getTransactionReference(), true);

        $data = $this->getBaseData();
        $data['Amount'] = $this->getAmount();
        $data['Currency'] = $this->getCurrency();
        $data['Description'] = $this->getDescription();
        $data['RelatedVendorTxCode'] = $reference['VendorTxCode'];
        $data['RelatedVPSTxId'] = $reference['VPSTxId'];
        $data['RelatedSecurityKey'] = $reference['SecurityKey'];
        $data['RelatedTxAuthNo'] = $reference['TxAuthNo'];

        // VendorTxCode must be unique for the refund (different from original)
        $data['VendorTxCode'] = $this->getTransactionId();

        return $data;
    }
}
