<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEcoTest\Zed\Payone\Business\RiskCheck\RiskCheckManagerTest;

class AddressCheckAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    protected function getSuccessResponse(): array
    {
        $result = 'status=' . PayoneApiConstants::RESPONSE_TYPE_VALID .
            ' street_name=' . RiskCheckManagerTest::RESPONSE_VALUE_STREET_NAME .
            ' street_number=' . RiskCheckManagerTest::RESPONSE_VALUE_STREET_NUMBER .
            ' zip=' . RiskCheckManagerTest::RESPONSE_VALUE_ZIP .
            ' city=' . RiskCheckManagerTest::RESPONSE_VALUE_CITY .
            ' person_status=' . RiskCheckManagerTest::ADDRESS_CHECK_PERSON_STATUS_CORRECT .
            ' customermessage=' . RiskCheckManagerTest::RESPONSE_VALUE_CUSTOMER_MESSAGE .
            ' secstatus=' . PayoneApiConstants::ADDRESS_CHECK_SECSTATUS_CORRECT;

        return explode(' ', $result);
    }

    /**
     * @return array
     */
    protected function getFailureResponse(): array
    {
        $result = 'status=' . PayoneApiConstants::RESPONSE_TYPE_INVALID .
            ' street_name=' . RiskCheckManagerTest::RESPONSE_VALUE_STREET_NAME .
            ' street_number=' . RiskCheckManagerTest::RESPONSE_VALUE_STREET_NUMBER .
            ' zip=' . RiskCheckManagerTest::RESPONSE_VALUE_ZIP .
            ' city=' . RiskCheckManagerTest::RESPONSE_VALUE_CITY .
            ' person_status=' . RiskCheckManagerTest::ADDRESS_CHECK_PERSON_STATUS_CORRECT .
            ' customermessage=' . RiskCheckManagerTest::RESPONSE_VALUE_CUSTOMER_MESSAGE .
            ' secstatus=' . PayoneApiConstants::ADDRESS_CHECK_SECSTATUS_NONE_CORRECTABLE;

        return explode(' ', $result);
    }
}
