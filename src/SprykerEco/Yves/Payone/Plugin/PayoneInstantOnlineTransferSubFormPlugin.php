<?php
//phpcs:ignoreFile

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
class PayoneInstantOnlineTransferSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @return \SprykerEco\Yves\Payone\Form\InstantOnlineTransferSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createInstantOnlineTransferSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\InstantOnlineTransferDataProvider
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createInstantOnlineTransferSubFormDataProvider();
    }
}
