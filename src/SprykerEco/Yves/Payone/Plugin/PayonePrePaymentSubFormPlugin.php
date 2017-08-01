<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;

/**
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class PayonePrePaymentSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{

    /**
     * @return \SprykerEco\Yves\Payone\Form\PrePaymentForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createPrePaymentForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createPrePaymentFormDataProvider();
    }

}
