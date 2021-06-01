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
    protected function createPayoneCreditCardSubFormPlugin(): SubFormPluginInterface
    {
        return new PayoneCreditCardSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    protected function createPayoneDirectDebitSubFormPlugin(): SubFormPluginInterface
    {
        return new PayoneDirectDebitSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    protected function createPayonePrePaymentSubFormPlugin(): SubFormPluginInterface
    {
        return new PayonePrePaymentSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    protected function createPayoneInvoiceSubFormPlugin(): SubFormPluginInterface
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
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    protected function createEWalletSubFormPlugin(): SubFormPluginInterface
    {
        return new PayoneEWalletSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    protected function createPayoneInstantOnlineTransferSubFormPlugin(): SubFormPluginInterface
    {
        return new PayoneInstantOnlineTransferSubFormPlugin();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface
     */
    protected function createPayoneKlarnaSubFormPlugin(): SubFormPluginInterface
    {
        return new PayoneKlarnaSubFormPlugin();
    }
}
