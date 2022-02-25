<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Payone\Form\BancontactOnlineTransferSubForm;
use SprykerEco\Yves\Payone\PayoneConfig;

class BancontactOnlineTransferDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \SprykerEco\Yves\Payone\PayoneConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Yves\Payone\PayoneConfig $config
     */
    public function __construct(PayoneConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        if ($quoteTransfer instanceof QuoteTransfer && $quoteTransfer->getPayment() !== null) {
            return $quoteTransfer;
        }

        $paymentTransfer = (new PaymentTransfer())->setPayone(new PayonePaymentTransfer());

        if ($quoteTransfer instanceof QuoteTransfer) {
            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [
            BancontactOnlineTransferSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
        ];
    }

    /**
     * @return array
     */
    protected function getBankCountries(): array
    {
        return $this->config->getPayOneBancontactAvailableCountries();
    }
}
