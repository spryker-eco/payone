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
class PayoneIdealOnlineTransferSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates `IdealOnlineTransferSubForm` subform.
     *
     * @api
     *
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createIdealOnlineTransferSubForm();
    }

    /**
     * {@inheritDoc}
     * - Creates subform data provider.
     *
     * @api
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createIdealOnlineTransferSubFormDataProvider();
    }
}
