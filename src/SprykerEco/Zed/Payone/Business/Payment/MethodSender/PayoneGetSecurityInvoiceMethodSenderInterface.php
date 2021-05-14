<?php

namespace SprykerEco\Zed\Payone\Business\Payment\MethodSender;

use Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer;

interface PayoneGetSecurityInvoiceMethodSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer
     *
     * @return \Generated\Shared\Transfer\PayoneGetSecurityInvoiceTransfer
     */
    public function getSecurityInvoice(PayoneGetSecurityInvoiceTransfer $getSecurityInvoiceTransfer): PayoneGetSecurityInvoiceTransfer;
}
