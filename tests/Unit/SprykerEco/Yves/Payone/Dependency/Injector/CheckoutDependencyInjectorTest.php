<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\SprykerEco\Yves\Payone\Dependency\Injector;

use Generated\Shared\Transfer\PaymentTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Yves\Payone\Dependency\Injector\CheckoutDependencyInjector;

/**
 * @group Unit
 * @group SprykerEco
 * @group Yves
 * @group Payone
 * @group Dependency
 * @group Injector
 * @group CheckoutDependencyInjectorTest
 */
class CheckoutDependencyInjectorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testInjectInjectsPaymentSubFormAndHandler()
    {
        $container = $this->createContainerToInjectTo();

        $checkoutDependencyInjector = new CheckoutDependencyInjector();
        $checkoutDependencyInjector->inject($container);

        $checkoutSubFormPluginCollection = $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS];
        $this->assertGreaterThanOrEqual(5, $checkoutSubFormPluginCollection->count());

        $checkoutStepHandlerPluginCollection = $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER];
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_CREDIT_CARD));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_DIRECT_DEBIT));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_E_WALLET));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_INVOICE));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_PRE_PAYMENT));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_POSTFINANCE_EFINANCE_ONLINE_TRANSFER));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_EPS_ONLINE_TRANSFER));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_GIROPAY_ONLINE_TRANSFER));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_POSTFINANCE_CARD_ONLINE_TRANSFER));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER));
        $this->assertTrue($checkoutStepHandlerPluginCollection->has(PaymentTransfer::PAYONE_PRZELEWY24_ONLINE_TRANSFER));
    }

    /**
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function createContainerToInjectTo()
    {
        $container = new Container();
        $container[CheckoutDependencyProvider::PAYMENT_SUB_FORMS] = function () {
            return new SubFormPluginCollection();
        };
        $container[CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER] = function () {
            return new StepHandlerPluginCollection();
        };

        return $container;
    }

}
