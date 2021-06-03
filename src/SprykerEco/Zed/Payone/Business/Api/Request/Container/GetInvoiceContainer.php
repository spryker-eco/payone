<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Shared\Payone\PayoneApiConstants;

class GetInvoiceContainer extends AbstractRequestContainer
{
    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_GETINVOICE;

    /**
     * @var string
     */
    protected $invoice_title;

    /**
     * @param string $invoice_title
     *
     * @return void
     */
    //phpcs:ignore
    public function setInvoiceTitle($invoice_title)
    {
        $this->invoice_title = $invoice_title;
    }

    /**
     * @return string
     */
    //phpcs:ignore
    public function getInvoiceTitle()
    {
        return $this->invoice_title;
    }
}
