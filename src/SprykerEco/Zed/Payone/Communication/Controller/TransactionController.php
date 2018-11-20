<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Communication\Controller;

use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use SprykerEco\Shared\Payone\PayoneConstants;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Zed\Payone\Business\PayoneFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
class TransactionController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function statusUpdateAction(Request $request)
    {
        //Payone always sends status updates in ISO-8859-1. We transform them to utf8.
        $requestParameters = $request->request->all();

        $map = [
            // TransferObject (internal) => POST request (external)
            'key' => 'key',
            'aid' => 'aid',
            'mode' => 'mode',
            'customerid' => null,
            'portalid' => 'portalid',
            'sequencenumber' => 'sequencenumber',
            'txaction' => 'txaction',
            'receivable' => 'receivable',
            'price' => 'price',
            'balance' => 'balance',
            'currency' => 'currency',
            'txid' => 'txid',
            'userid' => 'userid',
            'txtime' => 'txtime',
            'clearingtype' => 'clearingtype',
            'reference' => 'reference',
            'reminderlevel' => 'reminderlevel',
        ];

        $dataArray = [];
        foreach ($map as $transferObjectKey => $postDataKey) {
            if (!isset($requestParameters[$postDataKey])) {
                continue;
            }
            $dataArray[$transferObjectKey] = utf8_encode($requestParameters[$postDataKey]);
        }

        $payoneTransactionStatusUpdateTransfer = new PayoneTransactionStatusUpdateTransfer();
        $payoneTransactionStatusUpdateTransfer->fromArray($dataArray);

        $payoneTransactionStatusUpdateTransfer = $this->getFacade()->processTransactionStatusUpdate($payoneTransactionStatusUpdateTransfer);

        $this->triggerEventsOnSuccess($payoneTransactionStatusUpdateTransfer);

        $callback = function () use ($payoneTransactionStatusUpdateTransfer) {
            echo $payoneTransactionStatusUpdateTransfer->getResponse();
        };

        return $this->streamedResponse($callback);
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer $payoneTransactionStatusUpdateTransfer
     *
     * @return void
     */
    protected function triggerEventsOnSuccess(PayoneTransactionStatusUpdateTransfer $payoneTransactionStatusUpdateTransfer)
    {
        if (!$payoneTransactionStatusUpdateTransfer->getIsSuccess()) {
            return;
        }

        $orderItems = SpySalesOrderItemQuery::create()
            ->useOrderQuery()
            ->useSpyPaymentPayoneQuery()
            ->filterByTransactionId($payoneTransactionStatusUpdateTransfer->getTxid())
            ->endUse()
            ->endUse()
            ->find();

        $this->getFactory()->getOmsFacade()->triggerEvent('PaymentNotificationReceived', $orderItems, []);

        if ($payoneTransactionStatusUpdateTransfer->getTxaction() === PayoneConstants::PAYONE_TXACTION_APPOINTED) {
            $this->getFactory()->getOmsFacade()->triggerEvent('RedirectResponseAppointed', $orderItems, []);
        }
    }
}
