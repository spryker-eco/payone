<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Response\Container;

use PHPUnit_Framework_TestCase;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\DebitResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Api
 * @group Response
 * @group Container
 * @group ResponseContainerTest
 */
class ResponseContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $city = 'Berlin';

    /**
     * @var string
     */
    protected $street = 'street';

    /**
     * @var string
     */
    protected $streetNumber = '21';

    /**
     * @var string
     */
    protected $zip = '21212';

    /**
     * @var string
     */
    protected $score = 'G';

    /**
     * @var string
     */
    protected $status = 'valid';

    /**
     * @var string
     */
    protected $customerMessage = 'customermessage';

    /**
     * @return void
     */
    public function testAuthorizationResponseContainer(): void
    {
        $params = array_merge($this->getStandardResponseParams(), $this->getAuthorizationResponseParams());
        $container = new AuthorizationResponseContainer($params);

        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer', $container);
        $this->assertStandardParams($container);
        $this->assertEquals('clearingamount', $container->getClearingAmount());
        $this->assertEquals('clearingbankaccount', $container->getClearingBankaccount());
        $this->assertEquals('clearingbankaccountholder', $container->getClearingBankaccountholder());
        $this->assertEquals('clearingbankbic', $container->getClearingBankbic());
        $this->assertEquals('clearingbankcity', $container->getClearingBankcity());
        $this->assertEquals('clearingbankcode', $container->getClearingBankcode());
        $this->assertEquals('clearingbankcountry', $container->getClearingBankcountry());
        $this->assertEquals('clearingbankiban', $container->getClearingBankiban());
        $this->assertEquals('clearingbankname', $container->getClearingBankname());
        $this->assertEquals('clearingdate', $container->getClearingDate());
        $this->assertEquals('creditorcity', $container->getCreditorCity());
        $this->assertEquals('creditorcountry', $container->getCreditorCountry());
        $this->assertEquals('creditoremail', $container->getCreditorEmail());
        $this->assertEquals('creditoridentifier', $container->getCreditorIdentifier());
        $this->assertEquals('creditorname', $container->getCreditorName());
        $this->assertEquals('creditorstreet', $container->getCreditorStreet());
        $this->assertEquals('creditorzip', $container->getCreditorZip());
    }

    /**
     * @return array
     */
    protected function getAuthorizationResponseParams(): array
    {
        return [
            'clearingamount' => 'clearingamount',
            'clearingbankaccount' => 'clearingbankaccount',
            'clearingbankaccountholder' => 'clearingbankaccountholder',
            'clearingbankbic' => 'clearingbankbic',
            'clearingbankcity' => 'clearingbankcity',
            'clearingbankcode' => 'clearingbankcode',
            'clearingbankcountry' => 'clearingbankcountry',
            'clearingbankiban' => 'clearingbankiban',
            'clearingbankname' => 'clearingbankname',
            'clearingdate' => 'clearingdate',

            'creditorcity' => 'creditorcity',
            'creditorcountry' => 'creditorcountry',
            'creditoremail' => 'creditoremail',
            'creditoridentifier' => 'creditoridentifier',
            'creditorname' => 'creditorname',
            'creditorstreet' => 'creditorstreet',
            'creditorzip' => 'creditorzip',
        ];
    }

    /**
     * @return void
     */
    public function testCaptureResponseContainer(): void
    {
        $params = array_merge($this->getStandardResponseParams(), $this->getCaptureResponseParams());
        $container = new CaptureResponseContainer($params);

        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer', $container);
        $this->assertStandardParams($container);
        $this->assertEquals('clearingamount', $container->getClearingAmount());
        $this->assertEquals('clearingbankaccount', $container->getClearingBankaccount());
        $this->assertEquals('clearingbankaccountholder', $container->getClearingBankaccountholder());
        $this->assertEquals('clearingbankbic', $container->getClearingBankbic());
        $this->assertEquals('clearingbankcity', $container->getClearingBankcity());
        $this->assertEquals('clearingbankcode', $container->getClearingBankcode());
        $this->assertEquals('clearingbankcountry', $container->getClearingBankcountry());
        $this->assertEquals('clearingbankiban', $container->getClearingBankiban());
        $this->assertEquals('clearingbankname', $container->getClearingBankname());
        $this->assertEquals('clearingdate', $container->getClearingDate());
        $this->assertEquals('clearingduedate', $container->getClearingDuedate());
        $this->assertEquals('clearinginstructionnote', $container->getClearingInstructionnote());
        $this->assertEquals('clearinglegalnote', $container->getClearingLegalnote());
        $this->assertEquals('clearingreference', $container->getClearingReference());

        $this->assertEquals('creditorcity', $container->getCreditorCity());
        $this->assertEquals('creditorcountry', $container->getCreditorCountry());
        $this->assertEquals('creditoremail', $container->getCreditorEmail());
        $this->assertEquals('creditoridentifier', $container->getCreditorIdentifier());
        $this->assertEquals('creditorname', $container->getCreditorName());
        $this->assertEquals('creditorstreet', $container->getCreditorStreet());
        $this->assertEquals('creditorzip', $container->getCreditorZip());

        $this->assertEquals('mandateidentification', $container->getMandateIdentification());
        $this->assertEquals('settleaccount', $container->getSettleaccount());
        $this->assertSame(10, $container->getTxid());
    }

    /**
     * @return array
     */
    protected function getCaptureResponseParams(): array
    {
        return [
            'clearingamount' => 'clearingamount',
            'clearingbankaccount' => 'clearingbankaccount',
            'clearingbankaccountholder' => 'clearingbankaccountholder',
            'clearingbankbic' => 'clearingbankbic',
            'clearingbankcity' => 'clearingbankcity',
            'clearingbankcode' => 'clearingbankcode',
            'clearingbankcountry' => 'clearingbankcountry',
            'clearingbankiban' => 'clearingbankiban',
            'clearingbankname' => 'clearingbankname',
            'clearingdate' => 'clearingdate',
            'clearingduedate' => 'clearingduedate',
            'clearinginstructionnote' => 'clearinginstructionnote',
            'clearinglegalnote' => 'clearinglegalnote',
            'clearingreference' => 'clearingreference',

            'creditorcity' => 'creditorcity',
            'creditorcountry' => 'creditorcountry',
            'creditoremail' => 'creditoremail',
            'creditoridentifier' => 'creditoridentifier',
            'creditorname' => 'creditorname',
            'creditorstreet' => 'creditorstreet',
            'creditorzip' => 'creditorzip',

            'mandateidentification' => 'mandateidentification',
            'settleaccount' => 'settleaccount',
            'txid' => 10,
        ];
    }

    /**
     * @return void
     */
    public function testDebitResponseContainer(): void
    {
        $params = array_merge($this->getStandardResponseParams(), $this->getDebitResponseParams());

        $container = new DebitResponseContainer($params);

        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer', $container);
        $this->assertStandardParams($container);
        $this->assertEquals('settleaccount', $container->getSettleaccount());
        $this->assertSame(5, $container->getTxid());
    }

    /**
     * @return array
     */
    protected function getDebitResponseParams(): array
    {
        return [
            'settleaccount' => 'settleaccount',
            'txid' => 5,
        ];
    }

    /**
     * @return void
     */
    public function testRefundResponseContainer(): void
    {
        $params = array_merge($this->getStandardResponseParams(), $this->getRefundResponseParams());

        $container = new RefundResponseContainer($params);

        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer', $container);
        $this->assertStandardParams($container);
        $this->assertEquals('protectresultavs', $container->getProtectResultAvs());
        $this->assertSame(5, $container->getTxid());
    }

    /**
     * @return array
     */
    protected function getRefundResponseParams(): array
    {
        return [
            'protectresultavs' => 'protectresultavs',
            'txid' => 5,
        ];
    }

    /**
     * @return array
     */
    protected function getStandardResponseParams(): array
    {
        $params = [
            'customermessage' => 'customermessage',
            'errorcode' => 'errorcode',
            'errormessage' => 'errormessage',
            'rawResponse' => 'rawresponse',
            'status' => 'status',
        ];

        return $params;
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Response\Container\AbstractResponseContainer $container
     *
     * @return void
     */
    protected function assertStandardParams(AbstractResponseContainer $container): void
    {
        $this->assertEquals('customermessage', $container->getCustomerMessage());
        $this->assertEquals('errorcode', $container->getErrorcode());
        $this->assertEquals('errormessage', $container->getErrormessage());
        $this->assertEquals('rawresponse', $container->getRawResponse());
        $this->assertEquals('status', $container->getStatus());
    }
}
