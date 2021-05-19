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
use SprykerEco\Yves\Payone\Form\KlarnaSubForm;

class KlarnaDataProvider implements StepEngineFormDataProviderInterface
{
    protected const ADDRESS_SEPARATOR = ' ';

    protected const GIVEN_NAME = 'given_name';
    protected const FAMILY_NAME = 'family_name';
    protected const EMAIL = 'email';
    protected const STREET_ADDRESS = 'street_address';
    protected const POSTAL_CODE = 'postal_code';
    protected const CITY = 'city';
    protected const COUNTRY = 'country';
    protected const PHONE = 'phone';

    protected const DATE_OF_BIRTH = 'date_of_birth';

    protected const SLICE_IT_PAY_METHOD = 'Slice it';
    protected const PAY_LATER_PAY_METHOD = 'Pay later';
    protected const PAY_NOW_PAY_METHOD = 'Pay now';

    protected const SLICE_IT_PAY_METHOD_CODE = 'KIS';
    protected const PAY_LATER_PAY_METHOD_CODE = 'KIV';
    protected const PAY_NOW_PAY_METHOD_CODE = 'KDD';

    protected const SLICE_IT_WIDGET_PAY_METHOD_CODE = 'pay_over_time';
    protected const PAY_LATER_WIDGET_PAY_METHOD_CODE = 'pay_later';
    protected const PAY_NOW_WIDGET_PAY_METHOD_CODE = 'pay_now';

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
     * @return string[]
     */
    public function getOptions(AbstractTransfer $quoteTransfer): array
    {
        $billingAddress = $quoteTransfer->getBillingAddress();

        $currentStoreCountryList = $this->storeClient->getCurrentStore()->getCountries();
        $firstCurrentStoreCountry = reset($currentStoreCountryList);

        return [
            KlarnaSubForm::PAY_METHOD_CHOICES => $this->getPayMethods(),
            KlarnaSubForm::BILLING_ADDRESS_DATA => [
                static::GIVEN_NAME => $billingAddress->getFirstName(),
                static::FAMILY_NAME => $billingAddress->getLastName(),
                static::EMAIL => $quoteTransfer->getCustomer()->getEmail(),
                static::STREET_ADDRESS => implode(self::ADDRESS_SEPARATOR, [$billingAddress->getAddress1(), $billingAddress->getAddress2()]),
                static::POSTAL_CODE => $billingAddress->getZipCode(),
                static::CITY => $billingAddress->getCity(),
                static::COUNTRY => $firstCurrentStoreCountry,
                static::PHONE => $billingAddress->getPhone(),
            ],
            KlarnaSubForm::CUSTOMER_DATA => [
                static::DATE_OF_BIRTH => $quoteTransfer->getCustomer() ? $quoteTransfer->getCustomer()->getDateOfBirth() : null,
            ],
            KlarnaSubForm::WIDGET_PAY_METHODS => $this->getKlarnaPayMethods(),
        ];
    }

    /**
     * @return string[]
     */
    protected function getPayMethods(): array
    {
        return [
            static::PAY_LATER_PAY_METHOD => static::PAY_LATER_PAY_METHOD_CODE,
        ];
    }

    /**
     * @return array
     */
    protected function getKlarnaPayMethods(): array
    {
        return [
            static::SLICE_IT_PAY_METHOD_CODE => static::SLICE_IT_WIDGET_PAY_METHOD_CODE,
            static::PAY_LATER_PAY_METHOD_CODE => static::PAY_LATER_WIDGET_PAY_METHOD_CODE,
            static::PAY_NOW_PAY_METHOD_CODE => static::PAY_NOW_WIDGET_PAY_METHOD_CODE,
        ];
    }
}
