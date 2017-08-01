<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Dependency\Injector;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface;
use Spryker\Yves\Checkout\CheckoutDependencyProvider;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use SprykerEco\Yves\Payone\Plugin\PayoneHandlerPlugin;
use SprykerEco\Yves\Payone\Plugin\PayoneSubFormsPlugin;

/**
 * @method \SprykerEco\Yves\Payone\PayoneFactory getFactory()
 */
class CheckoutDependencyInjector implements DependencyInjectorInterface
{

    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface|\Spryker\Yves\Kernel\Container
     */
    public function inject(ContainerInterface $container)
    {
        $payoneSubFormsPlugin = new PayoneSubFormsPlugin();
        $paymentMethodsSubForms = $payoneSubFormsPlugin->getPaymentMethodsSubForms();
        $container->extend(CheckoutDependencyProvider::PAYMENT_SUB_FORMS, function (SubFormPluginCollection $paymentSubForms) use ($paymentMethodsSubForms) {
            foreach ($paymentMethodsSubForms as $paymentMethodsSubForm) {
                $paymentSubForms->add($paymentMethodsSubForm);
            }
            return $paymentSubForms;
        });

        $container->extend(CheckoutDependencyProvider::PAYMENT_METHOD_HANDLER, function (StepHandlerPluginCollection $paymentMethodHandler) {
            $payoneHandlerPlugin = new PayoneHandlerPlugin();

            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_CREDIT_CARD);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_E_WALLET);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_DIRECT_DEBIT);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_EPS_ONLINE_TRANSFER);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_GIROPAY_ONLINE_TRANSFER);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_INSTANT_ONLINE_TRANSFER);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_IDEAL_ONLINE_TRANSFER);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_POSTFINANCE_EFINANCE_ONLINE_TRANSFER);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_POSTFINANCE_CARD_ONLINE_TRANSFER);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_PRZELEWY24_ONLINE_TRANSFER);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_PRE_PAYMENT);
            $paymentMethodHandler->add($payoneHandlerPlugin, PaymentTransfer::PAYONE_INVOICE);

            return $paymentMethodHandler;
        });

        return $container;
    }

}
