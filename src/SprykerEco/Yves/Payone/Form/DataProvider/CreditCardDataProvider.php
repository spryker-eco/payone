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
use SprykerEco\Yves\Payone\Form\CreditCardSubForm;

class CreditCardDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var int YEAR_CHOICES_AMOUNT
     */
    public const YEAR_CHOICES_AMOUNT = 20;

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
            CreditCardSubForm::OPTION_CARD_EXPIRES_CHOICES_MONTH => $this->getMonthChoices(),
            CreditCardSubForm::OPTION_CARD_EXPIRES_CHOICES_YEAR => $this->getYearChoices(),
            CreditCardSubForm::OPTION_CARD_TYPES => $this->getCardTypes(),
        ];
    }

    /**
     * @return array
     */
    protected function getMonthChoices(): array
    {
        return [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
        ];
    }

    /**
     * @return array
     */
    protected function getYearChoices(): array
    {
        $result = [];
        $currentYear = date('Y');

        for ($i = 0; $i < static::YEAR_CHOICES_AMOUNT; $i++) {
            $result[$currentYear] = $currentYear++;
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getCardTypes(): array
    {
        return [
            'Visa' => 'V',
            'Master Card' => 'M',
            'American Express' => 'A',
            'Diners' => 'D',
            'JCB' => 'J',
            'Maestro International' => 'O',
            'Maestro UK' => 'U',
            'Discover' => 'C',
            'Carte Bleue' => 'B',
        ];
    }
}
