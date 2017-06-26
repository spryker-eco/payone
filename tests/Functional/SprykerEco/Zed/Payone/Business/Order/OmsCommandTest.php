<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\SprykerEco\Zed\Payone\Business\Order;

use Functional\SprykerEco\Zed\Payone\Business\AbstractPayoneTest;
use Functional\SprykerEco\Zed\Payone\Business\Api\Adapter\DummyAdapter;
use Functional\SprykerEco\Zed\Payone\Business\PayoneFacadeMockBuilder;
use Generated\Shared\Transfer\PayoneCaptureTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\PayoneRefundTransfer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\AuthorizationResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\CaptureResponseContainer;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\RefundResponseContainer;

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

    const PREAUTHORIZE_RESPONSE = '{"txid":"213547373","userid":"95374053","redirecturl":"https:\/\/sandbox.paydirekt.de\/checkout\/#\/checkout\/1dbdbdd1-7e0c-401e-9256-8cb096ac08cb","status":"REDIRECT"}';
    const AUTHORIZE_RESPONSE = '{"txid":"213624820","userid":"95419580","redirecturl":"https:\/\/www.sofort-ueberweisung.de\/payment\/start?user_id=10910&project_id=55780&hash=e378b42b538369f18f28f93327612691bf1f8f3b1ca476a0be5bb8b03a93f0f5035f79bdc9ea3416e2404c441247fbb2b48c41ee71e6931b73a76d45a412de98&sender_holder=Max+Musterman&sender_account_number=&sender_bank_code=88888888&sender_country_id=DE&recipient_holder=PAYONE+GmbH&recipient_account_number=0022520120&recipient_bank_code=21070020&recipient_bank_bic=DEUTDEHH210&recipient_iban=DE87210700200022520120&recipient_country_id=DE&amount=78.12&currency_id=EUR&reason_1=213624820&reason_2=&user_variable_0=213624820;95419580&user_variable_1=http%3A%2F%2Fwww.de.payone.local%2Fcheckout%2Fsuccess%3ForderReference%3DDE--3%26sig%3Df4ed226e932f865be02bd338fbdefa0e63ba510764f5259ef2e585129fb0f3f3&user_variable_2=http%3A%2F%2Fwww.de.payone.local%2Fpayone%2Fregular-redirect-payment-cancellation%3ForderReference%3DDE--3%26sig%3Df4ed226e932f865be02bd338fbdefa0e63ba510764f5259ef2e585129fb0f3f3&recipient_name1=Spryker+Systems+GmbH+%28SP%29&recipient_street=testweg+1&recipient_zipcode=12345&recipient_city=test&encoding=utf-8","status":"REDIRECT"}';

    public function testPreAuthorizePayment()
    {
        $this->payoneFacade = (new PayoneFacadeMockBuilder())
            ->build(
                new DummyAdapter(static::PREAUTHORIZE_RESPONSE),
                $this
            );

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $authorizationResponseContainer = $this->payoneFacade
            ->preAuthorizePayment($this->orderEntity->getIdSalesOrder());
        $this->assertInstanceOf(AuthorizationResponseContainer::class, $authorizationResponseContainer);
    }

    public function testAuthorizePayment()
    {
        $this->payoneFacade = (new PayoneFacadeMockBuilder())
            ->build(
                new DummyAdapter(static::AUTHORIZE_RESPONSE),
                $this
            );

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $authorizationResponseContainer = $this->payoneFacade->authorizePayment($this->orderTransfer);
        $this->assertInstanceOf(AuthorizationResponseContainer::class, $authorizationResponseContainer);
    }

    public function testCapturePayment()
    {
        $this->payoneFacade = (new PayoneFacadeMockBuilder())
            ->build(
                new DummyAdapter(static::AUTHORIZE_RESPONSE),
                $this
            );

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $captureTransfer = (new PayoneCaptureTransfer())
            ->setAmount(10)
            ->setPayment((new PayonePaymentTransfer())->fromArray($this->spyPaymentPayone->toArray()))
            ->setSettleaccount('settlement account');

        $captureResponseContainer = $this->payoneFacade->capturePayment($captureTransfer);

        $this->assertInstanceOf(CaptureResponseContainer::class, $captureResponseContainer);
        $this->assertEquals(213552995, $captureResponseContainer->getTxid());
        $this->assertEquals('settlement account', $captureResponseContainer->getSettleaccount());
    }

    public function testRefundPayment()
    {
        $this->payoneFacade = (new PayoneFacadeMockBuilder())
            ->build(
                new DummyAdapter(static::AUTHORIZE_RESPONSE),
                $this
            );

        $this->createPayonePayment(PayoneApiConstants::PAYMENT_METHOD_INVOICE);
        $this->createPayoneApiLog(PayoneApiConstants::REQUEST_TYPE_REFUND, PayoneApiConstants::RESPONSE_TYPE_APPROVED);
        $this->createPayonePaymentDetail();

        $refundTransfer = (new PayoneRefundTransfer())
            ->setNarrativeText('Narrative Text')
            ->setUseCustomerdata('Use Customer data')
            ->setAmount(10)
            ->setPayment((new PayonePaymentTransfer())->fromArray($this->spyPaymentPayone->toArray()));

        $refundResponseContainer = $this->payoneFacade->refundPayment($refundTransfer);

        $this->assertInstanceOf(RefundResponseContainer::class, $refundResponseContainer);
        $this->assertEquals(213552995, $refundResponseContainer->getTxid());
    }

}