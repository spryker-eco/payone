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
use SprykerEco\Yves\Payone\Form\EpsOnlineTransferSubForm;

class EpsOnlineTransferDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): AbstractTransfer
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
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        return [
            EpsOnlineTransferSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
            EpsOnlineTransferSubForm::OPTION_BANK_GROUP_TYPES => $this->getBankGroupTypes(),
        ];
    }

    /**
     * @return array
     */
    protected function getBankCountries(): array
    {
        return [
            'AT' => 'Austria',
        ];
    }

    /**
     * @return array
     */
    protected function getBankGroupTypes(): array
    {
        return [
            'Commercial credit cooperatives (Volksbank)' => 'ARZ_OVB',
            'Bank for doctors and independent professions' => 'ARZ_BAF',
            'Hypo state bank Lower Austria' => 'ARZ_NLH',
            'Hypo state bank Voralberg' => 'ARZ_VLH',
            'Bankhaus Carl Spängler & Co. AG' => 'ARZ_BCS',
            'Hypo bank Tyrol' => 'ARZ_HTB',
            'Hypo Alpe Adria' => 'ARZ_HAA',
            'Investkredit bank' => 'ARZ_IKB',
            'Österreichische Apotheker' => 'ARZ_OAB',
            'Immobank' => 'ARZ_IMB',
            'Gärtnerbank' => 'ARZ_GRB',
            'HYPO Investment bank' => 'ARZ_HIB',
            'Bank Austria' => 'BA_AUS',
            'BAWAG' => 'BAWAG_BWG',
            'PSK Bank' => 'BAWAG_PSK',
            'easybank' => 'BAWAG_ESY',
            'Sparda Bank' => 'BAWAG_SPD',
            'Erste Bank' => 'SPARDAT_EBS',
            'Bank Burgenland' => 'SPARDAT_BBL',
            'Raiffeisen bank' => 'RAC_RAC',
            'Hypo bank Upper Austria' => 'HRAC_OOS',
            'Hypo bank Salzburg' => 'HRAC_SLB',
            'Hypo bank Styria' => 'HRAC_STM',
            'Bankhaus Schelhammer' => 'EPS_SCHEL',
            'Oberbank AG' => 'EPS_OBAG',
            'Schoellerbank AG' => 'EPS_SCHOELLER',
            'Sparda-Bank Linz' => 'EPS_SPDLI',
            'Sparda-Bank Villach' => 'EPS_SPDVI',
            'VR-Bank Brunau' => 'EPS_VRBB',
        ];
    }
}
