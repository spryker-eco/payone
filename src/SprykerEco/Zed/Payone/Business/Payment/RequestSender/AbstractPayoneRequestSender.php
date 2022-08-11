<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;
use SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface;

abstract class AbstractPayoneRequestSender
{
    /**
     * @var \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface
     */
    protected $payoneRepository;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface
     */
    protected $paymentMapperReader;

    /**
     * @param \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface $payoneRepository
     * @param \SprykerEco\Zed\Payone\Business\Payment\PaymentMapperReaderInterface $paymentMapperReader
     */
    public function __construct(
        PayoneRepositoryInterface $payoneRepository,
        PaymentMapperReaderInterface $paymentMapperReader
    ) {
        $this->payoneRepository = $payoneRepository;
        $this->paymentMapperReader = $paymentMapperReader;
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     *
     * @return \SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface
     */
    protected function getPaymentMethodMapper(SpyPaymentPayone $paymentPayoneEntity): PaymentMethodMapperInterface
    {
        return $this->paymentMapperReader->getRegisteredPaymentMethodMapper($paymentPayoneEntity->getPaymentMethod());
    }

    /**
     * @param int $orderId
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function getPaymentEntity(int $orderId): SpyPaymentPayone
    {
        return $this->payoneRepository->createPaymentById($orderId)->findOne();
    }

    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentPayoneEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLog
     */
    protected function initializeApiLog(SpyPaymentPayone $paymentPayoneEntity, AbstractRequestContainer $container): SpyPaymentPayoneApiLog
    {
        $entity = new SpyPaymentPayoneApiLog();

        $entity->setSpyPaymentPayone($paymentPayoneEntity);
        $entity->setRequest($container->getRequest());
        $entity->setMode((string)$container->getMode());
        $entity->setMerchantId((string)$container->getMid());
        $entity->setPortalId((string)$container->getPortalid());
        if ($container instanceof CaptureContainer || $container instanceof RefundContainer || $container instanceof DebitContainer) {
            $entity->setSequenceNumber((int)$container->getSequenceNumber());
        }

        $entity->setRawRequest((string)json_encode($container->toArray()));
        $entity->save();

        return $entity;
    }
}
