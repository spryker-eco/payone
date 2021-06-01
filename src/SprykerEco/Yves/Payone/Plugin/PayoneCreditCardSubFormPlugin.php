<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm;

/**
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class PayoneCreditCardSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @return \SprykerEco\Yves\Payone\Form\AbstractPayoneSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createCreditCardSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createCreditCardSubFormDataProvider();
    }
}
