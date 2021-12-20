<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class PayoneSubFormsPlugin extends AbstractPlugin
{
    /**
     * @var \SprykerEco\Yves\Payone\Plugin\PluginCountryFactory
     */
    protected $pluginCountryFactory;

    public function __construct()
    {
        $this->pluginCountryFactory = new PluginCountryFactory();
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface>
     */
    public function getPaymentMethodsSubForms(): array
    {
        $subFormsCreator = $this->pluginCountryFactory->createSubFormsCreator(Store::getInstance()->getCurrentCountry());

        $paymentMethodsSubForms = $subFormsCreator->createPaymentMethodsSubForms();

        return $paymentMethodsSubForms;
    }
}
