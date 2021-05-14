<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Response\Mapper\AuthorizationResponseMapper;

interface PayonePreAuthorizeMethodSenderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function preAuthorizePayment($idSalesOrder): AuthorizationResponseTransfer;
}
