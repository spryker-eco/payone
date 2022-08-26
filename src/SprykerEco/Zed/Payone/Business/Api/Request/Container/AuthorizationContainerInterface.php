<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;

interface AuthorizationContainerInterface extends RequestContainerInterface
{
    /**
     * @param string $narrativeText
     *
     * @return void
     */
    public function setNarrativeText(string $narrativeText): void;

    /**
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer|null
     */
    public function getInvoicing(): ?TransactionContainer;

    /**
     * @return string|null
     */
    public function getNarrativeText(): ?string;

    /**
     * @return string|null
     */
    public function getPortalid(): ?string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer $personalData
     *
     * @return void
     */
    public function setPersonalData(PersonalContainer $personalData): void;

    /**
     * @return string|null
     */
    public function getParam(): ?string;

    /**
     * @return string|null
     */
    public function getRequest(): ?string;

    /**
     * @param string $currency
     *
     * @return void
     */
    public function setCurrency(string $currency): void;

    /**
     * set the system-Name
     *
     * @param string $integratorName
     *
     * @return $this
     */
    public function setIntegratorName(string $integratorName);

    /**
     * @return string|null
     */
    public function getIntegratorName(): ?string;

    /**
     * @return string|null
     */
    public function getCurrency(): ?string;

    /**
     * set the version of the solution-partner's app / extension / plugin / etc..
     *
     * @param string $solutionVersion
     *
     * @return $this
     */
    public function setSolutionVersion(string $solutionVersion);

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return string|null
     */
    public function getReference(): ?string;

    /**
     * @return string|null
     */
    public function getSolutionVersion(): ?string;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer|null
     */
    public function get3dsecure(): ?ThreeDSecureContainer;

    /**
     * @param string|null $portalid
     *
     * @return $this
     */
    public function setPortalid(?string $portalid);

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer $delivery
     *
     * @return void
     */
    public function setShippingData(ShippingContainer $delivery): void;

    /**
     * @return string|null
     */
    public function getEncoding(): ?string;

    /**
     * @return string|null
     */
    public function getSolutionName(): ?string;

    /**
     * @param string $param
     *
     * @return void
     */
    public function setParam(string $param): void;

    /**
     * @return int|null
     */
    public function getAmount(): ?int;

    /**
     * @param string|null $encoding
     *
     * @return $this
     */
    public function setEncoding(?string $encoding);

    /**
     * @param string|null $api_version
     *
     * @return $this
     */
    public function setApiVersion(?string $api_version);

    /**
     * @param string $clearingType
     *
     * @return void
     */
    public function setClearingType(string $clearingType): void;

    /**
     * @param string $reference
     *
     * @return void
     */
    public function setReference(string $reference): void;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer|null
     */
    public function getPersonalData(): ?PersonalContainer;

    /**
     * @return string|null
     */
    public function getClearingType(): ?string;

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey(string $key);

    /**
     * @return string|null
     */
    public function getIntegratorVersion(): ?string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     *
     * @return void
     */
    public function setPaymentMethod(AbstractPaymentMethodContainer $paymentMethod): void;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer|null
     */
    public function getPaymentMethod(): ?AbstractPaymentMethodContainer;

    /**
     * @return string|null
     */
    public function getMid(): ?string;

    /**
     * @param string|null $mid
     *
     * @return $this
     */
    public function setMid(?string $mid);

    /**
     * @return string|null
     */
    public function getMode(): ?string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer $secure
     *
     * @return void
     */
    public function set3dsecure(ThreeDSecureContainer $secure): void;

    /**
     * set the name of the solution-partner (company)
     *
     * @param string $solution_name
     *
     * @return $this
     */
    public function setSolutionName(string $solution_name);

    /**
     * @param string $request
     *
     * @return $this
     */
    public function setRequest(string $request);

    /**
     * @param string|null $aid
     *
     * @return $this
     */
    public function setAid(?string $aid);

    /**
     * @param string $mode
     *
     * @return $this
     */
    public function setMode(string $mode);

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing): void;

    /**
     * @return string|null
     */
    public function getApiVersion(): ?string;

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer|null
     */
    public function getShippingData(): ?ShippingContainer;

    /**
     * @return string|null
     */
    public function getAid(): ?string;

    /**
     * @param int|null $amount
     *
     * @return void
     */
    public function setAmount(?int $amount = null): void;

    /**
     * @param string $businessRelation
     *
     * @return void
     */
    public function setBusinessRelation(string $businessRelation): void;

    /**
     * @param string $clearingSubType
     *
     * @return void
     */
    public function setClearingSubType(string $clearingSubType): void;
}
