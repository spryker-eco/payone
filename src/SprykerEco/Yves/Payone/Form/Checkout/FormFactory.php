<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Payone\Form\Checkout;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Checkout\Form\FormFactory as SprykerFormFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Form\FormCollectionHandler;
use SprykerEco\Yves\Payone\PayoneDependencyProvider;

class FormFactory extends SprykerFormFactory
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer
     *
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    public function createSummaryFormCollection()
    {
        return $this->createFormCollection($this->getSummaryFormTypes(), $this->getSummaryFormDataProviderPlugin());
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface[]
     */
    protected function getSummaryFormTypes()
    {
        return [
            $this->createSummaryForm()
        ];
    }

    protected function createSummaryForm()
    {
        return new SummaryForm();
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getVoucherForm()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY)
            ->create($this->createVoucherFormType());
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    protected function getSummaryFormDataProviderPlugin()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::PLUGIN_SHIPMENT_FORM_DATA_PROVIDER);
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface[]
     */
    protected function createSummaryFormTypes()
    {
        return [
            $this->createSummaryForm(),
            //$this->createVoucherFormType(),
        ];
    }

    /**
     * @return \Pyz\Yves\Checkout\Form\Voucher\VoucherForm
     */
    /*protected function createVoucherFormType()
    {
        return new VoucherForm();
    }*/

    /**
     * @param \Symfony\Component\Form\FormTypeInterface[] $formTypes
     * @param \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface|null $dataProvider
     *
     * @return \Spryker\Yves\StepEngine\Form\FormCollectionHandlerInterface
     */
    protected function createFormCollection(array $formTypes, StepEngineFormDataProviderInterface $dataProvider = null)
    {
        return new FormCollectionHandler($formTypes, $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY), $dataProvider);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Pyz\Client\Customer\CustomerClient
     */
    protected function getCustomerClient()
    {
        return $this->getProvidedDependency(PayoneDependencyProvider::CLIENT_CUSTOMER);
    }

}
