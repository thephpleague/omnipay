<?php
/**
 * Created by sqiu.
 * CreateTime: 14-1-3 上午12:10
 *
 */
namespace Omnipay\Alipay\Message;

use Omnipay\Common\Message\AbstractResponse;

class ExpressCompletePurchaseResponse extends AbstractResponse
{

    /**
     * @var ExpressCompletePurchaseRequest
     */
    protected $request;

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data['verify_success']) {
            return true;
        } else {
            return false;
        }
    }

    public function isTradeStatusOk()
    {
        $status = $this->request->getTradeStatus();
        return ($status == 'TRADE_FINISHED' || $status == 'TRADE_SUCCESS');
    }

    public function getResponseText()
    {
        if ($this->isSuccessful()) {
            return 'success';
        } else {
            return 'fail';
        }
    }
}
