<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Form\DataProvider;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerEco\Yves\Payone\Form\KlarnaSubForm;

class KlarnaDataProvider implements StepEngineFormDataProviderInterface
{
    protected const ADDRESS_SEPARATOR = ' ';

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer): AbstractTransfer
    {
        if ($quoteTransfer->getPayment() !== null) {
            return $quoteTransfer;
        }

        $paymentTransfer = new PaymentTransfer();
        $paymentTransfer->setPayone(new PayonePaymentTransfer());
        $quoteTransfer->setPayment($paymentTransfer);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        $billingAddress = $quoteTransfer->getBillingAddress();
        $items = $quoteTransfer->getItems();

        return [
            KlarnaSubForm::PAY_METHOD_CHOICES => $this->getPayMethods(),
            KlarnaSubForm::BILLING_ADDRESS_DATA => [
                KlarnaSubForm::GIVEN_NAME => $billingAddress->getFirstName(),
                KlarnaSubForm::FAMILY_NAME => $billingAddress->getLastName(),
                KlarnaSubForm::EMAIL => $quoteTransfer->getCustomer()->getEmail(),
                KlarnaSubForm::STREET_ADDRESS => implode(self::ADDRESS_SEPARATOR, [$billingAddress->getAddress1(), $billingAddress->getAddress2()]),
                KlarnaSubForm::POSTAL_CODE => $billingAddress->getZipCode(),
                KlarnaSubForm::CITY => $billingAddress->getCity(),
                KlarnaSubForm::COUNTRY => $this->store->getCurrentCountry(),
                KlarnaSubForm::PHONE => $billingAddress->getPhone(),
            ],
            KlarnaSubForm::CUSTOMER_DATA => [
                KlarnaSubForm::DATE_OF_BIRTH => $quoteTransfer->getCustomer() ? $quoteTransfer->getCustomer()->getDateOfBirth() : null,
            ],
            KlarnaSubForm::WIDGET_PAY_METHODS => $this->getKlarnaPayMethods(),
        ];
    }

    /**
     * @return array
     */
    protected function getPayMethods(): array
    {
        return [
            KlarnaSubForm::SLICE_IT_PAY_METHOD => KlarnaSubForm::SLICE_IT_PAY_METHOD_CODE,
            KlarnaSubForm::PAY_LATER_PAY_METHOD => KlarnaSubForm::PAY_LATER_PAY_METHOD_CODE,
            KlarnaSubForm::PAY_NOW_PAY_METHOD => KlarnaSubForm::PAY_NOW_PAY_METHOD_CODE,
        ];
    }

    /**
     * @return array
     */
    protected function getKlarnaPayMethods(): array
    {
        return [
            KlarnaSubForm::SLICE_IT_PAY_METHOD_CODE => KlarnaSubForm::SLICE_IT_WIDGET_PAY_METHOD_CODE,
            KlarnaSubForm::PAY_LATER_PAY_METHOD_CODE => KlarnaSubForm::PAY_LATER_WIDGET_PAY_METHOD_CODE,
            KlarnaSubForm::PAY_NOW_PAY_METHOD_CODE => KlarnaSubForm::PAY_NOW_WIDGET_PAY_METHOD_CODE,
        ];
    }
}
