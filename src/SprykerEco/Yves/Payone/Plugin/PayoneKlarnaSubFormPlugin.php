<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerEco\Yves\Payone\Form\DataProvider\KlarnaDataProvider;
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
     * @return \SprykerEco\Yves\Payone\Form\KlarnaSubForm
     */
    public function createSubForm(): KlarnaSubForm
    {
        return $this->getFactory()->createKlarnaSubForm();
    }

    /**
     * {@inheritDoc}
     * - Creates KlarnaSubFormDataProvider.
     *
     * @api
     *
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\KlarnaDataProvider
     */
    public function createSubFormDataProvider(): KlarnaDataProvider
    {
        return $this->getFactory()->createKlarnaSubFormDataProvider();
    }
}
