<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Payone\Form\DirectDebitSubForm;

class DirectDebitDataProvider implements StepEngineFormDataProviderInterface
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
     * @return array<string, array<string, string>>
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [
            DirectDebitSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
            DirectDebitSubForm::OPTION_BANK_ACCOUNT_MODE => $this->getBankAccountModes(),
        ];
    }

    /**
     * @return array
     */
    protected function getBankCountries(): array
    {
        return [
            Store::getInstance()->getCurrentCountry() => Store::getInstance()->getCurrentCountry(),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getBankAccountModes(): array
    {
        return [
            'BBAN' => 'BBAN',
            'IBAN/BIC' => 'IBAN/BIC',
        ];
    }
}
