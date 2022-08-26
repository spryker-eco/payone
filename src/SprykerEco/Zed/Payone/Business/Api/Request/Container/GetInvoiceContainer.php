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
     * @var string|null
     */
    protected $invoice_title;

    /**
     * @param string $invoice_title
     *
     * @return void
     */
    public function setInvoiceTitle(string $invoice_title): void
    {
        $this->invoice_title = $invoice_title;
    }

    /**
     * @return string|null
     */
    public function getInvoiceTitle(): ?string
    {
        return $this->invoice_title;
    }
}
