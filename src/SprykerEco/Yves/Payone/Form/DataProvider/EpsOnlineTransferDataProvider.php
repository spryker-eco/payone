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
            EpsOnlineTransferSubForm::OPTION_BANK_COUNTRIES => $this->getBankCountries(),
            EpsOnlineTransferSubForm::OPTION_BANK_GROUP_TYPES => $this->getBankGroupTypes(),
        ];
    }

    /**
     * @return array
     */
    protected function getBankCountries()
    {
        return [
            'AT' => 'Austria',
        ];
    }

    /**
     * @return array
     */
    protected function getBankGroupTypes()
    {
        return [
            'ARZ_OVB' => 'Commercial credit cooperatives (Volksbank)',
            'ARZ_BAF' => 'Bank for doctors and independent professions',
            'ARZ_NLH' => 'Hypo state bank Lower Austria',
            'ARZ_VLH' => 'Hypo state bank Voralberg',
            'ARZ_BCS' => 'Bankhaus Carl Spängler & Co. AG',
            'ARZ_HTB' => 'Hypo bank Tyrol',
            'ARZ_HAA' => 'Hypo Alpe Adria',
            'ARZ_IKB' => 'Investkredit bank',
            'ARZ_OAB' => 'Österreichische Apothekerbank',
            'ARZ_IMB' => 'Immobank',
            'ARZ_GRB' => 'Gärtnerbank',
            'ARZ_HIB' => 'HYPO Investment bank',
            'BA_AUS' => 'Bank Austria',
            'BAWAG_BWG' => 'BAWAG',
            'BAWAG_PSK' => 'PSK Bank',
            'BAWAG_ESY' => 'easybank',
            'BAWAG_SPD' => 'Sparda Bank',
            'SPARDAT_EBS' => 'Erste Bank',
            'SPARDAT_BBL' => 'Bank Burgenland',
            'RAC_RAC' => 'Raiffeisen bank',
            'HRAC_OOS' => 'Hypo bank Upper Austria',
            'HRAC_SLB' => 'Hypo bank Salzburg',
            'HRAC_STM' => 'Hypo bank Styria',
            'EPS_SCHEL' => 'Bankhaus Schelhammer',
            'EPS_OBAG' => 'Oberbank AG',
            'EPS_SCHOELLER' => 'Schoellerbank AG',
            'EPS_SPDLI' => 'Sparda-Bank Linz',
            'EPS_SPDVI' => 'Sparda-Bank Villach',
            'EPS_VRBB' => 'VR-Bank Brunau',
        ];
    }

}
