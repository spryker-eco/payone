<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneAuthorizationTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Spryker\Shared\Kernel\Store;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CashOnDeliveryContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\BankAccountCheckContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface;
use SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface;

class CashOnDelivery extends AbstractMapper
{
    /**
     * @var \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Shared\Kernel\Store $storeConfig
     * @param \SprykerEco\Zed\Payone\Dependency\Facade\PayoneToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(Store $storeConfig, PayoneToGlossaryFacadeInterface $glossaryFacade)
    {
        parent::__construct($storeConfig);
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return PayoneApiConstants::PAYMENT_METHOD_CASH_ON_DELIVERY;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentPayoneEntity, OrderTransfer $orderTransfer): AuthorizationContainerInterface
    {
        $authorizationContainer = new AuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentPayoneEntity, $authorizationContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainerInterface
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentPayoneEntity): CaptureContainerInterface
    {
        $paymentDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();

        $captureContainer = new CaptureContainer();
        $captureContainer->setAmount($paymentDetailEntity->getAmount());
        if ($this->getStandardParameter()->getCurrency() !== null) {
            $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        }
        $captureContainer->setTxid($paymentPayoneEntity->getTransactionId());
        if ($paymentPayoneEntity->getTransactionId() !== null) {
            $captureContainer->setSequenceNumber($this->getNextSequenceNumber($paymentPayoneEntity->getTransactionId()));
        }

        return $captureContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface|\SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentPayoneEntity)
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();
        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentPayoneEntity, $preAuthorizationContainer);

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainerInterface
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentPayoneEntity): DebitContainerInterface
    {
        $debitContainer = new DebitContainer();

        $debitContainer->setTxid($paymentPayoneEntity->getTransactionId());
        if ($paymentPayoneEntity->getTransactionId() !== null) {
            $debitContainer->setSequenceNumber($this->getNextSequenceNumber($paymentPayoneEntity->getTransactionId()));
        }
        $debitContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $debitContainer->setAmount($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getAmount());

        return $debitContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainerInterface
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentPayoneEntity): RefundContainerInterface
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($paymentPayoneEntity->getTransactionId());
        if ($paymentPayoneEntity->getTransactionId() !== null) {
            $refundContainer->setSequenceNumber($this->getNextSequenceNumber($paymentPayoneEntity->getTransactionId()));
        }
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());

        $refundContainer->setBankcountry($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankCountry());
        $refundContainer->setBankaccount($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankAccount());
        $refundContainer->setBankcode($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankCode());
        $refundContainer->setBankbranchcode($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankBranchCode());
        $refundContainer->setBankcheckdigit($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBankCheckDigit());
        $refundContainer->setIban($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getIban());
        $refundContainer->setBic($paymentPayoneEntity->getSpyPaymentPayoneDetail()->getBic());

        return $refundContainer;
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
        $bankAccountCheckContainer->setBankAccount($bankAccountCheckTransfer->getBankAccount());
        $bankAccountCheckContainer->setBankCode($bankAccountCheckTransfer->getBankCode());
        $bankAccountCheckContainer->setIban($bankAccountCheckTransfer->getIban());
        $bankAccountCheckContainer->setBic($bankAccountCheckTransfer->getBic());
        $bankAccountCheckContainer->setLanguage($this->getStandardParameter()->getLanguage());

        return $bankAccountCheckContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface|\SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainerInterface
     */
    protected function mapPaymentToAbstractAuthorization(
        SpyPaymentPayone $paymentPayoneEntity,
        AbstractAuthorizationContainer $authorizationContainer
    ) {
        $paymentDetailEntity = $paymentPayoneEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_CASH_ON_DELIVERY);
        $authorizationContainer->setReference($paymentPayoneEntity->getReference());
        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        if ($this->getStandardParameter()->getCurrency() !== null) {
            $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());
        }
        $authorizationContainer->setPaymentMethod($this->createPaymentMethodContainerFromPayment($paymentPayoneEntity));

        $personalContainer = $this->buildPersonalContainer($paymentPayoneEntity);
        $authorizationContainer->setPersonalData($personalContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function buildPersonalContainer(SpyPaymentPayone $paymentPayoneEntity): PersonalContainer
    {
        $personalContainer = new PersonalContainer();

        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentPayoneEntity);

        $personalContainer->setLanguage($this->getStandardParameter()->getLanguage());

        return $personalContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CashOnDeliveryContainer
     */
    protected function createPaymentMethodContainerFromPayment(SpyPaymentPayone $paymentPayoneEntity): CashOnDeliveryContainer
    {
        $shippingProviderName = $paymentPayoneEntity->getSpyPaymentPayoneDetail()->getShippingProvider();

        /** @var string|null $translatedShippingProviderName */
        $translatedShippingProviderName = $shippingProviderName ? $this->translate($shippingProviderName) : $shippingProviderName;

        $paymentMethodContainer = new CashOnDeliveryContainer();
        $paymentMethodContainer->setShippingProvider($translatedShippingProviderName);

        return $paymentMethodContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PayoneAuthorizationTransfer $authorizationData
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function createAuthorizationPersonalData(PayoneAuthorizationTransfer $authorizationData): PersonalContainer
    {
        $personalContainer = new PersonalContainer();

        $personalContainer->setFirstName($authorizationData->getOrder()->getFirstName());
        $personalContainer->setLastName($authorizationData->getOrder()->getLastName());
        $personalContainer->setCountry($this->storeConfig->getCurrentCountry());

        return $personalContainer;
    }

    /**
     * @param string $glossaryKey
     *
     * @return string
     */
    protected function translate(string $glossaryKey): string
    {
        return $this->glossaryFacade->translate($glossaryKey, []);
    }
}
