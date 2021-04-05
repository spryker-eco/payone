<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm;
use SprykerEco\Yves\Payone\Form\KlarnaSubForm;

/**
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class PayoneKlarnaSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * {@inheritDoc}
     * - Creates KlarnaSubForm.
     *
     * @api
     *
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createSubForm(): AbstractPayoneSubForm
    {
        return $this->getFactory()->createKlarnaSubForm();
    }

    /**
     * {@inheritDoc}
     * - Creates KlarnaSubFormDataProvider.
     *
     * @api
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return $this->getFactory()->createKlarnaSubFormDataProvider();
    }
}
