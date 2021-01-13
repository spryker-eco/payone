<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business;

use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem;
use SprykerEco\Shared\Payone\PayoneApiConstants;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Ratepay
 * @group Business
 * @group Request
 * @group AbstractPayoneTest
 */
abstract class AbstractPayoneTest extends AbstractBusinessTest
{
    /**
     * @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog
     */
    protected $spyPayoneTransactionStatusLog;

    /**
     * @param string $method
     *
     * @return void
     */
    protected function createPayonePayment($method = PayoneApiConstants::PAYMENT_METHOD_INVOICE)
    {
        $this->spyPaymentPayone = (new SpyPaymentPayone())
            ->setSpySalesOrder($this->orderEntity)
            ->setPaymentMethod($method)
            ->setReference('TX15887428dd2212')
            ->setTransactionId('213552995');
        $this->spyPaymentPayone->save();
    }

    /**
     * @param string $request
     * @param string $status
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog
     */
    protected function createPayoneApiLog(
        $request = PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
        $status = PayoneApiConstants::RESPONSE_TYPE_APPROVED
    ) {
        $paymentApiLog = (new SpyPaymentPayoneApiLog())
            ->setRequest($request)
            ->setStatus($status)
            ->setRedirectUrl('redirect url')
            ->setMode(PayoneApiConstants::MODE_TEST)
            ->setTransactionId('213552995')
            ->setMerchantId('32481')
            ->setUserId('123')
            ->setPortalId('123')
            ->setSpyPaymentPayone($this->spyPaymentPayone);
        $paymentApiLog->save();

        return $paymentApiLog;
    }

    /**
     * @param string $iban
     * @param string $bic
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail
     */
    protected function createPayonePaymentDetail($iban = '', $bic = '')
    {
        $paymentDetail = (new SpyPaymentPayoneDetail())
            ->setAmount(12345)
            ->setIban($iban)
            ->setBic($bic)
            ->setCurrency('EUR')
            ->setSpyPaymentPayone($this->spyPaymentPayone);
        $paymentDetail->save();

        return $paymentDetail;
    }

    /**
     * @param string $status
     * @param int $balance
     *
     * @return void
     */
    protected function createPayoneTransactionStatusLog($status = PayoneApiConstants::RESPONSE_TYPE_APPROVED, $balance = 0)
    {
        $this->spyPayoneTransactionStatusLog = (new SpyPaymentPayoneTransactionStatusLog())
            ->setSpyPaymentPayone($this->spyPaymentPayone)
            ->setBalance($balance)
            ->setStatus($status)
            ->setMode(PayoneApiConstants::MODE_TEST);
        $this->spyPayoneTransactionStatusLog->save();
    }

    /**
     * @param int $idOrderItem
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem
     */
    protected function createPayoneTransactionStatusLogItem($idOrderItem)
    {
        $transactionStatusLogOrderItem = (new SpyPaymentPayoneTransactionStatusLogOrderItem())
            ->setSpyPaymentPayoneTransactionStatusLog($this->spyPayoneTransactionStatusLog)
            ->setIdSalesOrderItem($idOrderItem);
        $transactionStatusLogOrderItem->save();

        return $transactionStatusLogOrderItem;
    }
}
