<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEcoTest\Zed\Payone\Business\RiskCheck\RiskCheckManagerTest;

class ConsumerScoreAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    protected function getSuccessResponse(): array
    {
        $result = 'status=' . PayoneApiConstants::RESPONSE_TYPE_VALID .
            ' score=' . PayoneApiConstants::CONSUMER_SCORE_GREEN;

        return explode(' ', $result);
    }

    /**
     * @return array
     */
    protected function getFailureResponse(): array
    {
        $result = 'status=' . PayoneApiConstants::RESPONSE_TYPE_INVALID .
            ' errorcode=500' .
            ' customer_message=' . RiskCheckManagerTest::RESPONSE_VALUE_CUSTOMER_MESSAGE;

        return explode(' ', $result);
    }
}
