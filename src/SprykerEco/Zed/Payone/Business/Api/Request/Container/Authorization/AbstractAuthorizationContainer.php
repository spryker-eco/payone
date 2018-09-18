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
     * @var int
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
     * @var int
     */
    protected $amount;

    /**
     * Currency (ISO-4217)
     *
     * @var string
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
    protected $narrative_text;

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
    protected $_3dsecure;

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
     */
    public function setBusinessrelation(string $businessrelation): void
    {
        $this->businessrelation = $businessrelation;
    }

    /**
     * @var array
     */
    protected $it;

    /**
     * @var array
     */
    protected $id;

    /**
     * @var array
     */
    protected $pr;

    /**
     * @var array
     */
    protected $no;

    /**
     * @var array
     */
    protected $de;

    /**
     * @var array
     */
    protected $va;

    /**
     * @var string
     */
    protected $email;

    /**
     * @param int $aid
     *
     * @return void
     */
    public function setAid($aid)
    {
        $this->aid = $aid;
    }

    /**
     * @return int
     */
    public function getAid()
    {
        return $this->aid;
    }

    /**
     * @param int $amount
     *
     * @return void
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $clearingType
     *
     * @return void
     */
    public function setClearingType($clearingType)
    {
        $this->clearingtype = $clearingType;
    }

    /**
     * @return string
     */
    public function getClearingType()
    {
        return $this->clearingtype;
    }

    /**
     * @param string $currency
     *
     * @return void
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $narrative_text
     *
     * @return void
     */
    public function setNarrativeText($narrative_text)
    {
        $this->narrative_text = $narrative_text;
    }

    /**
     * @return string
     */
    public function getNarrativeText()
    {
        return $this->narrative_text;
    }

    /**
     * @param string $param
     *
     * @return void
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param string $reference
     *
     * @return void
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer $personalData
     *
     * @return void
     */
    public function setPersonalData(PersonalContainer $personalData)
    {
        $this->personalData = $personalData;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    public function getPersonalData()
    {
        return $this->personalData;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer $delivery
     *
     * @return void
     */
    public function setShippingData(ShippingContainer $delivery)
    {
        $this->shippingData = $delivery;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer
     */
    public function getShippingData()
    {
        return $this->shippingData;
    }

    /**
     * @param PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     *
     * @return void
     */
    public function setPaymentMethod(AbstractPaymentMethodContainer $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return PaymentMethod\AbstractPaymentMethodContainer
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer $secure
     *
     * @return void
     */
    public function set3dsecure(ThreeDSecureContainer $secure)
    {
        $this->_3dsecure = $secure;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer
     */
    public function get3dsecure()
    {
        return $this->_3dsecure;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing)
    {
        $this->invoicing = $invoicing;
    }

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    public function getInvoicing()
    {
        return $this->invoicing;
    }

    /**
     * @return string
     */
    public function getOnlinebanktransfertype()
    {
        return $this->onlinebanktransfertype;
    }

    /**
     * @param string $onlinebanktransfertype
     *
     * @return void
     */
    public function setOnlinebanktransfertype($onlinebanktransfertype)
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

    /**
     * @return array
     */
    public function getIt(): array
    {
        return $this->it;
    }

    /**
     * @param array $it
     */
    public function setIt(array $it): void
    {
        $this->it = $it;
    }

    /**
     * @return array
     */
    public function getId(): array
    {
        return $this->id;
    }

    /**
     * @param array $id
     */
    public function setId(array $id): void
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    public function getPr(): array
    {
        return $this->pr;
    }

    /**
     * @param array $pr
     */
    public function setPr(array $pr): void
    {
        $this->pr = $pr;
    }

    /**
     * @return array
     */
    public function getNo(): array
    {
        return $this->no;
    }

    /**
     * @param array $no
     */
    public function setNo(array $no): void
    {
        $this->no = $no;
    }

    /**
     * @return array
     */
    public function getDe(): array
    {
        return $this->de;
    }

    /**
     * @param array $de
     */
    public function setDe(array $de): void
    {
        $this->de = $de;
    }

    /**
     * @return array
     */
    public function getVa(): array
    {
        return $this->va;
    }

    /**
     * @param array $va
     */
    public function setVa(array $va): void
    {
        $this->va = $va;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
