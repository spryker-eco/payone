<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\RiskManager;

use Generated\Shared\Transfer\AddressCheckResponseTransfer;
use Generated\Shared\Transfer\ConsumerScoreResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface;
use SprykerEco\Zed\Payone\Business\RiskManager\Mapper\RiskCheckMapperInterface;

class RiskCheckManager implements RiskCheckManagerInterface
{
    /**
     * @var \SprykerEco\Zed\Payone\Business\RiskManager\Mapper\RiskCheckMapperInterface
     */
    protected $riskCheckMapper;

    /**
     * @var \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var \SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface
     */
    protected $riskCheckFactory;

    /**
     * @param \SprykerEco\Zed\Payone\Business\RiskManager\Mapper\RiskCheckMapperInterface $riskCheckMapper
     * @param \SprykerEco\Zed\Payone\Business\Api\Adapter\AdapterInterface $executionAdapter
     * @param \SprykerEco\Zed\Payone\Business\RiskManager\Factory\RiskCheckFactoryInterface $riskCheckFactory
     */
    public function __construct(RiskCheckMapperInterface $riskCheckMapper, AdapterInterface $executionAdapter, RiskCheckFactoryInterface $riskCheckFactory)
    {
        $this->riskCheckMapper = $riskCheckMapper;
        $this->executionAdapter = $executionAdapter;
        $this->riskCheckFactory = $riskCheckFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressCheckResponseTransfer
     */
    public function sendAddressCheckRequest(QuoteTransfer $quoteTransfer): AddressCheckResponseTransfer
    {
        $requestContainer = $this->riskCheckMapper->mapAddressCheck($quoteTransfer);
        $response = $this->executionAdapter->sendRequest($requestContainer);

        /** @var \SprykerEco\Zed\Payone\Business\Api\Response\Container\AddressCheckResponseContainer $responseContainer */
        $responseContainer = $this->riskCheckFactory->createAddressCheckResponseContainer();
        $responseContainer->init($response);

        $addressCheckResponseTransfer = new AddressCheckResponseTransfer();
        $addressCheckResponseTransfer->setStatus($responseContainer->getStatus());
        $addressCheckResponseTransfer->setStreetName($responseContainer->getStreetName());
        $addressCheckResponseTransfer->setStreetNumber($responseContainer->getStreetNumber());
        $addressCheckResponseTransfer->setZip($responseContainer->getZip());
        $addressCheckResponseTransfer->setCity($responseContainer->getCity());
        if ($responseContainer->getSecstatus() !== null) {
            $addressCheckResponseTransfer->setSecStatus((string)$responseContainer->getSecstatus());
        }
        $addressCheckResponseTransfer->setPersonStatus($responseContainer->getPersonstatus());

        if ($responseContainer->getCustomerMessage() !== null) {
            $addressCheckResponseTransfer->setCustomerMessage($responseContainer->getCustomerMessage());
        }

        return $addressCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ConsumerScoreResponseTransfer
     */
    public function sendConsumerScoreRequest(QuoteTransfer $quoteTransfer): ConsumerScoreResponseTransfer
    {
        $requestContainer = $this->riskCheckMapper->mapConsumerScoreCheck($quoteTransfer);

        $response = $this->executionAdapter->sendRequest($requestContainer);

        /** @var \SprykerEco\Zed\Payone\Business\Api\Response\Container\ConsumerScoreResponseContainer $responseContainer */
        $responseContainer = $this->riskCheckFactory->createConsumerScoreResponseContainer();
        $responseContainer->init($response);

        $consumerScoreResponseTransfer = new ConsumerScoreResponseTransfer();
        $consumerScoreResponseTransfer->setStatus($responseContainer->getStatus());

        if (!$responseContainer->isError()) {
            $consumerScoreResponseTransfer->setScore($responseContainer->getScore());
            $consumerScoreResponseTransfer->setScoreValue((string)$responseContainer->getScorevalue());

            return $consumerScoreResponseTransfer;
        }

        $consumerScoreResponseTransfer->setCustomerMessage($responseContainer->getCustomerMessage());

        return $consumerScoreResponseTransfer;
    }
}
