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
class PayoneGiropayOnlineTransferSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{

    /**
     * @return \SprykerEco\Yves\Payone\Form\EpsOnlineTransferSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createGiropayOnlineTransferSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\EpsOnlineTransferDataProvider
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createGiropayOnlineTransferSubFormDataProvider();
    }

}
