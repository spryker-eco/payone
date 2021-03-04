<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\SubFormsCreator;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Yves\Payone\Plugin\PayoneGiropayOnlineTransferSubFormPlugin;

class DeSubFormsCreator extends AbstractSubFormsCreator implements SubFormsCreatorInterface
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface[]
     */
    public function createPaymentMethodsSubForms()
    {
        return [
            PaymentTransfer::PAYONE_CREDIT_CARD => $this->createPayoneCreditCardSubFormPlugin(),
            PaymentTransfer::PAYONE_DIRECT_DEBIT => $this->createPayoneDirectDebitSubFormPlugin(),
            PaymentTransfer::PAYONE_PRE_PAYMENT => $this->createPayonePrePaymentSubFormPlugin(),
            PaymentTransfer::PAYONE_INVOICE => $this->createPayoneInvoiceSubFormPlugin(),
            PaymentTransfer::PAYONE_SECURITY_INVOICE => $this->createPayoneSecurityInvoiceSubFormPlugin(),
            PaymentTransfer::PAYONE_E_WALLET => $this->createEWalletSubFormPlugin(),
            PaymentTransfer::PAYONE_GIROPAY_ONLINE_TRANSFER => $this->createPayoneGiropayOnlineTransferSubFormPlugin(),
            PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER => $this->createPayoneInstantOnlineTransferSubFormPlugin(),
            PaymentTransfer::PAYONE_KLARNA => $this->createPayoneInstantOnlineTransferSubFormPlugin(),
        ];
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayoneGiropayOnlineTransferSubFormPlugin
     */
    protected function createPayoneGiropayOnlineTransferSubFormPlugin()
    {
        return new PayoneGiropayOnlineTransferSubFormPlugin();
    }
}
