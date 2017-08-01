<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment;

use Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer;

interface GenericPaymentMethodMapperInterface
{

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer $genericPayment
     * @param \Generated\Shared\Transfer\PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer
     */
    public function mapRequestTransferToGenericPayment(
        GenericPaymentContainer $genericPayment,
        PayoneInitPaypalExpressCheckoutRequestTransfer $requestTransfer
    );

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer $genericPayment
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer
     */
    public function mapQuoteTransferToGenericPayment(
        GenericPaymentContainer $genericPayment,
        QuoteTransfer $quoteTransfer
    );

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GenericPaymentContainer
     */
    public function createBaseGenericPaymentContainer();

}
