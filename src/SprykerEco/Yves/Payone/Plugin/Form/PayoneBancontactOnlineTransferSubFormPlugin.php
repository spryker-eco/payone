<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Plugin\Form;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerEco\Yves\Payone\Form\BancontactOnlineTransferSubForm;
use SprykerEco\Yves\Payone\Form\DataProvider\BancontactOnlineTransferDataProvider;

/**
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class PayoneBancontactOnlineTransferSubFormPlugin extends AbstractPlugin implements SubFormPluginInterface
{
    /**
     * @return \SprykerEco\Yves\Payone\Form\BancontactOnlineTransferSubForm
     */
    public function createSubForm(): BancontactOnlineTransferSubForm
    {
        return $this->getFactory()->createBancontactOnlineTransferSubForm();
    }

    /**
     * @return \SprykerEco\Yves\Payone\Form\DataProvider\BancontactOnlineTransferDataProvider
     */
    public function createSubFormDataProvider(): BancontactOnlineTransferDataProvider
    {
        return $this->getFactory()->createBancontactOnlineTransferSubFormDataProvider();
    }
}
