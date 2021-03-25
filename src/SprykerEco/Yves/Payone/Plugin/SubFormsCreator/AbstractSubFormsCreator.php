<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\SubFormsCreator;

use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerEco\Yves\Payone\Plugin\PayoneCreditCardSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneDirectDebitSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneEWalletSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneInstantOnlineTransferSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneInvoiceSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneKlarnaSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayonePrePaymentSubFormPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneSecurityInvoiceSubFormPlugin;

abstract class AbstractSubFormsCreator
{
    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayoneCreditCardSubFormPlugin
     */
    protected function createPayoneCreditCardSubFormPlugin()
    {
        return new PayoneCreditCardSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayoneDirectDebitSubFormPlugin
     */
    protected function createPayoneDirectDebitSubFormPlugin()
    {
        return new PayoneDirectDebitSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayonePrePaymentSubFormPlugin
     */
    protected function createPayonePrePaymentSubFormPlugin()
    {
        return new PayonePrePaymentSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayoneInvoiceSubFormPlugin
     */
    protected function createPayoneInvoiceSubFormPlugin()
    {
        return new PayoneInvoiceSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    protected function createPayoneSecurityInvoiceSubFormPlugin(): SubFormPluginInterface
    {
        return new PayoneSecurityInvoiceSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayoneEWalletSubFormPlugin
     */
    protected function createEWalletSubFormPlugin()
    {
        return new PayoneEWalletSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayoneInstantOnlineTransferSubFormPlugin
     */
    protected function createPayoneInstantOnlineTransferSubFormPlugin()
    {
        return new PayoneInstantOnlineTransferSubFormPlugin();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Plugin\PayoneKlarnaSubFormPlugin
     */
    protected function createPayoneKlarnaSubFormPlugin(): SubFormPluginInterface
    {
        return new PayoneKlarnaSubFormPlugin();
    }
}
