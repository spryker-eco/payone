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

        $responseContainer = $this->riskCheckFactory->createAddressCheckResponseContainer();
        $responseContainer->init($response);

        $addressCheckResponseTransfer = new AddressCheckResponseTransfer();
        $addressCheckResponseTransfer->setStatus($responseContainer->getStatus());
        $addressCheckResponseTransfer->setStreetName($responseContainer->getStreetName());
        $addressCheckResponseTransfer->setStreetNumber($responseContainer->getStreetNumber());
        $addressCheckResponseTransfer->setZip($responseContainer->getZip());
        $addressCheckResponseTransfer->setCity($responseContainer->getCity());
        $addressCheckResponseTransfer->setSecstatus($responseContainer->getSecstatus());
        $addressCheckResponseTransfer->setPersonstatus($responseContainer->getPersonstatus());

        if (!is_null($responseContainer->getCustomermessage())) {
            $addressCheckResponseTransfer->setCustomerMessage($responseContainer->getCustomermessage());
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

        $responseContainer = $this->riskCheckFactory->createConsumerScoreResponseContainer();
        $responseContainer->init($response);

        $consumerScoreResponseTransfer = new ConsumerScoreResponseTransfer();
        $consumerScoreResponseTransfer->setStatus($responseContainer->getStatus());

        if (!$responseContainer->isError()) {
            $consumerScoreResponseTransfer->setScore($responseContainer->getScore());
            $consumerScoreResponseTransfer->setScorevalue($responseContainer->getScorevalue());

            return $consumerScoreResponseTransfer;
        }

        $consumerScoreResponseTransfer->setCustomerMessage($responseContainer->getCustomermessage());

        return $consumerScoreResponseTransfer;
    }
}
