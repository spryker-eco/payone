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
use SprykerEco\Yves\Payone\Form\OnlineTransferSubForm;

abstract class AbstractOnlineTransferDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
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
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [
            OnlineTransferSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
            OnlineTransferSubForm::OPTION_BANK_GROUP_TYPES => $this->getBankGroupTypes(),
        ];
    }

    /**
     * @return array
     */
    protected function getOnlineBankTransferTypes(): array
    {
        return [
            'Sofortbanking' => 'PNT', // (DE, AT, CH, NL)
            'giropay' => 'GPY', // (DE)
            'eps – online transfer' => 'EPS', // (AT)
            'PostFinance E-Finance' => 'PFF', // (CH)
            'PostFinance Card' => 'PFC', // (CH)
            'iDEAL' => 'IDL', // (NL)
            'Przelewy24' => 'P24', // (P24)
        ];
    }

    /**
     * @return array
     */
    abstract protected function getBankCountries(): array;

    /**
     * @return array
     */
    protected function getBankGroupTypes(): array
    {
        return [];
    }
}
