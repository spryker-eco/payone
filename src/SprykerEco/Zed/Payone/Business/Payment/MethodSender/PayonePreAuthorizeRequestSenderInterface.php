<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;

interface PayonePreAuthorizeRequestSenderInterface
{
    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\AuthorizationResponseTransfer
     */
    public function preAuthorizePayment(int $idSalesOrder): AuthorizationResponseTransfer;
}
