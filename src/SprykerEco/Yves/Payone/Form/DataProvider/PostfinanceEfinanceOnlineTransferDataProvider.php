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
use SprykerEco\Yves\Payone\Form\PostfinanceEfinanceOnlineTransferSubForm;

class PostfinanceEfinanceOnlineTransferDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        if ($quoteTransfer instanceof QuoteTransfer && $quoteTransfer->getPayment() === null) {
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
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [
            PostfinanceEfinanceOnlineTransferSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
        ];
    }

    /**
     * @return array
     */
    protected function getBankCountries(): array
    {
        return [
            'CH' => 'Switzerland',
        ];
    }
}
