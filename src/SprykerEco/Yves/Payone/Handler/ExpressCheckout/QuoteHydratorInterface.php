<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Handler\ExpressCheckout;

use Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteHydratorInterface
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function getHydratedQuote(
        QuoteTransfer $quoteTransfer,
        PayonePaypalExpressCheckoutGenericPaymentResponseTransfer $details);

}