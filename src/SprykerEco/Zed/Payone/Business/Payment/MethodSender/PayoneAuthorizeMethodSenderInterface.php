<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapper;

interface PayoneAuthorizeMethodSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function authorizePayment(OrderTransfer $orderTransfer): AuthorizationResponseTransfer;
}
