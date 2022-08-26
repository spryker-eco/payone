<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

class CreditCardCheckAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    protected function getSuccessResponse(): array
    {
        $result = 'pseudoCardPan=1111111111111111 status=OK errormessage=OK customermessage=OK errorcode=200 rawresponse=200 truncatedcardpan=200';

        return explode(' ', $result);
    }

    /**
     * @return array
     */
    protected function getFailureResponse(): array
    {
        $result = 'status=ERROR';

        return explode(' ', $result);
    }
}
