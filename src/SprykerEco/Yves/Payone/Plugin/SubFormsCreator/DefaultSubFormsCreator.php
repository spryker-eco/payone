<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\SubFormsCreator;

use Generated\Shared\Transfer\PaymentTransfer;

class DefaultSubFormsCreator extends AbstractSubFormsCreator implements SubFormsCreatorInterface
{
    /**
     * @return array<\Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface>
     */
    public function createPaymentMethodsSubForms(): array
    {
        return [
            PaymentTransfer::PAYONE_CREDIT_CARD => $this->createPayoneCreditCardSubFormPlugin(),
            PaymentTransfer::PAYONE_DIRECT_DEBIT => $this->createPayoneDirectDebitSubFormPlugin(),
            PaymentTransfer::PAYONE_PRE_PAYMENT => $this->createPayonePrePaymentSubFormPlugin(),
            PaymentTransfer::PAYONE_INVOICE => $this->createPayoneInvoiceSubFormPlugin(),
            PaymentTransfer::PAYONE_SECURITY_INVOICE => $this->createPayoneSecurityInvoiceSubFormPlugin(),
            PaymentTransfer::PAYONE_E_WALLET => $this->createEWalletSubFormPlugin(),
            PaymentTransfer::PAYONE_KLARNA => $this->createPayoneKlarnaSubFormPlugin(),
        ];
    }
}
