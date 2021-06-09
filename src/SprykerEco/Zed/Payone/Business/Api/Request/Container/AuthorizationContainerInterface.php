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

interface AuthorizationContainerInterface
{
    /**
     * @param string $narrative_text
     *
     * @return void
     */
    public function setNarrativeText($narrative_text);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer
     */
    public function getInvoicing();

    /**
     * @return string
     */
    public function getNarrativeText();

    /**
     * @return string|null
     */
    public function getPortalid(): ?string;

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer $personalData
     *
     * @return void
     */
    public function setPersonalData(PersonalContainer $personalData);

    /**
     * @return string
     */
    public function getParam();

    /**
     * @return string
     */
    public function getRequest();

    /**
     * @param string $currency
     *
     * @return void
     */
    public function setCurrency($currency);

    /**
     * set the system-Name
     *
     * @param string $integratorName
     *
     * @return $this
     */
    public function setIntegratorName(string $integratorName);

    /**
     * @return string
     */
    public function getIntegratorName();

    /**
     * @return string
     */
    public function getCurrency();

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
    public function toArray();

    /**
     * @return string
     */
    public function getReference();

    /**
     * @return string
     */
    public function getSolutionVersion();

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer
     */
    public function get3dsecure();

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
    public function setShippingData(ShippingContainer $delivery);

    /**
     * @return string
     */
    public function getEncoding();

    /**
     * @return string
     */
    public function getSolutionName();

    /**
     * @param string $param
     *
     * @return void
     */
    public function setParam($param);

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param string $encoding
     *
     * @return $this
     */
    public function setEncoding(?string $encoding);

    /**
     * @param string $api_version
     *
     * @return $this
     */
    public function setApiVersion(?string $api_version);

    /**
     * @param string $clearingType
     *
     * @return void
     */
    public function setClearingType($clearingType);

    /**
     * @param string $reference
     *
     * @return void
     */
    public function setReference($reference);

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer
     */
    public function getPersonalData();

    /**
     * @return string
     */
    public function getClearingType();

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey(string $key);

    /**
     * @return string
     */
    public function getIntegratorVersion();

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer $paymentMethod
     *
     * @return void
     */
    public function setPaymentMethod(AbstractPaymentMethodContainer $paymentMethod);

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer
     */
    public function getPaymentMethod();

    /**
     * @return string|null
     */
    public function getMid(): ?string;

    /**
     * @param string $mid
     *
     * @return $this
     */
    public function setMid(?string $mid);

    /**
     * @return string
     */
    public function getMode();

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer $secure
     *
     * @return void
     */
    public function set3dsecure(ThreeDSecureContainer $secure);

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
     * @param string $aid
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
    public function __toString();

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer $invoicing
     *
     * @return void
     */
    public function setInvoicing(TransactionContainer $invoicing);

    /**
     * @return string
     */
    public function getApiVersion();

    /**
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer
     */
    public function getShippingData();

    /**
     * @return string|null
     */
    public function getAid(): ?string;

    /**
     * @param int $amount
     *
     * @return void
     */
    public function setAmount($amount);
}
