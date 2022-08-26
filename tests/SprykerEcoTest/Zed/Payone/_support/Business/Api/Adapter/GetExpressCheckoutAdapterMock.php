<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Adapter;

class GetExpressCheckoutAdapterMock extends AbstractAdapterMock
{
    /**
     * @return array
     */
    protected function getSuccessResponse(): array
    {
        $result = 'status=OK' .
            ' add_paydata[shipping_street]=sdfsfas' .
            ' add_paydata[shipping_zip]=12312' .
            ' add_paydata[shipping_city]=sdfasdf' .
            ' add_paydata[shipping_firstname]=sfasdf' .
            ' add_paydata[shipping_country]=DE' .
            ' add_paydata[shipping_lastname]=sdfasd' .
            ' add_paydata[email]=sergey.sikachev-facilitator@spryker.com' .
            ' redirecturl=https://spryker.com' .
            ' customermessage=CustomerMessage' .
            ' errormessage=ErrorMessage' .
            ' errorcode=200' .
            ' shippingcompany=ShippingCompany' .
            ' shippingstate=ShippingState' .
            ' shippingaddressaddition=ShippingAddressAddition' .
            ' workorderid=WX1A1SE57Y8D1XNR';

        return explode(' ', $result);
    }

    /**
     * @return array
     */
    protected function getFailureResponse(): array
    {
        $result = 'status=ERROR' .
            ' errorcode=1011' .
            ' redirecturl=https://spryker.com' .
            ' workorderid=WorkOrderId' .
            ' email=example@spryker.com' .
            ' shippingfirstname=FirstName' .
            ' shippinglastname=LastName' .
            ' shippingcompany=ShippingCompany' .
            ' shippingstate=ShippingState' .
            ' shippingcountry=ShippingCountry' .
            ' shippingcity=ShippingCity' .
            ' shippingstreet=ShippingStreet' .
            ' shippingzip=123456' .
            ' shippingaddressaddition=ShippingAddressAddition' .
            ' errormessage=Parameter {workorderid} incorrect or missing' .
            ' customermessage=An error occured while processing this transaction (wrong parameters).';

        return explode(' ', $result);
    }
}
