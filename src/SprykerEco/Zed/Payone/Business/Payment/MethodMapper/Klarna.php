<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\KlarnaGenericPaymentContainer;
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer
     */
    public function mapPaymentToAuthorization(SpyPaymentPayone $paymentEntity, OrderTransfer $orderTransfer)
    {
        $authorizationContainer = new AuthorizationContainer();

        // TODO: set data

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
        $captureContainer = new CaptureContainer();

        // TODO: set data

        return $captureContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer
     */
    public function mapPaymentToPreAuthorization(SpyPaymentPayone $paymentEntity)
    {
        $preAuthorizationContainer = new PreAuthorizationContainer();

        // TODO: set data

        return $preAuthorizationContainer;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer
     */
    public function mapPaymentToDebit(SpyPaymentPayone $paymentEntity)
    {
        $debitContainer = new DebitContainer();

        // TODO: set data

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

        // TODO: set data

        return $refundContainer;
    }

    public function mapPaymentToStartSession(QuoteTransfer $quoteTransfer): KlarnaGenericPaymentContainer
    {
        $klarnaGenericPaymentContainer = new KlarnaGenericPaymentContainer();

        $klarnaGenericPaymentContainer->setMid($this->getStandardParameter()->getMid()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setAid($this->getStandardParameter()->getAid()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setPortalid($this->getStandardParameter()->getPortalId()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setKey($this->getStandardParameter()->getKey()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setCountry($quoteTransfer->getBillingAddress()->getCountry()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setAmount(3000); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setCurrency($quoteTransfer->getCurrency()->getCode()); // TODO: finalize data setup
        $klarnaGenericPaymentContainer->setClearingType(PayoneApiConstants::CLEARING_TYPE_FINANCING);
        $klarnaGenericPaymentContainer->setFinancingtype('KIS');

        // TODO: set data

        $arrayIt[1] = PayoneApiConstants::INVOICING_ITEM_TYPE_GOODS;
        $arrayId[1] = 'fffff';
        $arrayPr[1] = 3000;
        $arrayNo[1] = 1;
        $arrayDe[1] = 'some good';
        $arrayVa[1] = 3000;
        $klarnaGenericPaymentContainer->setIt($arrayIt);
        $klarnaGenericPaymentContainer->setId($arrayId);
        $klarnaGenericPaymentContainer->setPr($arrayPr);
        $klarnaGenericPaymentContainer->setNo($arrayNo);
        $klarnaGenericPaymentContainer->setDe($arrayDe);
        $klarnaGenericPaymentContainer->setVa($arrayVa);

        return $klarnaGenericPaymentContainer;
    }
}
