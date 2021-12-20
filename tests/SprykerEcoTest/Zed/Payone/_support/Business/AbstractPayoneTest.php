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
 * @group Payone
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
    protected function createPayonePayment(string $method = PayoneApiConstants::PAYMENT_METHOD_INVOICE): void
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
        string $request = PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
        string $status = PayoneApiConstants::RESPONSE_TYPE_APPROVED
    ): SpyPaymentPayoneApiLog {
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
     * @param string $request
     * @param string $status
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog
     */
    protected function createPayoneApiLogWithError(
        string $request = PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
        string $status = PayoneApiConstants::RESPONSE_TYPE_APPROVED
    ): SpyPaymentPayoneApiLog {
        $paymentApiLog = (new SpyPaymentPayoneApiLog())
            ->setRequest($request)
            ->setStatus($status)
            ->setRedirectUrl('redirect url')
            ->setMode(PayoneApiConstants::MODE_TEST)
            ->setTransactionId('213552995')
            ->setMerchantId('32481')
            ->setUserId('123')
            ->setPortalId('123')
            ->setErrorCode('123')
            ->setErrorMessageUser('any error')
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
    protected function createPayonePaymentDetail(string $iban = '', string $bic = ''): SpyPaymentPayoneDetail
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
    protected function createPayoneTransactionStatusLog(string $status = PayoneApiConstants::RESPONSE_TYPE_APPROVED, int $balance = 0): void
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
    protected function createPayoneTransactionStatusLogItem(int $idOrderItem): SpyPaymentPayoneTransactionStatusLogOrderItem
    {
        $transactionStatusLogOrderItem = (new SpyPaymentPayoneTransactionStatusLogOrderItem())
            ->setSpyPaymentPayoneTransactionStatusLog($this->spyPayoneTransactionStatusLog)
            ->setIdSalesOrderItem($idOrderItem);
        $transactionStatusLogOrderItem->save();

        return $transactionStatusLogOrderItem;
    }
}
