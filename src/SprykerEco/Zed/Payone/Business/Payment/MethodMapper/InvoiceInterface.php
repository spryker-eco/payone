<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\MethodMapper;

use Generated\Shared\Transfer\PayoneGetInvoiceTransfer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GetInvoiceContainer;
use SprykerEco\Zed\Payone\Business\Payment\PaymentMethodMapperInterface;

interface InvoiceInterface extends PaymentMethodMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneGetInvoiceTransfer $getInvoiceTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\GetInvoiceContainer
     */
    public function mapGetInvoice(PayoneGetInvoiceTransfer $getInvoiceTransfer): GetInvoiceContainer;
}
