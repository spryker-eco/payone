<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainerInterface;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

abstract class AbstractAuthorizationContainer extends AbstractRequestContainer implements AuthorizationContainerInterface
{
    /**
     * Sub account ID
     *
     * @var string
     */
    protected $aid;

    /**
     * @var string
     */
    protected $clearingtype;

    /**
     * @var string
     */
    protected $clearingsubtype;

    /**
     * Merchant reference number for the payment process. (Permitted symbols: 0-9, a-z, A-Z, .,-,_,/)
     *
     * @var string
     */
    protected $reference;

    /**
     * Total amount (in smallest currency unit! e.g. cent)
     *
     * @var int|null
     */
    protected $amount;

    /**
     * Currency (ISO-4217)
     *
     * @var string|null
     */
    protected $currency;

    /**
     * Individual parameter
     *
     * @var string
     */
    protected $param;

    /**
     * dynamic text for debit and creditcard payments
     *
     * @var string
     */
    protected $narrativeText;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    protected $personalData;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer
     */
    protected $shippingData;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer
     */
    protected $paymentMethod;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer
     */
    protected $threeDSecure;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    protected $invoicing;

    /**
     * @var string
     */
    protected $onlinebanktransfertype;

    /**
     * @var string
     */
    protected $bankcountry;

    /**
     * @var string
     */
    protected $businessrelation;

    /**
     * @return string|null
     */
    public function getBusinessrelation(): ?string
    {
        return $this->businessrelation;
    }

    /**
     * @param string $businessrelation
     *
     * @return void
     */
    public function setBusinessrelation(string $businessrelation): void
    {
        $this->businessrelation = $businessrelation;
    }

    /**
     * @param int|null $amount
     *
     * @return void
     */
    public function setAmount(?int $amount = null): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @param string $clearingType
     *
     * @return void
     */
    public function setClearingType(string $clearingType): void
    {
        $this->clearingtype = $clearingType;
    }

    /**
     * @return string
     */
    public function getClearingType(): string
    {
        return $this->clearingtype;
    }

    /**
     * @param string $currency
     *
     * @return void
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $narrativeText
     *
     * @return void
     */
    public function setNarrativeText(string $narrativeText): void
    {
        $this->narrativeText = $narrativeText;
    }

    /**
     * @return string
     */
    public function getNarrativeText(): string
    {
        return $this->narrativeText;
    }

    /**
     * @param string $param
     *
     * @return void
     */
    public function setParam(string $param): void
    {
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function getParam(): string
    {
        return $this->param;
    }

    /**
     * @param string $reference
     *
     * @return void
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer $personalData
     *
     * @return void
     */
    public function setPersonalData(PersonalContainer $personalData): void
    {
        $this->personalData = $personalData;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    public function getPersonalData(): PersonalContainer
    {
        return $this->personalData;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer $delivery
     *
     * @return void
     */
    public function setShippingData(ShippingContainer $delivery): void
    {
        $this->shippingData = $delivery;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer
     */
    public function getShippingData(): ShippingContainer
    {
        return $this->shippingData;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     *
     * @return void
     */
    public function setPaymentMethod(AbstractPaymentMethodContainer $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer
     */
    public function getPaymentMethod(): AbstractPaymentMethodContainer
    {
        return $this->paymentMethod;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer $secure
     *
     * @return void
     */
    public function set3dsecure(ThreeDSecureContainer $secure): void
    {
        $this->threeDSecure = $secure;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer
     */
    public function get3dsecure(): ThreeDSecureContainer
    {
        return $this->threeDSecure;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing): void
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    public function getInvoicing(): TransactionContainer
    {
        return $this->invoicing;
    }

    /**
     * @return string
     */
    public function getOnlinebanktransfertype(): string
    {
        return $this->onlinebanktransfertype;
    }

    /**
     * @param string $onlinebanktransfertype
     *
     * @return void
     */
    public function setOnlinebanktransfertype(string $onlinebanktransfertype): void
    {
        $this->onlinebanktransfertype = $onlinebanktransfertype;
    }

    /**
     * @return string
     */
    public function getClearingsubtype(): string
    {
        return $this->clearingsubtype;
    }

    /**
     * @param string $clearingsubtype
     *
     * @return void
     */
    public function setClearingsubtype(string $clearingsubtype): void
    {
        $this->clearingsubtype = $clearingsubtype;
    }
}
