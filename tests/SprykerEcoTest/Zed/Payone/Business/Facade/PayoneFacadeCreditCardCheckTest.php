<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\DataBuilder\PayoneBankAccountCheckBuilder;
use Generated\Shared\DataBuilder\PayoneCreditCardBuilder;
use Generated\Shared\DataBuilder\PayonePaymentBuilder;
use Generated\Shared\Transfer\CreditCardCheckResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Generated\Shared\Transfer\PayoneCreditCardTransfer;
use Generated\Shared\Transfer\PayonePartialOperationRequestTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\RefundResponseTransfer;
use Spryker\Service\Container\Container;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Shared\Payone\PayoneConstants;
use SprykerEco\Zed\Payone\Business\Api\Response\Container\BankAccountCheckResponseContainer;
use SprykerEco\Zed\Payone\PayoneConfig;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\AbstractAdapterMock;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\BankAccountCheckAdapterMock;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\CreditCardCheckAdapterMock;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\DummyAdapter;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\GetExpressCheckoutAdapterMock;
use SprykerEcoTest\Zed\Payone\PayoneZedTester;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

class PayoneFacadeCreditCardCheckTest extends AbstractBusinessTest
{
    protected const REQUEST_CARD_TYPE_VISA = 'V';
    protected const REQUEST_CARD_PAN = '4111111111111111';
    protected const REQUEST_CARD_CVC2 = '111';
    protected const REQUEST_CARD_EXPIRE_DATE = '2311';
    protected const EXPECTED_PSEUDO_CARD_PAN = '1111111111111111';

    /**
     * @var \SprykerEcoTest\Zed\Payone\PayoneZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([PayoneZedTester::TEST_STATE_MACHINE_NAME]);

        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            'request_stack' => $this->getRequestStackMock(),
        ]));
    }

    /**
     * @return void
     */
    public function testBankAccountCheck(): void
    {
        //Arrange
        $adapter = new CreditCardCheckAdapterMock();
        $adapter->setExpectSuccess(true);
        $facadeMock = $this->createFacadeMock($adapter);

        $creditCardBuilder = new PayoneCreditCardBuilder();
        $creditCard = $creditCardBuilder->build();
        $creditCard->setCardType(static::REQUEST_CARD_TYPE_VISA);
        $creditCard->setCardPan(static::REQUEST_CARD_PAN);
        $creditCard->setCardCvc2(static::REQUEST_CARD_CVC2);
        $creditCard->setCardExpireDate(static::REQUEST_CARD_EXPIRE_DATE);
        $creditCard->setStoreCardData(PayoneApiConstants::STORE_CARD_DATA_NO);

        $creditCardPaymentBuilder = new PayonePaymentBuilder();
        $creditCardPayment = $creditCardPaymentBuilder->build();
        $creditCardPayment->setPaymentMethod(PayoneApiConstants::PAYMENT_METHOD_CREDITCARD);
        $creditCard->setPayment($creditCardPayment);

        //Act
        $creditCardCheckResponse = $facadeMock->creditCardCheck($creditCard);

        //Assert
        $this->assertInstanceOf(CreditCardCheckResponseTransfer::class, $creditCardCheckResponse);
        $this->assertSame(static::EXPECTED_PSEUDO_CARD_PAN, $creditCardCheckResponse->getPseudoCardPan());
    }
}
