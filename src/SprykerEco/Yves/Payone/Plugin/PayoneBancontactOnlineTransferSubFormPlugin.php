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
class PayoneBancontactOnlineTransferSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @return \SprykerEco\Yves\Payone\Form\BancontactOnlineTransferSubForm
     */
    public function createSubForm()
    {
        return $this->getFactory()->createBancontactOnlineTransferSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\BancontactOnlineTransferDataProvider
     */
    public function createSubFormDataProvider()
    {
        return $this->getFactory()->createBancontactOnlineTransferSubFormDataProvider();
    }
}
