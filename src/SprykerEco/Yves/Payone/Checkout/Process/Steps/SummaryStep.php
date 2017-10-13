<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\Payone\Checkout\Process\Steps;

use Spryker\Client\Calculation\CalculationClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouperInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Step\AbstractBaseStep;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerEco\Yves\Payone\PayoneDependencyProvider;
use Symfony\Component\HttpFoundation\Request;

class SummaryStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{

    /**
     * @var \Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper
     */
    protected $productBundleGrouper;

    protected $calculationClient;

    protected $shipmentPlugins;


    /**
     * @param \Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouperInterface $productBundleGrouper
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param string $stepRoute
     * @param string $escapeRoute
     */
    public function __construct(
        ProductBundleGrouperInterface $productBundleGrouper,
        CalculationClientInterface $calculationClient,
        StepHandlerPluginCollection $shipmentPlugins,
        $stepRoute,
        $escapeRoute
    ) {
        parent::__construct($stepRoute, $escapeRoute);

        $this->productBundleGrouper = $productBundleGrouper;
        $this->calculationClient = $calculationClient;
        $this->shipmentPlugins = $shipmentPlugins;

    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $quoteTransfer)
    {
        return true;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {

        $shipmentHandler = $this->shipmentPlugins->get(PayoneDependencyProvider::PLUGIN_SHIPMENT_STEP_HANDLER);
        $shipmentHandler->addToDataClass($request, $quoteTransfer);

        return $this->calculationClient->recalculate($quoteTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getBillingAddress() === null
            || $quoteTransfer->getShipment() === null
            || $quoteTransfer->getPayment() === null
            || $quoteTransfer->getPayment()->getPaymentProvider() === null
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $quoteTransfer)
    {
        return [
            'quoteTransfer' => $quoteTransfer,
            'cartItems' => $this->productBundleGrouper->getGroupedBundleItems(
                $quoteTransfer->getItems(),
                $quoteTransfer->getBundleItems()
            ),
        ];
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle()
    {
        return 'checkout.step.summary.title';
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $dataTransfer)
    {
        return $this->postCondition($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $dataTransfer)
    {
        return !$this->requireInput($dataTransfer);
    }

    /**
     * Requirements for this step, return true when satisfied.
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function preCondition(AbstractTransfer $dataTransfer)
    {
        return true;
    }
}
