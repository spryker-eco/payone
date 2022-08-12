<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\ApiLog;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

class ApiLogFinder implements ApiLogFinderInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected PayoneRepositoryInterface $payoneRepository;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     */
    public function __construct(PayoneRepositoryInterface $payoneRepository)
    {
        $this->payoneRepository = $payoneRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationApproved(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationRedirect(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_REDIRECT,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPreAuthorizationError(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_ERROR,
        ) || $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_TIMEOUT,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationApproved(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationRedirect(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_REDIRECT,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isAuthorizationError(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION,
            PayoneApiConstants::RESPONSE_TYPE_ERROR,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureApproved(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_CAPTURE,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isCaptureError(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_CAPTURE,
            PayoneApiConstants::RESPONSE_TYPE_ERROR,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundApproved(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_REFUND,
            PayoneApiConstants::RESPONSE_TYPE_APPROVED,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isRefundError(OrderTransfer $orderTransfer): bool
    {
        return $this->hasApiLogStatus(
            $orderTransfer,
            PayoneApiConstants::REQUEST_TYPE_REFUND,
            PayoneApiConstants::RESPONSE_TYPE_ERROR,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $request Relevant request
     * @param string $status Expected status
     *
     * @return bool
     */
    protected function hasApiLogStatus(OrderTransfer $orderTransfer, string $request, string $status): bool
    {
        $idSalesOrder = $orderTransfer->getIdSalesOrderOrFail();
        $apiLog = $this->payoneRepository->createApiLogsByOrderIdAndRequest($idSalesOrder, $request)->filterByStatus($status)->findOne();

        if ($apiLog === null) {
            return false;
        }

        return $apiLog->getStatus() === $status;
    }

    /**
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function findPaymentByTransactionId(int $transactionId): SpyPaymentPayone
    {
        return $this->payoneRepository->createPaymentByTransactionIdQuery($transactionId)->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function findPaymentByOrder(OrderTransfer $orderTransfer): SpyPaymentPayone
    {
        return $this->payoneRepository->createPaymentByOrderId($orderTransfer->getIdSalesOrderOrFail())->findOne();
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $payment
     * @param string $authorizationType
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog
     */
    protected function findApiLog(SpyPaymentPayone $payment, string $authorizationType): SpyPaymentPayoneApiLog
    {
        return $this->payoneRepository->createApiLogByPaymentAndRequestTypeQuery(
            $payment->getPrimaryKey(),
            $authorizationType,
        )->findOne();
    }
}
