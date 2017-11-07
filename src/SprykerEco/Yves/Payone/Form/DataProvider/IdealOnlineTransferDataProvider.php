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
use SprykerEco\Yves\Payone\Form\IdealOnlineTransferSubForm;

class IdealOnlineTransferDataProvider implements StepEngineFormDataProviderInterface
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
            IdealOnlineTransferSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
            IdealOnlineTransferSubForm::OPTION_BANK_GROUP_TYPES => $this->getBankGroupTypes(),
        ];
    }

    /**
     * @return array
     */
    protected function getBankCountries()
    {
        return [
            'NL' => 'Netherlands',
        ];
    }

    /**
     * @return array
     */
    protected function getBankGroupTypes()
    {
        return [
            'ABN_AMRO_BANK' => 'ABN Amro',
            'RABOBANK' => 'Rabobank',
            'FRIESLAND_BANK' => 'Friesland Bank',
            'ASN_BANK' => 'ASN Bank',
            'SNS_BANK' => 'SNS Bank',
            'TRIODOS_BANK' => 'Triodos',
            'SNS_REGIO_BANK' => 'SNS Regio Bank',
            'ING_BANK' => 'ING',
            'KNAB_BANK' => 'Knab Bank',
            'VAN_LANSCHOT_BANKIERS' => 'van Lanschot Bank',
        ];
    }
}
