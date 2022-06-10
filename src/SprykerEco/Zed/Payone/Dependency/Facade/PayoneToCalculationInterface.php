<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PayoneToCalculationInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $executeQuotePlugins
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculateQuote(QuoteTransfer $quoteTransfer, bool $executeQuotePlugins = true): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function recalculateOrder(OrderTransfer $orderTransfer): OrderTransfer;
}
