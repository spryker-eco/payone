<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\SubFormsCreator;

use Generated\Shared\Transfer\PaymentTransfer;
use SprykerEco\Yves\Payone\Plugin\PayonePostfinanceCardOnlineTransferSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayonePostfinanceEfinanceOnlineTransferSubFormPlugin;

class ChSubFormsCreator extends AbstractSubFormsCreator implements SubFormsCreatorInterface
{
    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface[]
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
            PaymentTransfer::PAYONE_POSTFINANCE_EFINANCE_ONLINE_TRANSFER => $this->createPayonePostfinanceEfinanceOnlineTransferSubFormPlugin(),
            PaymentTransfer::PAYONE_POSTFINANCE_CARD_ONLINE_TRANSFER => $this->createPayonePostfinanceCardOnlineTransferSubFormPlugin(),
            PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER => $this->createPayoneInstantOnlineTransferSubFormPlugin(),
        ];
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayonePostfinanceEfinanceOnlineTransferSubFormPlugin
     */
    protected function createPayonePostfinanceEfinanceOnlineTransferSubFormPlugin(): PayonePostfinanceEfinanceOnlineTransferSubFormPlugin
    {
        return new PayonePostfinanceEfinanceOnlineTransferSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayonePostfinanceCardOnlineTransferSubFormPlugin
     */
    protected function createPayonePostfinanceCardOnlineTransferSubFormPlugin(): PayonePostfinanceCardOnlineTransferSubFormPlugin
    {
        return new PayonePostfinanceCardOnlineTransferSubFormPlugin();
    }
}
