<?php
// phpcs:disable Spryker.ControlStructures.DisallowCloakingCheck.FixableEmpty
// phpcs:disable Spryker.ControlStructures.DisallowCloakingCheck.InvalidEmpty

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\TransactionStatus;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem;
use SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface;
use SprykerEco\Shared\Payone\PayoneTransactionStatusConstants;
use SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse;
use SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface;

class TransactionStatusUpdateManager implements TransactionStatusUpdateManagerInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface
     */
    protected $hashGenerator;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface $queryContainer
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameter
     * @param \SprykerEco\Zed\Payone\Business\Key\HashGeneratorInterface $hashGenerator
     */
    public function __construct(
        PayoneQueryContainerInterface $queryContainer,
        PayoneStandardParameterTransfer $standardParameter,
        HashGeneratorInterface $hashGenerator
    ) {
        $this->queryContainer = $queryContainer;
        $this->standardParameter = $standardParameter;
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * @param \SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    public function processTransactionStatusUpdate(TransactionStatusUpdateInterface $request): TransactionStatusResponse
    {
        $validationResult = $this->validate($request);
        if ($validationResult instanceof TransactionStatusResponse) {
            return $validationResult;
        }
        $this->transformCurrency($request);

        $paymentPayoneEntity = $this->findPaymentByTransactionId($request->getTxid());

        if (!$paymentPayoneEntity) {
            return $this->createErrorResponse('Payone transaction status update: Payment was not found!');
        }

        $this->persistRequest($request, $paymentPayoneEntity);

        return $this->createSuccessResponse();
    }

    /**
     * @param \SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone|null $paymentPayoneEntity
     *
     * @return void
     */
    protected function persistRequest(TransactionStatusUpdateInterface $request, ?SpyPaymentPayone $paymentPayoneEntity = null): void
    {
        if (!$paymentPayoneEntity) {
            return;
        }

        $paymentPayoneTransactionStatusLogEntity = new SpyPaymentPayoneTransactionStatusLog();
        $paymentPayoneTransactionStatusLogEntity->setFkPaymentPayone($paymentPayoneEntity->getIdPaymentPayone());
        $paymentPayoneTransactionStatusLogEntity->setTransactionId($request->getTxid());
        $paymentPayoneTransactionStatusLogEntity->setReferenceId($request->getReference());
        $paymentPayoneTransactionStatusLogEntity->setMode($request->getMode());
        $paymentPayoneTransactionStatusLogEntity->setStatus($request->getTxaction());
        $paymentPayoneTransactionStatusLogEntity->setTransactionTime($request->getTxtime());
        $paymentPayoneTransactionStatusLogEntity->setSequenceNumber($request->getSequencenumber());
        $paymentPayoneTransactionStatusLogEntity->setClearingType($request->getClearingtype());
        $paymentPayoneTransactionStatusLogEntity->setPortalId($request->getPortalid());
        $paymentPayoneTransactionStatusLogEntity->setPrice($request->getPrice());
        $paymentPayoneTransactionStatusLogEntity->setBalance($request->getBalance());
        $paymentPayoneTransactionStatusLogEntity->setReceivable($request->getReceivable());
        $paymentPayoneTransactionStatusLogEntity->setReminderLevel($request->getReminderlevel());
        $paymentPayoneTransactionStatusLogEntity->setRawRequest($request);

        $paymentPayoneTransactionStatusLogEntity->save();
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentNotificationAvailable(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        return $this->hasUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);
    }

    /**
     * @param \SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return void
     */
    protected function transformCurrency(TransactionStatusUpdateInterface $request): void
    {
        $balance = $request->getBalance();
        $balanceAmountInCents = round((float)$balance * 100);

        $isTransactionStatusRequest = $request instanceof TransactionStatusRequest;

        if ($isTransactionStatusRequest) {
            $request->setBalance($balanceAmountInCents);
        }

        $receivable = $request->getReceivable();
        $receivableAmountInCents = round((float)$receivable * 100);

        if ($isTransactionStatusRequest) {
            $request->setReceivable($receivableAmountInCents);
        }

        $price = $request->getPrice();
        $priceAmountInCents = round((float)$price * 100);

        if ($isTransactionStatusRequest) {
            $request->setPrice($priceAmountInCents);
        }
    }

    /**
     * @param \SprykerEco\Shared\Payone\Dependency\TransactionStatusUpdateInterface $request
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse|bool
     */
    protected function validate(TransactionStatusUpdateInterface $request)
    {
        $systemHashedKey = $this->hashGenerator->hash($this->standardParameter->getKey() ?? '');
        if ($request->getKey() !== $systemHashedKey) {
            return $this->createErrorResponse('Payone transaction status update: Given and internal key do not match!');
        }

        if ((int)$request->getAid() !== (int)$this->standardParameter->getAid()) {
            return $this->createErrorResponse('Payone transaction status update: Invalid Aid! System: ' . $this->standardParameter->getAid() . ' Request: ' . $request->getAid());
        }

        if ((int)$request->getPortalid() !== (int)$this->standardParameter->getPortalId()) {
            return $this->createErrorResponse('Payone transaction status update: Invalid Portalid! System: ' . $this->standardParameter->getPortalId() . ' Request: ' . $request->getPortalid());
        }

        return true;
    }

    /**
     * @param string $errorMessage
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    protected function createErrorResponse(string $errorMessage): TransactionStatusResponse
    {
        $response = new TransactionStatusResponse(false);
        $response->setErrorMessage($errorMessage);

        return $response;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusResponse
     */
    protected function createSuccessResponse(): TransactionStatusResponse
    {
        return new TransactionStatusResponse(true);
    }

    /**
     * @param int $transactionId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone|null
     */
    protected function findPaymentByTransactionId(int $transactionId): ?SpyPaymentPayone
    {
        return $this->queryContainer->createPaymentByTransactionIdQuery($transactionId)->findOne();
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentPaid(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        $status = PayoneTransactionStatusConstants::TXACTION_PAID;
        $statusLog = $this->getFirstUnprocessedTransactionStatusLog($idSalesOrder, $idSalesOrderItem, $status);
        if ($statusLog === null) {
            return false;
        }
        if ($statusLog->getBalance() > 0) {
            return false;
        }

        $this->saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, $statusLog);

        return true;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentCapture(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        $status = PayoneTransactionStatusConstants::TXACTION_CAPTURE;

        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOverpaid(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        $status = PayoneTransactionStatusConstants::TXACTION_PAID;
        $statusLog = $this->getFirstUnprocessedTransactionStatusLog($idSalesOrder, $idSalesOrderItem, $status);
        if ($statusLog === null) {
            return false;
        }
        if ($statusLog->getBalance() >= 0) {
            return false;
        }

        $this->saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, $statusLog);

        return true;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentUnderpaid(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        $status = PayoneTransactionStatusConstants::TXACTION_UNDERPAID;

        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentRefund(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        $status = PayoneTransactionStatusConstants::TXACTION_REFUND;

        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentAppointed(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        $status = PayoneTransactionStatusConstants::TXACTION_APPOINTED;

        return $this->isPayment($idSalesOrder, $idSalesOrderItem, $status);
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isPaymentOther(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        $statusLogs = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);
        if (empty($statusLogs)) {
            return false;
        }

        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $statusLog */
        $statusLog = array_shift($statusLogs);

        $statuses = [
            PayoneTransactionStatusConstants::TXACTION_PAID,
            PayoneTransactionStatusConstants::TXACTION_APPOINTED,
            PayoneTransactionStatusConstants::TXACTION_UNDERPAID,
        ];
        if (in_array($statusLog->getStatus(), $statuses)) {
            return false;
        }

        $this->saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, $statusLog);

        return true;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     * @param string $status
     *
     * @return bool
     */
    protected function isPayment(int $idSalesOrder, int $idSalesOrderItem, string $status): bool
    {
        $statusLog = $this->getFirstUnprocessedTransactionStatusLog($idSalesOrder, $idSalesOrderItem, $status);
        if ($statusLog === null) {
            return false;
        }

        $this->saveSpyPaymentPayoneTransactionStatusLogOrderItem($idSalesOrderItem, $statusLog);

        return true;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    protected function hasUnprocessedTransactionStatusLogs(int $idSalesOrder, int $idSalesOrderItem): bool
    {
        $records = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);

        return (bool)$records;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     * @param string $status
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog|null
     */
    protected function getFirstUnprocessedTransactionStatusLog(int $idSalesOrder, int $idSalesOrderItem, string $status): ?SpyPaymentPayoneTransactionStatusLog
    {
        $transactionStatusLogs = $this->getUnprocessedTransactionStatusLogs($idSalesOrder, $idSalesOrderItem);

        if (empty($transactionStatusLogs)) {
            return null;
        }

        while (count($transactionStatusLogs)) {
            /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $transactionStatusLog */
            $transactionStatusLog = array_shift($transactionStatusLogs);

            if ($transactionStatusLog->getStatus() == $status) {
                return $transactionStatusLog;
            }
        }

        return null;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesOrderItem
     *
     * @return array<\Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog>
     */
    protected function getUnprocessedTransactionStatusLogs(int $idSalesOrder, int $idSalesOrderItem): array
    {
        $transactionStatusLogs = $this->queryContainer->createTransactionStatusLogsBySalesOrder($idSalesOrder)->find();

        $ids = [];

        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $transactionStatusLog */
        foreach ($transactionStatusLogs as $transactionStatusLog) {
            $ids[$transactionStatusLog->getIdPaymentPayoneTransactionStatusLog()] = $transactionStatusLog;
        }

        $relations = $this->queryContainer
            ->createTransactionStatusLogOrderItemsByLogIds($idSalesOrderItem, array_keys($ids))
            ->find();

        /** @var \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItem $relation */
        foreach ($relations as $relation) {
            unset($ids[$relation->getIdPaymentPayoneTransactionStatusLog()]);
        }

        return $ids;
    }

    /**
     * @param int $idSalesOrderItem
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLog $statusLog
     *
     * @return void
     */
    protected function saveSpyPaymentPayoneTransactionStatusLogOrderItem(int $idSalesOrderItem, SpyPaymentPayoneTransactionStatusLog $statusLog): void
    {
        $entity = new SpyPaymentPayoneTransactionStatusLogOrderItem();
        $entity->setSpyPaymentPayoneTransactionStatusLog($statusLog);
        $entity->setIdSalesOrderItem($idSalesOrderItem);
        $entity->save();
    }
}
