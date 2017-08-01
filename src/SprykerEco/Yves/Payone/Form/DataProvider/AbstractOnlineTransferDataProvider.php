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
    public function getData(AbstractTransfer $quoteTransfer)
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
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [
            OnlineTransferSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
            OnlineTransferSubForm::OPTION_BANK_GROUP_TYPES => $this->getBankGroupTypes(),
        ];
    }

    /**
     * @return array
     */
    protected function getOnlineBankTransferTypes()
    {
        return [
            'PNT' => 'Sofortbanking',           // (DE, AT, CH, NL)
            'GPY' => 'giropay',                 // (DE)
            'EPS' => 'eps – online transfer',  // (AT)
            'PFF' => 'PostFinance E-Finance',   // (CH)
            'PFC' => 'PostFinance Card',        // (CH)
            'IDL' => 'iDEAL',                   // (NL)
            'P24' => 'Przelewy24', // (P24)
        ];
    }

    /**
     * @return array
     */
    abstract protected function getBankCountries();

    /**
     * @return array
     */
    protected function getBankGroupTypes()
    {
        return [];
    }

}
