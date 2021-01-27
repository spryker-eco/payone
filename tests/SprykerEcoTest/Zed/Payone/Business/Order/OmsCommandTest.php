<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\AuthorizationResponseTransfer;
use Generated\Shared\Transfer\CaptureResponseTransfer;
use Generated\Shared\Transfer\PayoneCaptureTransfer;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributor;
use SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface;
use SprykerEco\Zed\Payone\Business\PayoneFacadeInterface;
use SprykerEcoTest\Zed\Payone\Business\AbstractPayoneTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DummyAdapter;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Order
 * @group OmsCommandTest
 */
class OmsCommandTest extends AbstractPayoneTest
{
    public const PREAUTHORIZE_RESPONSE = '{"txid":"213547373","userid":"95374053","redirecturl":"https:\/\/sandbox.paydirekt.de\/checkout\/#\/checkout\/1dbdbdd1-7e0c-401e-9256-8cb096ac08cb","status":"REDIRECT"}';
    public const AUTHORIZE_RESPONSE = '{"txid":"213624820","settleAccount":"settlement account","userid":"95419580","redirecturl":"https:\/\/www.sofort-ueberweisung.de\/payment\/start?user_id=10910&project_id=55780&hash=e378b42b538369f18f28f93327612691bf1f8f3b1ca476a0be5bb8b03a93f0f5035f79bdc9ea3416e2404c441247fbb2b48c41ee71e6931b73a76d45a412de98&sender_holder=Max+Musterman&sender_account_number=&sender_bank_code=88888888&sender_country_id=DE&recipient_holder=PAYONE+GmbH&recipient_account_number=0022520120&recipient_bank_code=21070020&recipient_bank_bic=DEUTDEHH210&recipient_iban=DE87210700200022520120&recipient_country_id=DE&amount=78.12&currency_id=EUR&reason_1=213624820&reason_2=&user_variable_0=213624820;95419580&user_variable_1=http%3A%2F%2Fwww.de.payone.local%2Fcheckout%2Fsuccess%3ForderReference%3DDE--3%26sig%3Df4ed226e932f865be02bd338fbdefa0e63ba510764f5259ef2e585129fb0f3f3&user_variable_2=http%3A%2F%2Fwww.de.payone.local%2Fpayone%2Fregular-redirect-payment-cancellation%3ForderReference%3DDE--3%26sig%3Df4ed226e932f865be02bd338fbdefa0e63ba510764f5259ef2e585129fb0f3f3&recipient_name1=Spryker+Systems+GmbH+%28SP%29&recipient_street=testweg+1&recipient_zipcode=12345&recipient_city=test&encoding=utf-8","status":"REDIRECT"}';

    protected const PAYMENT_PROVIDER_PAYONE = 'Payone';
    protected const PAYMENT_PROVIDER_DUMMY = 'Dummy';

    /**
     * @var \SprykerEcoTest\Zed\Payone\PayoneZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPreAuthorizePayment(): void
    {
        $payoneFacade = $this->createFacadeMock(
            new DummyAdapter(static::AUTHORIZE_RESPONSE)
        );

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $authorizationResponseTransfer = $payoneFacade
            ->preAuthorizePayment($this->orderEntity->getIdSalesOrder());
        $this->assertInstanceOf(AuthorizationResponseTransfer::class, $authorizationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testAuthorizePayment(): void
    {
        $payoneFacade = $this->createFacadeMock(
            new DummyAdapter(static::AUTHORIZE_RESPONSE)
        );

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $authorizationResponseTransfer = $payoneFacade->authorizePayment($this->orderTransfer);
        $this->assertInstanceOf(AuthorizationResponseTransfer::class, $authorizationResponseTransfer);
    }

    /**
     * @return void
     */
    public function testCapturePayment(): void
    {
        $orderPriceDistributorMock = $this->createOrderPriceDistributorMock();
        $payoneFacade = $this->createPayoneFacadeWithOrderPriceDistributorMocked($orderPriceDistributorMock);

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_CAPTURE, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $captureTransfer = (new PayoneCaptureTransfer())
            ->setAmount(10)
            ->setPayment((new PayonePaymentTransfer())->fromArray($this->spyPaymentPayone->toArray()))
            ->setSettleaccount('settlement account')
            ->setOrder($this->orderTransfer);
        $orderPriceDistributorMock
            ->expects($this->once())
            ->method('distributeOrderPrice')
            ->with($this->equalTo($this->orderTransfer))
            ->willReturn($this->orderTransfer);

        $captureResponseTransfer = $payoneFacade->capturePayment($captureTransfer);

        $this->assertInstanceOf(CaptureResponseTransfer::class, $captureResponseTransfer);
        $this->assertEquals('213624820', $captureResponseTransfer->getTxid());
        $this->assertEquals('settlement account', $captureResponseTransfer->getSettleaccount());
    }

    /**
     * @return void
     */
    public function testRefundPayment(): void
    {
        $orderPriceDistributorMock = $this->createOrderPriceDistributorMock();
        $payoneFacade = $this->createPayoneFacadeWithOrderPriceDistributorMocked($orderPriceDistributorMock);

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $refundTransfer = (new PayoneRefundTransfer())
            ->setNarrativeText('Narrative Text')
            ->setUseCustomerdata('Use Customer data')
            ->setAmount(10)
            ->setPayment((new PayonePaymentTransfer())->fromArray($this->spyPaymentPayone->toArray()))
            ->setOrder($this->orderTransfer);
        $orderPriceDistributorMock
            ->expects($this->once())
            ->method('distributeOrderPrice')
            ->with($this->equalTo($this->orderTransfer))
            ->willReturn($this->orderTransfer);

        $refundResponseTransfer = $payoneFacade->refundPayment($refundTransfer);

        $this->assertInstanceOf(RefundResponseTransfer::class, $refundResponseTransfer);
        $this->assertSame('213624820', $refundResponseTransfer->getTxid());
    }

    /**
     * @return void
     */
    public function testExecutePartialCapture(): void
    {
        $orderPriceDistributorMock = $this->createOrderPriceDistributorMock();
        $payoneFacade = $this->createPayoneFacadeWithOrderPriceDistributorMocked($orderPriceDistributorMock);

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $payonePartialOperationTransfer = (new PayonePartialOperationRequestTransfer())
            ->setOrder($this->orderTransfer);
        $orderPriceDistributorMock
            ->expects($this->once())
            ->method('distributeOrderPrice')
            ->with($this->equalTo($this->orderTransfer))
            ->willReturn($this->orderTransfer);

        $captureResponseTransfer = $payoneFacade->executePartialCapture($payonePartialOperationTransfer);

        $this->assertSame('213624820', $captureResponseTransfer->getTxid());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface
     */
    protected function createOrderPriceDistributorMock(): OrderPriceDistributorInterface
    {
        return $this->getMockBuilder(OrderPriceDistributor::class)->getMock();
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Distributor\OrderPriceDistributorInterface $orderPriceDistributorMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerEco\Zed\Payone\Business\PayoneFacadeInterface
     */
    protected function createPayoneFacadeWithOrderPriceDistributorMocked(
        OrderPriceDistributorInterface $orderPriceDistributorMock
    ): PayoneFacadeInterface {
        $adapter = new DummyAdapter(static::AUTHORIZE_RESPONSE);
        $businessFactoryMock = $this->createBusinessFactoryMock(
            $adapter,
            ['createOrderPriceDistributor']
        );
        $businessFactoryMock->method('createOrderPriceDistributor')->willReturn($orderPriceDistributorMock);

        return $this->createFacadeMock(
            $adapter,
            $businessFactoryMock
        );
    }
}
