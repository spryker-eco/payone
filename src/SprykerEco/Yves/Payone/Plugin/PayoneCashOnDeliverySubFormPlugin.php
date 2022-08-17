<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;

/**
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class PayoneCashOnDeliverySubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates `CashOnDeliverySubForm` subform.
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createSubForm(): SubFormInterface
    {
        return $this->getFactory()->createCashOnDeliverySubForm();
    }

    /**
     * {@inheritDoc}
     * - Creates subform data provider.
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return $this->getFactory()->createCashOnDeliverySubFormDataProvider();
    }
}
