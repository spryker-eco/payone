<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Facade;

use Generated\Shared\DataBuilder\PayoneBankAccountCheckBuilder;
use Generated\Shared\Transfer\PayoneBankAccountCheckTransfer;
use Spryker\Service\Container\Container;
use Spryker\Shared\Kernel\Container\GlobalContainer;
use SprykerEcoTest\Zed\Payone\Business\AbstractBusinessTest;
use SprykerEcoTest\Zed\Payone\Business\Api\Adapter\BankAccountCheckAdapterMock;
use SprykerEcoTest\Zed\Payone\PayoneZedTester;

class PayoneFacadeBankAccountCheckTest extends AbstractBusinessTest
{
    /**
     * @var string
     */
    protected const REQUEST_BANK_COUNTRY = 'DE';

    /**
     * @var string
     */
    protected const REQUEST_BANK_ACCOUNT = 'AK001';

    /**
     * @var string
     */
    protected const REUEST_BANK_CODE = '001';

    /**
     * @var string
     */
    protected const REQUEST_BANK_IBAN = '000000000000000000';

    /**
     * @var string
     */
    protected const REQUEST_BIC = '000000000000000001';

    /**
     * @var string
     */
    protected const EXPECTED_BANK_COUNTRY = 'DE';

    /**
     * @var string
     */
    protected const ERROR_CODE = '0';

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
        $adapter = new BankAccountCheckAdapterMock();
        $adapter->setExpectSuccess(true);
        $facadeMock = $this->createFacadeMock($adapter);

        $bankAccountCheckBuilder = new PayoneBankAccountCheckBuilder();
        $bankAccountCheck = $bankAccountCheckBuilder->build();
        $bankAccountCheck->setBankCountry(static::REQUEST_BANK_COUNTRY);
        $bankAccountCheck->setBankAccount(static::REQUEST_BANK_ACCOUNT);
        $bankAccountCheck->setBankCode(static::REUEST_BANK_CODE);
        $bankAccountCheck->setIban(static::REQUEST_BANK_IBAN);
        $bankAccountCheck->setBic(static::REQUEST_BIC);
        $bankAccountCheck->setErrorCode(static::ERROR_CODE);

        //Act
        $bankAccountCheckResponse = $facadeMock->bankAccountCheck($bankAccountCheck);

        //Assert
        $this->assertInstanceOf(PayoneBankAccountCheckTransfer::class, $bankAccountCheckResponse);
        $this->assertSame(static::EXPECTED_BANK_COUNTRY, $bankAccountCheckResponse->getBankCountry());
    }
}
