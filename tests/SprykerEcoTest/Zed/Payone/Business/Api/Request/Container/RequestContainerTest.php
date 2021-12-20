<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Zed\Payone\Business\Api\Request\Container;

use PHPUnit_Framework_TestCase;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer as AuthorizationBusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\CashOnDeliveryContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\DirectDebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\EWalletContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\FinancingContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\OnlineBankTransferContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\PrepaymentContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ShippingContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\BankAccountCheckContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer as CaptureBusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\CaptureContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer as DebitBusinessContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\PaymentMethod\BankAccountContainer as DebitBankAccountContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\DebitContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GetInvoiceContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\GetSecurityInvoiceContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\PreAuthorizationContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\Refund\PaymentMethod\BankAccountContainer as RefundBankAccountContainer;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\RefundContainer;

/**
 * @group Unit
 * @group SprykerEco
 * @group Zed
 * @group Payone
 * @group Business
 * @group Api
 * @group Request
 * @group Container
 * @group RequestContainerTest
 */
class RequestContainerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    protected $amount = 9900;

    /**
     * @var string
     */
    protected $encoding = 'UTF-8';

    /**
     * @var string
     */
    protected $currency = 'EUR';

    /**
     * @var int
     */
    protected $sequenceNumber = 2;

    /**
     * @var string
     */
    protected $mode = 'test';

    /**
     * @var string
     */
    protected $txId = '123456789';

    /**
     * @var string
     */
    protected $portalId = '12345';

    /**
     * @var string
     */
    protected $mid = '123';

    /**
     * @var string
     */
    protected $aid = '1234';

    /**
     * @var string
     */
    protected $integratorName = 'integrator-name';

    /**
     * @var string
     */
    protected $integratorVersion = '1.0';

    /**
     * @var string
     */
    protected $solutionName = 'solution-name';

    /**
     * @var string
     */
    protected $solutionVersion = '2.0';

    /**
     * @var string
     */
    protected $key = '123456789-test-key';

    /**
     * @var string
     */
    protected $reference = 'DE000000001';

    /**
     * @var string
     */
    protected $clearingType = 'pre';

    /**
     * @var string
     */
    protected $narrativeText = 'some-text';

    /**
     * @var string
     */
    protected $country = 'DE';

    /**
     * @var string
     */
    protected $city = 'Berlin';

    /**
     * @var string
     */
    protected $firstName = 'Spencor';

    /**
     * @var string
     */
    protected $lastName = 'Hopkin';

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
     * @return void
     */
    public function testRefundContainer(): void
    {
        $container = new RefundContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setSequenceNumber($this->sequenceNumber);
        $container->setTxid($this->txId);
        $container->setInvoicing(new TransactionContainer());

        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->sequenceNumber, $container->getSequenceNumber());
        $this->assertEquals($this->txId, $container->getTxid());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
    }

    /**
     * @return void
     */
    public function testEmptyRefundContainer(): void
    {
        $container = new RefundContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testDebitContainer(): void
    {
        $container = new DebitContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setSequenceNumber($this->sequenceNumber);
        $container->setTxid($this->txId);
        $container->setBusiness(new DebitBusinessContainer());
        $container->setInvoicing(new TransactionContainer());

        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->sequenceNumber, $container->getSequenceNumber());
        $this->assertEquals($this->txId, $container->getTxid());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Debit\BusinessContainer', $container->getBusiness());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
    }

    /**
     * @return void
     */
    public function testEmptyDebitContainer(): void
    {
        $container = new DebitContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testCaptureContainer(): void
    {
        $container = new CaptureContainer();

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setSequenceNumber($this->sequenceNumber);
        $container->setTxid($this->txId);
        $container->setBusiness(new CaptureBusinessContainer());
        $container->setInvoicing(new TransactionContainer());

        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->sequenceNumber, $container->getSequenceNumber());
        $this->assertEquals($this->txId, $container->getTxid());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Capture\BusinessContainer', $container->getBusiness());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
    }

    /**
     * @return void
     */
    public function testEmptyCaptureContainer(): void
    {
        $container = new CaptureContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testAuthorizationContainer(): void
    {
        $container = new AuthorizationContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setAid($this->aid);
        $container->setReference($this->reference);
        $container->setClearingType($this->clearingType);
        $container->setNarrativeText($this->narrativeText);
        $container->setBusiness(new AuthorizationBusinessContainer());
        $container->setInvoicing(new TransactionContainer());
        $container->set3dsecure(new ThreeDSecureContainer());
        $container->setPersonalData(new PersonalContainer());
        $container->setPaymentMethod(new PrepaymentContainer());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION, $container->getRequest());
        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->reference, $container->getReference());
        $this->assertEquals($this->aid, $container->getAid());
        $this->assertEquals($this->clearingType, $container->getClearingType());
        $this->assertEquals($this->narrativeText, $container->getNarrativeText());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer', $container->getBusiness());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer', $container->get3dsecure());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer', $container->getPersonalData());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\PrepaymentContainer', $container->getPaymentMethod());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container->getPaymentMethod());
    }

    /**
     * @return void
     */
    public function testEmptyAuthorizationContainer(): void
    {
        $container = new AuthorizationContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testPreAuthorizationContainer(): void
    {
        $container = new PreAuthorizationContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAmount($this->amount);
        $container->setAid($this->aid);
        $container->setReference($this->reference);
        $container->setClearingType($this->clearingType);
        $container->setNarrativeText($this->narrativeText);
        $container->setInvoicing(new TransactionContainer());
        $container->set3dsecure(new ThreeDSecureContainer());
        $container->setPersonalData(new PersonalContainer());
        $container->setPaymentMethod(new PrepaymentContainer());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_PREAUTHORIZATION, $container->getRequest());
        $this->assertStandardParams($container);
        $this->assertEquals($this->amount, $container->getAmount());
        $this->assertEquals($this->reference, $container->getReference());
        $this->assertEquals($this->aid, $container->getAid());
        $this->assertEquals($this->clearingType, $container->getClearingType());
        $this->assertEquals($this->narrativeText, $container->getNarrativeText());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\TransactionContainer', $container->getInvoicing());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\ThreeDSecureContainer', $container->get3dsecure());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PersonalContainer', $container->getPersonalData());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\PrepaymentContainer', $container->getPaymentMethod());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container->getPaymentMethod());
    }

    /**
     * @return void
     */
    public function testEmptyPreAuthorizationContainer(): void
    {
        $container = new PreAuthorizationContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testBankAccountCheckContainer(): void
    {
        $container = new BankAccountCheckContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setAid($this->aid);
        $this->assertEquals($this->aid, $container->getAid());

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setBankCountry('country');
        $this->assertEquals('country', $container->getBankCountry());

        $container->setBankCode('code');
        $this->assertEquals('code', $container->getBankCode());

        $container->setBankAccount('account');
        $this->assertEquals('account', $container->getBankAccount());

        $container->setCheckType('checktype');
        $this->assertEquals('checktype', $container->getCheckType());

        $container->setLanguage('language');
        $this->assertEquals('language', $container->getLanguage());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_BANKACCOUNTCHECK, $container->getRequest());
        $this->assertStandardParams($container);
    }

    /**
     * @return void
     */
    public function testEmptyBankAccountCheckContainer(): void
    {
        $container = new BankAccountCheckContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testGetInvoiceContainer(): void
    {
        $container = new GetInvoiceContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setInvoiceTitle('invoicetitle');
        $this->assertEquals('invoicetitle', $container->getInvoiceTitle());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_GETINVOICE, $container->getRequest());
        $this->assertStandardParams($container);
    }

    /**
     * @return void
     */
    public function testEmptyGetInvoiceContainer(): void
    {
        $container = new GetInvoiceContainer();
        $this->assertCount(1, $container->toArray()); // request set in container
    }

    /**
     * @return void
     */
    public function testGetSecurityInvoiceContainer(): void
    {
        $container = new GetSecurityInvoiceContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $this->setStandardParams($container);
        $container->setInvoiceTitle('invoicetitle');
        $this->assertEquals('invoicetitle', $container->getInvoiceTitle());

        $this->assertEquals(PayoneApiConstants::REQUEST_TYPE_GETSECURITYINVOICE, $container->getRequest());
        $this->assertStandardParams($container);
    }

    /**
     * @return void
     */
    public function testEmptyGetSecurityInvoiceContainer(): void
    {
        $container = new GetSecurityInvoiceContainer();
        $this->assertCount(1, $container->toArray());
    }

    /**
     * @return void
     */
    public function testPersonalContainer(): void
    {
        $container = new PersonalContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setAddressAddition('addition');
        $this->assertEquals('addition', $container->getAddressAddition());

        $container->setBirthday('2000-01-01');
        $this->assertEquals('2000-01-01', $container->getBirthday());

        $container->setCity('city');
        $this->assertEquals('city', $container->getCity());

        $container->setCompany('company');
        $this->assertEquals('company', $container->getCompany());

        $container->setCountry('country');
        $this->assertEquals('country', $container->getCountry());

        $container->setCustomerId('cid');
        $this->assertEquals('cid', $container->getCustomerId());

        $container->setEmail('email');
        $this->assertEquals('email', $container->getEmail());

        $container->setFirstName('firstname');
        $this->assertEquals('firstname', $container->getFirstName());

        $container->setLastName('lastname');
        $this->assertEquals('lastname', $container->getLastName());

        $container->setLanguage('language');
        $this->assertEquals('language', $container->getLanguage());

        $container->setIp('ip');
        $this->assertEquals('ip', $container->getIp());

        $container->setSalutation('salutation');
        $this->assertEquals('salutation', $container->getSalutation());

        $container->setState('state');
        $this->assertEquals('state', $container->getState());

        $container->setStreet('street');
        $this->assertEquals('street', $container->getStreet());

        $container->setTelephoneNumber('phonenumber');
        $this->assertEquals('phonenumber', $container->getTelephoneNumber());

        $container->setTitle('title');
        $this->assertEquals('title', $container->getTitle());

        $container->setUserId('userid');
        $this->assertEquals('userid', $container->getUserId());

        $container->setVatId('vatid');
        $this->assertEquals('vatid', $container->getVatId());

        $container->setZip('zip');
        $this->assertEquals('zip', $container->getZip());

        $this->assertCount(19, $container->toArray());
    }

    /**
     * @return void
     */
    public function testPrepaymentContainer(): void
    {
        $container = new PrepaymentContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setClearingBankAccount('account');
        $this->assertEquals('account', $container->getClearingBankAccount());

        $container->setClearingBankAccountHolder('holder');
        $this->assertEquals('holder', $container->getClearingBankAccountHolder());

        $container->setClearingBankBic('bic');
        $this->assertEquals('bic', $container->getClearingBankBic());

        $container->setClearingBankCity('city');
        $this->assertEquals('city', $container->getClearingBankCity());

        $container->setClearingBankCode('code');
        $this->assertEquals('code', $container->getClearingBankCode());

        $container->setClearingBankCountry('country');
        $this->assertEquals('country', $container->getClearingBankCountry());

        $container->setClearingBankIban('iban');
        $this->assertEquals('iban', $container->getClearingBankIban());

        $container->setClearingBankName('name');
        $this->assertEquals('name', $container->getClearingBankName());
    }

    /**
     * @return void
     */
    public function testEWalletContainer(): void
    {
        $container = new EWalletContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setWalletType('type');
        $this->assertEquals('type', $container->getWalletType());

        $container->setRedirect(new RedirectContainer());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer', $container->getRedirect());

        $this->assertCount(1, $container->toArray());
    }

    /**
     * @return void
     */
    public function testOnlineBankTransferContainer(): void
    {
        $container = new OnlineBankTransferContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBankAccount('account');
        $this->assertEquals('account', $container->getBankAccount());

        $container->setBankCountry('country');
        $this->assertEquals('country', $container->getBankCountry());

        $container->setBankCode('code');
        $this->assertEquals('code', $container->getBankCode());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $container->setOnlineBankTransferType('transfertype');
        $this->assertEquals('transfertype', $container->getOnlineBankTransferType());

        $container->setBankGroupType('grouptype');
        $this->assertEquals('grouptype', $container->getBankGroupType());

        $container->setRedirect(new RedirectContainer());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer', $container->getRedirect());

        $this->assertCount(7, $container->toArray());
    }

    /**
     * @return void
     */
    public function testCashOnDeliveryContainer(): void
    {
        $container = new CashOnDeliveryContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setShippingProvider('shippingprovider');
        $this->assertEquals('shippingprovider', $container->getShippingProvider());

        $this->assertCount(1, $container->toArray());
    }

    /**
     * @return void
     */
    public function testDirectDebitContainer(): void
    {
        $container = new DirectDebitContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setBankCode('bankcode');
        $this->assertEquals('bankcode', $container->getBankCode());

        $container->setBankAccount('bankaccount');
        $this->assertEquals('bankaccount', $container->getBankAccount());

        $container->setBankAccountHolder('bankaccountholder');
        $this->assertEquals('bankaccountholder', $container->getBankAccountHolder());

        $container->setBankCountry('bankcountry');
        $this->assertEquals('bankcountry', $container->getBankCountry());

        $container->setMandateIdentification('mandatidentification');
        $this->assertEquals('mandatidentification', $container->getMandateIdentification());

        $this->assertCount(7, $container->toArray());
    }

    /**
     * @return void
     */
    public function testFinancingContainer(): void
    {
        $container = new FinancingContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\PaymentMethod\AbstractPaymentMethodContainer', $container);
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setFinancingType('type');
        $this->assertEquals('type', $container->getFinancingType());

        $container->setRedirect(new RedirectContainer());
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\Authorization\RedirectContainer', $container->getRedirect());

        $this->assertCount(1, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyFinancingContainer(): void
    {
        $container = new FinancingContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function test3DSecureContainer(): void
    {
        $container = new ThreeDSecureContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setCavv('cavv');
        $this->assertEquals('cavv', $container->getCavv());

        $container->setEci('eci');
        $this->assertEquals('eci', $container->getEci());

        $container->setXid('xid');
        $this->assertEquals('xid', $container->getXid());

        $this->assertCount(3, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmpty3DSecureContainer(): void
    {
        $container = new ThreeDSecureContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testRedirectContainer(): void
    {
        $container = new RedirectContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBackUrl('backurl');
        $this->assertEquals('backurl', $container->getBackUrl());

        $container->setErrorUrl('errorurl');
        $this->assertEquals('errorurl', $container->getErrorUrl());

        $container->setSuccessUrl('successurl');
        $this->assertEquals('successurl', $container->getSuccessUrl());

        $this->assertCount(3, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyRedirectContainer(): void
    {
        $container = new RedirectContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testInvoicingTransactionContainer(): void
    {
        $container = new TransactionContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setInvoiceappendix('appendix');
        $this->assertEquals('appendix', $container->getInvoiceappendix());

        $container->setInvoiceDeliverydate('deliverydate');
        $this->assertEquals('deliverydate', $container->getInvoiceDeliverydate());

        $container->setInvoiceDeliveryenddate('deliveryenddate');
        $this->assertEquals('deliveryenddate', $container->getInvoiceDeliveryenddate());

        $container->setInvoiceDeliverymode('deliverymode');
        $this->assertEquals('deliverymode', $container->getInvoiceDeliverymode());

        $container->setInvoiceid('invoiceid');
        $this->assertEquals('invoiceid', $container->getInvoiceid());

        $items = [new ItemContainer(), new ItemContainer()];
        $container->setItems($items);

        $this->assertCount(6, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyInvoicingTransactionContainer(): void
    {
        $container = new TransactionContainer();
        $this->assertCount(1, $container->toArray()); // 1 empty array
    }

    /**
     * @return void
     */
    public function testInvoicingItemContainer(): void
    {
        $container = new ItemContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setDe('de');
        $this->assertEquals('de', $container->getDe());

        $container->setEd('ed');
        $this->assertEquals('ed', $container->getEd());

        $container->setId('id');
        $this->assertEquals('id', $container->getId());

        $container->setIt('it');
        $this->assertEquals('it', $container->getIt());

        $container->setNo(10);
        $this->assertEquals(10, $container->getNo());

        $container->setPr(20);
        $this->assertEquals(20, $container->getPr());

        $container->setSd('sd');
        $this->assertEquals('sd', $container->getSd());

        $container->setVa(30);
        $this->assertEquals(30, $container->getVa());

        $this->assertCount(8, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyInvoicingItemContainer(): void
    {
        $container = new ItemContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testAuthorizationBusinessContainer(): void
    {
        $container = new AuthorizationBusinessContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBookingDate('bookingdate');
        $this->assertEquals('bookingdate', $container->getBookingDate());

        $container->setDocumentDate('documentdate');
        $this->assertEquals('documentdate', $container->getDocumentDate());

        $container->setDueTime('duetime');
        $this->assertEquals('duetime', $container->getDueTime());

        $this->assertCount(3, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyAuthorizationBusinessContainer(): void
    {
        $container = new AuthorizationBusinessContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testCaptureBusinessContainer(): void
    {
        $container = new CaptureBusinessContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBookingDate('bookingdate');
        $this->assertEquals('bookingdate', $container->getBookingDate());

        $container->setDocumentDate('documentdate');
        $this->assertEquals('documentdate', $container->getDocumentDate());

        $container->setDueTime('duetime');
        $this->assertEquals('duetime', $container->getDueTime());

        $container->setSettleAccount('settleaccout');
        $this->assertEquals('settleaccout', $container->getSettleAccount());

        $this->assertCount(4, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyCaptureBusinessContainer(): void
    {
        $container = new CaptureBusinessContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testDebitBusinessContainer(): void
    {
        $container = new DebitBusinessContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBookingDate('bookingdate');
        $this->assertEquals('bookingdate', $container->getBookingDate());

        $container->setDocumentDate('documentdate');
        $this->assertEquals('documentdate', $container->getDocumentDate());

        $container->setTransactionType('transactiontype');
        $this->assertEquals('transactiontype', $container->getTransactionType());

        $container->setSettleAccount('settleaccout');
        $this->assertEquals('settleaccout', $container->getSettleAccount());

        $this->assertCount(4, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyDebitBusinessContainer(): void
    {
        $container = new DebitBusinessContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testAuthorizationShippingContainer(): void
    {
        $container = new ShippingContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setShippingCity('city');
        $this->assertEquals('city', $container->getShippingCity());

        $container->setShippingCompany('company');
        $this->assertEquals('company', $container->getShippingCompany());

        $container->setShippingCountry('country');
        $this->assertEquals('country', $container->getShippingCountry());

        $container->setShippingFirstName('firstname');
        $this->assertEquals('firstname', $container->getShippingFirstName());

        $container->setShippingLastName('lastname');
        $this->assertEquals('lastname', $container->getShippingLastName());

        $container->setShippingState('state');
        $this->assertEquals('state', $container->getShippingState());

        $container->setShippingStreet('street');
        $this->assertEquals('street', $container->getShippingStreet());

        $container->setShippingZip('zip');
        $this->assertEquals('zip', $container->getShippingZip());

        $this->assertCount(8, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyAuthorizationShippingContainer(): void
    {
        $container = new ShippingContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testDebitBankAccountContainer(): void
    {
        $container = new DebitBankAccountContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBankAccount('bankaccount');
        $this->assertEquals('bankaccount', $container->getBankAccount());

        $container->setBankAccountHolder('holder');
        $this->assertEquals('holder', $container->getBankAccountHolder());

        $container->setBankBranchCode(10);
        $this->assertEquals(10, $container->getBankBranchCode());

        $container->setBankCheckDigit(20);
        $this->assertEquals(20, $container->getBankCheckDigit());

        $container->setBankCode(30);
        $this->assertEquals(30, $container->getBankCode());

        $container->setBankCountry('country');
        $this->assertEquals('country', $container->getBankCountry());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $container->setMandateIdentification('mandateidentifiction');
        $this->assertEquals('mandateidentifiction', $container->getMandateIdentification());

        $this->assertCount(9, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyDebitBankAccountContainer(): void
    {
        $container = new DebitBankAccountContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @return void
     */
    public function testRefundBankAccountContainer(): void
    {
        $container = new RefundBankAccountContainer();
        $this->assertInstanceOf('SprykerEco\Zed\Payone\Business\Api\Request\Container\ContainerInterface', $container);

        $container->setBankaccount('bankaccount');
        $this->assertEquals('bankaccount', $container->getBankaccount());

        $container->setBankbranchcode(10);
        $this->assertEquals(10, $container->getBankbranchcode());

        $container->setBankcheckdigit(20);
        $this->assertEquals(20, $container->getBankcheckdigit());

        $container->setBankcode(30);
        $this->assertEquals(30, $container->getBankcode());

        $container->setBankcountry('country');
        $this->assertEquals('country', $container->getBankcountry());

        $container->setBic('bic');
        $this->assertEquals('bic', $container->getBic());

        $container->setIban('iban');
        $this->assertEquals('iban', $container->getIban());

        $this->assertCount(7, $container->toArray());
    }

    /**
     * @return void
     */
    public function testEmptyRefundBankAccountContainer(): void
    {
        $container = new RefundBankAccountContainer();
        $this->assertCount(0, $container->toArray());
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return void
     */
    protected function setStandardParams(AbstractRequestContainer $container): void
    {
        $container->setEncoding($this->encoding);
        $container->setMode($this->mode);
        $container->setPortalid($this->portalId);
        $container->setMid($this->mid);
        $container->setIntegratorName($this->integratorName);
        $container->setIntegratorVersion($this->integratorVersion);
        $container->setSolutionName($this->solutionName);
        $container->setSolutionVersion($this->solutionVersion);
        $container->setKey($this->key);
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return void
     */
    protected function assertStandardParams(AbstractRequestContainer $container): void
    {
        $this->assertEquals($this->encoding, $container->getEncoding());
        $this->assertEquals($this->mode, $container->getMode());
        $this->assertEquals($this->portalId, $container->getPortalid());
        $this->assertEquals($this->mid, $container->getMid());
        $this->assertEquals($this->key, $container->getKey());
        $this->assertEquals($this->integratorName, $container->getIntegratorName());
        $this->assertEquals($this->integratorVersion, $container->getIntegratorVersion());
        $this->assertEquals($this->solutionName, $container->getSolutionName());
        $this->assertEquals($this->solutionVersion, $container->getSolutionVersion());
    }
}
