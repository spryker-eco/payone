<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface PayoneToQuoteClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function setQuote(QuoteTransfer $quoteTransfer);

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote();
}
