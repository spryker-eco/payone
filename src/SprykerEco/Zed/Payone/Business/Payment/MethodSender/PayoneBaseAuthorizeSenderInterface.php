<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;

interface PayoneBaseAuthorizeSenderInterface
{
    /**
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $paymentEntity
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface $requestContainer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer
     */
    public function performAuthorizationRequest(SpyPaymentPayone $paymentEntity, AuthorizationContainerInterface $requestContainer): AuthorizationResponseContainer;
}
