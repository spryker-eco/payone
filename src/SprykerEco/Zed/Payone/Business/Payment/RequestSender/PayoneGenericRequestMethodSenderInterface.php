<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\RequestSender;

use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer;

interface PayoneGenericRequestMethodSenderInterface
{
    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer $requestContainer
     *
     * @return \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer
     */
    public function performGenericRequest(GenericPaymentContainer $requestContainer): PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
}
