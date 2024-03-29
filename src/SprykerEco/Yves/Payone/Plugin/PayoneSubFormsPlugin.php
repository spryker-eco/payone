<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin;

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
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface[]
     */
    public function getPaymentMethodsSubForms()
    {
        $subFormsCreator = $this->pluginCountryFactory->createSubFormsCreator(
            $this->getFactory()->createStoreReader()->getDefaultStoreCountry(),
        );

        $paymentMethodsSubForms = $subFormsCreator->createPaymentMethodsSubForms();

        return $paymentMethodsSubForms;
    }
}
