<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientInterface;
use SprykerEco\Yves\Payone\Form\InstantOnlineTransferSubForm;

class InstantOnlineTransferDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \SprykerEco\Yves\Payone\Dependency\Client\PayoneToStoreClientInterface $storeClient
     */
    public function __construct(PayoneToStoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
        if ($quoteTransfer->getPayment() === null) {
            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setPayone(new PayonePaymentTransfer());
            $quoteTransfer->setPayment($paymentTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [
            InstantOnlineTransferSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getBankCountries(): array
    {
        $countries = $this->storeClient->getCurrentStore()->getCountries();

        return array_combine($countries, $countries);
    }
}
