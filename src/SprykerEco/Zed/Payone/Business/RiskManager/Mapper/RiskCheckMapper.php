<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\RiskManager\Mapper;

use Generated\Shared\Transfer\PayoneStandardParameterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface;
use SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface;

class RiskCheckMapper implements RiskCheckMapperInterface
{
    public const BASIC_ADDRESS_CHECK_KEY = 'BA';
    public const BASIC_CONSUMER_SCORE_KEY = 'IH';

    /**
     * @var \SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface
     */
    protected $riskCheckFactory;

    /**
     * @var \Generated\Shared\Transfer\PayoneStandardParameterTransfer
     */
    protected $standardParameters;

    /**
     * @var \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface
     */
    protected $modeDetector;

    /**
     * @param \SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface $riskCheckFactory
     * @param \Generated\Shared\Transfer\PayoneStandardParameterTransfer $standardParameters
     * @param \SprykerEco\Shared\Payone\Dependency\ModeDetectorInterface $modeDetector
     */
    public function __construct(
        RiskCheckFactoryInterface $riskCheckFactory,
        PayoneStandardParameterTransfer $standardParameters,
        ModeDetectorInterface $modeDetector
    ) {
        $this->riskCheckFactory = $riskCheckFactory;
        $this->standardParameters = $standardParameters;
        $this->modeDetector = $modeDetector;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    public function mapAddressCheck(QuoteTransfer $quoteTransfer): ContainerInterface
    {
        /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\AddressCheckContainer $container */
        $container = $this->riskCheckFactory->createAddressCheckContainer();
        $container = $this->mapDefaultContainerParams($container);

        $container->setFirstName($quoteTransfer->getCustomer()->getFirstName());
        $container->setLastName($quoteTransfer->getCustomer()->getLastName());
        $container->setCompany($quoteTransfer->getCustomer()->getCompany());
        $container->setCountry($quoteTransfer->getBillingAddress()->getIso2Code());
        $container->setStreetName($quoteTransfer->getBillingAddress()->getAddress1());
        $container->setStreetNumber($quoteTransfer->getBillingAddress()->getAddress2());
        $container->setZip($quoteTransfer->getBillingAddress()->getZipCode());
        $container->setCity($quoteTransfer->getBillingAddress()->getCity());
        $container->setAddressCheckType(static::BASIC_ADDRESS_CHECK_KEY);

        return $container;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    public function mapConsumerScoreCheck(QuoteTransfer $quoteTransfer): ContainerInterface
    {
        /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\ConsumerScoreContainer $container */
        $container = $this->riskCheckFactory->createConsumerScoreContainer();
        $container = $this->mapDefaultContainerParams($container);

        $container->setFirstName($quoteTransfer->getCustomer()->getFirstName());
        $container->setLastName($quoteTransfer->getCustomer()->getLastName());
        $container->setCompany($quoteTransfer->getCustomer()->getCompany());
        $container->setCountry($quoteTransfer->getBillingAddress()->getIso2Code());
        $container->setStreetName($quoteTransfer->getBillingAddress()->getAddress1());
        $container->setStreetNumber($quoteTransfer->getBillingAddress()->getAddress2());
        $container->setZip($quoteTransfer->getBillingAddress()->getZipCode());
        $container->setCity($quoteTransfer->getBillingAddress()->getCity());
        $container->setAddressCheckType(static::BASIC_ADDRESS_CHECK_KEY);
        $container->setConsumerScoreType(static::BASIC_CONSUMER_SCORE_KEY);

        return $container;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface
     */
    protected function mapDefaultContainerParams(ContainerInterface $container): ContainerInterface
    {
        /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container */
        $container->setMid($this->standardParameters->getMid());
        $container->setAid($this->standardParameters->getAid());
        $container->setPortalid($this->standardParameters->getPortalId());
        $container->setKey(md5($this->standardParameters->getKey()));
        $container->setMode($this->modeDetector->getMode());
        $container->setIntegratorName(PayoneApiConstants::INTEGRATOR_NAME_SPRYKER);
        $container->setIntegratorVersion(PayoneApiConstants::INTEGRATOR_VERSION_3_0_0);
        $container->setSolutionName(PayoneApiConstants::SOLUTION_NAME_SPRYKER);
        $container->setSolutionVersion(PayoneApiConstants::SOLUTION_VERSION_3_0_0);

        return $container;
    }
}
