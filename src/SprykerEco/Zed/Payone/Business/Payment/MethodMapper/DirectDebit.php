<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneGetFileTransfer;
use Generated\Shared\Transfer\PayoneManageMandateTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\DirectDebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\BankAccountCheckContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GetFileContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ManageMandateContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface;

class DirectDebit extends AbstractMapper implements DirectDebitInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return PayoneApiConstants::PAYMENT_METHOD_DIRECT_DEBIT;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer): AbstractAuthorizationContainer
    {
        $authorizationContainer = new AuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $authorizationContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity): RefundContainerInterface
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($paymentEntity->getTransactionId());
        $refundContainer->setSequenceNumber($this->getNextSequenceNumber((int)$paymentEntity->getTransactionId()));
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());

        return $refundContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity): PreAuthorizationContainerInterface
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity): CaptureContainerInterface
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $captureContainer = new CaptureContainer();
        $captureContainer->setAmount($paymentDetailEntity->getAmount());
        $captureContainer->setCurrency((string)$this->getStandardParameter()->getCurrency());
        $captureContainer->setTxid($paymentEntity->getTransactionId());
        $captureContainer->setSequenceNumber($this->getNextSequenceNumber((int)$paymentEntity->getTransactionId()));

        return $captureContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity): DebitContainerInterface
    {
        $debitContainer = new DebitContainer();

        $debitContainer->setTxid($paymentEntity->getTransactionId());
        $debitContainer->setSequenceNumber($this->getNextSequenceNumber((int)$paymentEntity->getTransactionId()));
        $debitContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $debitContainer->setAmount($paymentEntity->getSpyPaymentPayoneDetail()->getAmount());

        return $debitContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneBankAccountCheckTransfer $bankAccountCheckTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\BankAccountCheckContainer
     */
    public function mapBankAccountCheck(PayoneBankAccountCheckTransfer $bankAccountCheckTransfer): BankAccountCheckContainer
    {
        $bankAccountCheckContainer = new BankAccountCheckContainer();

        $bankAccountCheckContainer->setAid($this->getStandardParameter()->getAid());
        $bankAccountCheckContainer->setBankCountry($bankAccountCheckTransfer->getBankCountry());
        $bankAccountCheckContainer->setBankAccount((string)$bankAccountCheckTransfer->getBankAccount());
        $bankAccountCheckContainer->setBankCode($bankAccountCheckTransfer->getBankCode());
        $bankAccountCheckContainer->setIban($bankAccountCheckTransfer->getIban());
        $bankAccountCheckContainer->setBic($bankAccountCheckTransfer->getBic());

        return $bankAccountCheckContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneManageMandateTransfer $manageMandateTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ManageMandateContainer
     */
    public function mapManageMandate(PayoneManageMandateTransfer $manageMandateTransfer): ManageMandateContainer
    {
        $manageMandateContainer = new ManageMandateContainer();

        $manageMandateContainer->setAid($this->getStandardParameter()->getAid());
        $manageMandateContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_DIRECT_DEBIT);
        $manageMandateContainer->setCurrency((string)$this->getStandardParameter()->getCurrency());
        $manageMandateContainer->setCustomerid((int)$manageMandateTransfer->getPersonalData()->getCustomerId());
        $manageMandateContainer->setLastname((string)$manageMandateTransfer->getPersonalData()->getLastName());
        $manageMandateContainer->setFirstname((string)$manageMandateTransfer->getPersonalData()->getFirstName());
        $manageMandateContainer->setCity((string)$manageMandateTransfer->getPersonalData()->getCity());
        $manageMandateContainer->setCountry((string)$manageMandateTransfer->getPersonalData()->getCountry());
        $manageMandateContainer->setEmail($manageMandateTransfer->getPersonalData()->getEmail());
        $manageMandateContainer->setLanguage($this->getStandardParameter()->getLanguage());

        $manageMandateContainer->setBankCountry((string)$manageMandateTransfer->getBankCountry());
        $manageMandateContainer->setBankAccount((string)$manageMandateTransfer->getBankAccount());
        $manageMandateContainer->setBankCode((string)$manageMandateTransfer->getBankCode());
        $manageMandateContainer->setIban((string)$manageMandateTransfer->getIban());
        $manageMandateContainer->setBic((string)$manageMandateTransfer->getBic());

        return $manageMandateContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    protected function mapPaymentToAbstractAuthorization(
        SpyPaymentPayone $paymentEntity,
        AbstractAuthorizationContainer $authorizationContainer
    ): AbstractAuthorizationContainer {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_DIRECT_DEBIT);
        $authorizationContainer->setReference($paymentEntity->getReference());
        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        $authorizationContainer->setCurrency((string)$this->getStandardParameter()->getCurrency());
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainerFromPayment($paymentEntity));

        $personalContainer = new PersonalContainer();
        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentEntity);

        $authorizationContainer->setPersonalData($personalContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\DirectDebitContainer
     */
    protected function createPaymentMethodContainerFromPayment(
        SpyPaymentPayone $paymentEntity
    ): DirectDebitContainer {
        $paymentMethodContainer = new DirectDebitContainer();

        $paymentMethodContainer->setBankCountry((string)$paymentEntity->getSpyPaymentPayoneDetail()->getBankCountry());
        $paymentMethodContainer->setBankAccount((string)$paymentEntity->getSpyPaymentPayoneDetail()->getBankAccount());
        $paymentMethodContainer->setBankCode((string)$paymentEntity->getSpyPaymentPayoneDetail()->getBankCode());
        $paymentMethodContainer->setIban((string)$paymentEntity->getSpyPaymentPayoneDetail()->getIban());
        $paymentMethodContainer->setBic((string)$paymentEntity->getSpyPaymentPayoneDetail()->getBic());

        return $paymentMethodContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneGetFileTransfer $getFileTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GetFileContainer
     */
    public function mapGetFile(PayoneGetFileTransfer $getFileTransfer): GetFileContainer
    {
        $getFileContainer = new GetFileContainer();

        $getFileContainer->setFileReference((string)$getFileTransfer->getReference());
        $getFileContainer->setFileType(PayoneApiConstants::FILE_TYPE_MANDATE);
        $getFileContainer->setFileFormat(PayoneApiConstants::FILE_FORMAT_PDF);

        return $getFileContainer;
    }
}
