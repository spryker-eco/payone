<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Checkout\Process\Steps;

use Spryker\Client\Cart\CartClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\AbstractBaseStep;
use SprykerEco\Client\Payone\PayoneClientInterface;
use SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydratorInterface;
use Symfony\Component\HttpFoundation\Request;

class LoadExpressCheckoutDetailsStep extends AbstractBaseStep
{

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerEco\Client\Payone\PayoneClientInterface
     */
    protected $payoneClient;

    /**
     * @var \SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydratorInterface
     */
    protected $quoteHydrator;

    /**
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param \SprykerEco\Client\Payone\PayoneClientInterface $payoneClient
     * @param \SprykerEco\Yves\Payone\Handler\ExpressCheckout\QuoteHydratorInterface $quoteHydrator
     * @param string $stepRoute
     * @param string $escapeRoute
     */
    public function __construct(
        CartClientInterface $cartClient,
        PayoneClientInterface $payoneClient,
        QuoteHydratorInterface $quoteHydrator,
        $stepRoute,
        $escapeRoute
    ) {

        parent::__construct($stepRoute, $escapeRoute);
        $this->cartClient = $cartClient;
        $this->payoneClient = $payoneClient;
        $this->quoteHydrator = $quoteHydrator;
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

    /**
     * Require input, should we render view with form or just skip step after calling execute.
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer)
    {
        return false;
    }

    /**
     * Execute step logic, happens after form submit if provided, gets AbstractTransfer filled by form data.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function execute(Request $request, AbstractTransfer $dataTransfer)
    {
        $quoteTransfer = $this->cartClient->getQuote();
        $details = $this->payoneClient->getPaypalExpressCheckoutDetails($quoteTransfer);
        $quoteTransfer = $this->quoteHydrator->getHydratedQuote($quoteTransfer, $details);
        $this->cartClient->storeQuote($quoteTransfer);

        return $quoteTransfer;
    }

    /**
     * Conditions that should be met for this step to be marked as completed. returns true when satisfied.
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $dataTransfer)
    {
        return true;
    }

}
