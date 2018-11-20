<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\RiskCheck;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEcoTest\Zed\Payone\Business\AbstractPayoneTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\AddressCheckAdapterMock;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\ConsumerScoreAdapterMock;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group RiskManager
 * @group RiskCheckManagerTest
 */
class RiskCheckManagerTest extends AbstractPayoneTest
{
    public const RESPONSE_VALUE_STREET_NAME = 'street_name';
    public const RESPONSE_VALUE_STREET_NUMBER = 'street_number';
    public const RESPONSE_VALUE_ZIP = '12345';
    public const RESPONSE_VALUE_CITY = 'Berlin';
    public const RESPONSE_VALUE_CUSTOMER_MESSAGE = 'CustomerMessage';

    /**
     * @return void
     */
    public function testSendAddressCheckSuccessRequest(): void
    {
        $adapter = new AddressCheckAdapterMock();

        $facade = $this->getFacadeMock($adapter);
        $responseTransfer = $facade->sendAddressCheckRequest($this->quoteTransfer);

        $this->assertEquals(PayoneApiConstants::ADDRESS_CHECK_SECSTATUS_CORRECT, $responseTransfer->getSecstatus());
        $this->assertEquals(PayoneApiConstants::RESPONSE_TYPE_VALID, $responseTransfer->getStatus());
        $this->assertEquals(static::RESPONSE_VALUE_CITY, $responseTransfer->getCity());
        $this->assertEquals(static::RESPONSE_VALUE_ZIP, $responseTransfer->getZip());
        $this->assertEquals(static::RESPONSE_VALUE_STREET_NUMBER, $responseTransfer->getStreetNumber());
        $this->assertEquals(static::RESPONSE_VALUE_STREET_NAME, $responseTransfer->getStreetName());
    }

    /**
     * @return void
     */
    public function testSendAddressCheckFailureRequest(): void
    {
        $adapter = new AddressCheckAdapterMock();
        $adapter->setExpectSuccess(false);

        $facade = $this->getFacadeMock($adapter);
        $responseTransfer = $facade->sendAddressCheckRequest($this->quoteTransfer);

        $this->assertEquals(PayoneApiConstants::ADDRESS_CHECK_SECSTATUS_NONE_CORRECTABLE, $responseTransfer->getSecstatus());
        $this->assertEquals(PayoneApiConstants::RESPONSE_TYPE_INVALID, $responseTransfer->getStatus());
        $this->assertEquals(static::RESPONSE_VALUE_CITY, $responseTransfer->getCity());
        $this->assertEquals(static::RESPONSE_VALUE_ZIP, $responseTransfer->getZip());
        $this->assertEquals(static::RESPONSE_VALUE_STREET_NUMBER, $responseTransfer->getStreetNumber());
        $this->assertEquals(static::RESPONSE_VALUE_STREET_NAME, $responseTransfer->getStreetName());
    }

    /**
     * @return void
     */
    public function testConsumerScoreSuccessRequest(): void
    {
        $adapter = new ConsumerScoreAdapterMock();

        $facade = $this->getFacadeMock($adapter);
        $responseTransfer = $facade->sendConsumerScoreRequest($this->quoteTransfer);

        $this->assertEquals(PayoneApiConstants::RESPONSE_TYPE_VALID, $responseTransfer->getStatus());
        $this->assertEquals(PayoneApiConstants::CONSUMER_SCORE_GREEN, $responseTransfer->getScore());
        $this->assertEquals('', $responseTransfer->getCustomerMessage());
    }

    /**
     * @return void
     */
    public function testConsumerScoreFailureRequest(): void
    {
        $adapter = new ConsumerScoreAdapterMock();
        $adapter->setExpectSuccess(false);

        $facade = $this->getFacadeMock($adapter);
        $responseTransfer = $facade->sendConsumerScoreRequest($this->quoteTransfer);

        $this->assertEquals(PayoneApiConstants::RESPONSE_TYPE_INVALID, $responseTransfer->getStatus());
        $this->assertEquals(static::RESPONSE_VALUE_CUSTOMER_MESSAGE, $responseTransfer->getCustomerMessage());
    }
}
