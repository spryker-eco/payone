<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\DataBuilder\PayoneCreditCardBuilder;
use Generated\Shared\DataBuilder\PayonePaymentBuilder;
use Generated\Shared\Transfer\CreditCardCheckResponseTransfer;
use Spryker\Service\Container\Container;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\CreditCardCheckAdapterMock;
use SprykerEcoTest\Zed\Payone\PayoneZedTester;

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
    public function testCreditCardCheck(): void
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
