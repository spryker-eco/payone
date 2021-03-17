<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayoneAuthorizationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPayment\PaydataContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaPreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;

class Klarna extends AbstractMapper
{
    /**
     * @return string
     */
    public function getName()
    {
        return PayoneApiConstants::PAYMENT_METHOD_KLARNA;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaPreAuthorizationContainer
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity)
    {
        $preAuthorizationContainer = new KlarnaPreAuthorizationContainer();

        $preAuthorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $preAuthorizationContainer);



        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer)
    {
        $authorizationContainer = new KlarnaAuthorizationContainer();
        $authorizationContainer = $this->mapPaymentToAbstractAuthorization($paymentEntity, $authorizationContainer);

        return $authorizationContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $orderItem
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer
     */
    public function mapOrderItemToItemContainer(ItemTransfer $orderItem)
    {
        $itemContainer = new ItemContainer();

        // TODO: set data

        return $itemContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer
     */
    public function mapPaymentToCapture(SpyPaymentPayone $paymentEntity)
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $captureContainer = new CaptureContainer();

        $captureContainer->setAmount($paymentDetailEntity->getAmount());
        $captureContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $captureContainer->setTxid($paymentEntity->getTransactionId());
        $captureContainer->setCapturemode(PayoneApiConstants::CAPTURE_MODE_COMPLETED); // TODO: maybe remove?

        return $captureContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity)
    {
        $debitContainer = new DebitContainer();

        $debitContainer->setTxid($paymentEntity->getTransactionId());
        $debitContainer->setSequenceNumber($this->getNextSequenceNumber($paymentEntity->getTransactionId()));
        $debitContainer->setCurrency($this->getStandardParameter()->getCurrency());
        $debitContainer->setAmount($paymentEntity->getSpyPaymentPayoneDetail()->getAmount());

        return $debitContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer
     */
    public function mapPaymentToRefund(SpyPaymentPayone $paymentEntity)
    {
        $refundContainer = new RefundContainer();

        $refundContainer->setTxid($paymentEntity->getTransactionId());
        $refundContainer->setSequenceNumber($this->getNextSequenceNumber($paymentEntity->getTransactionId()));
        $refundContainer->setCurrency($this->getStandardParameter()->getCurrency());

        return $refundContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer
     */
    public function mapPaymentToStartSession(QuoteTransfer $quoteTransfer): KlarnaGenericPaymentContainer
    {
        $klarnaGenericPaymentContainer = new KlarnaGenericPaymentContainer();

        $klarnaGenericPaymentContainer->setMid($this->getStandardParameter()->getMid()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setAid($this->getStandardParameter()->getAid()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setPortalid($this->getStandardParameter()->getPortalId()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setKey($this->getStandardParameter()->getKey()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setCountry('DE'); // TODO: finalize data setup   $quoteTransfer->getBillingAddress()->getCountry()
        $klarnaGenericPaymentContainer->setAmount(11070); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setCurrency($quoteTransfer->getCurrency()->getCode()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $klarnaGenericPaymentContainer->setFinancingtype('KIV');
        $klarnaGenericPaymentContainer->setLanguage('en'); // TODO: finalize data setup

        // TODO: set data

        $arrayIt[1] = PayoneApiConstants::INVOICING_ITEM_TYPE_GOODS;
        $arrayId[1] = 'fffff';
        $arrayPr[1] = 11070;
        $arrayNo[1] = 1;
        $arrayDe[1] = 'some good';
        $arrayVa[1] = '3000';
        $klarnaGenericPaymentContainer->setIt($arrayIt);
        $klarnaGenericPaymentContainer->setId($arrayId);
        $klarnaGenericPaymentContainer->setPr($arrayPr);
        $klarnaGenericPaymentContainer->setNo($arrayNo);
        $klarnaGenericPaymentContainer->setDe($arrayDe);
        $klarnaGenericPaymentContainer->setVa($arrayVa);

        $paydataContainer = new PaydataContainer();
        $paydataContainer->setAction('start_session');
        $klarnaGenericPaymentContainer->setPaydata($paydataContainer);

        return $klarnaGenericPaymentContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer $authorizationContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer
     */
    protected function mapPaymentToAbstractAuthorization(SpyPaymentPayone $paymentEntity, AbstractAuthorizationContainer $authorizationContainer)
    {
        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setMid($this->getStandardParameter()->getMid()); // TODO: finalize data setup
        $authorizationContainer->setAid($this->getStandardParameter()->getAid()); // TODO: finalize data setup
        $authorizationContainer->setPortalid($this->getStandardParameter()->getPortalId()); // TODO: finalize data setup
        $authorizationContainer->setKey($this->getStandardParameter()->getKey()); // TODO: finalize data setup
//        $authorizationContainer->setCountry('DE'); // TODO: finalize data setup   $quoteTransfer->getBillingAddress()->getCountry()

        $paymentDetailEntity = $paymentEntity->getSpyPaymentPayoneDetail();

        $authorizationContainer->setAmount($paymentDetailEntity->getAmount()); // TODO: finalize data setup
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency()); // TODO: finalize data setup
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $authorizationContainer->setFinancingtype('KIV'); // TODO: set actual data
        $authorizationContainer->setLanguage('en'); // TODO: set actual data


        $authorizationContainer->setAid($this->getStandardParameter()->getAid());
        $authorizationContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $authorizationContainer->setReference($paymentEntity->getReference());
        $authorizationContainer->setAmount($paymentDetailEntity->getAmount());
        $authorizationContainer->setCurrency($this->getStandardParameter()->getCurrency());

        $paydataContainer = new PaydataContainer();
        $paydataContainer->setAuthorizationToken('60ad3c17-d448-2143-9ffd-5cffa1d7f4d3');
        $authorizationContainer->setPaydata($paydataContainer);

        $authorizationContainer->setPersonalData($this->buildPersonalContainer($paymentEntity));
        $orderReference = $paymentEntity->getSpySalesOrder()->getOrderReference();
        $authorizationContainer->setRedirect($this->createRedirectContainer($orderReference));

        // TODO: set data

        $arrayIt[1] = PayoneApiConstants::INVOICING_ITEM_TYPE_GOODS;
        $arrayId[1] = 'fffff';
        $arrayPr[1] = 11070;
        $arrayNo[1] = 1;
        $arrayDe[1] = 'some good';
        $arrayVa[1] = '3000';
        $authorizationContainer->setIt($arrayIt);
        $authorizationContainer->setId($arrayId);
        $authorizationContainer->setPr($arrayPr);
        $authorizationContainer->setNo($arrayNo);
        $authorizationContainer->setDe($arrayDe);
        $authorizationContainer->setVa($arrayVa);

        return $authorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected function buildPersonalContainer(SpyPaymentPayone $paymentEntity): PersonalContainer
    {
        $personalContainer = new PersonalContainer();

        $this->mapBillingAddressToPersonalContainer($personalContainer, $paymentEntity);

        $personalContainer->setLanguage($this->getStandardParameter()->getLanguage());
        $personalContainer->setIp('123.123.123.123'); // TODO: set actual data

        return $personalContainer;
    }
}

