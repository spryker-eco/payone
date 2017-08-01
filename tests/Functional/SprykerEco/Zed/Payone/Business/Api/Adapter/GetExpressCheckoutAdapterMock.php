<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Payone\Business\Api\Adapter;

class GetExpressCheckoutAdapterMock extends AbstractAdapterMock
{

    /**
     * @return array
     */
    protected function getSuccessResponse()
    {
        $result = 'status=OK' .
            ' add_paydata[shipping_street]=sdfsfas' .
            ' add_paydata[shipping_zip]=12312' .
            ' add_paydata[shipping_city]=sdfasdf' .
            ' add_paydata[shipping_firstname]=sfasdf' .
            ' add_paydata[shipping_country]=DE' .
            ' add_paydata[shipping_lastname]=sdfasd' .
            ' add_paydata[email]=sergey.sikachev-facilitator@spryker.com' .
            ' workorderid=WX1A1SE57Y8D1XNR';
        return explode(" ", $result);
    }

    /**
     * @return array
     */
    protected function getFailureResponse()
    {
        $result = 'status=ERROR' .
            ' errorcode=1011' .
            ' errormessage=Parameter {workorderid} incorrect or missing' .
            ' customermessage=An error occured while processing this transaction (wrong parameters).';

        return explode(" ", $result);
    }

}
