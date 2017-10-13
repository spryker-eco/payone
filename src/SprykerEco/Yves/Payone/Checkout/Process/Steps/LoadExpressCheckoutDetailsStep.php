<?php
/**
 * Created by PhpStorm.
 * User: sikachev
 * Date: 10/13/17
 * Time: 5:37 PM
 */

namespace SprykerEco\Yves\Payone\Checkout\Process\Steps;


use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\AbstractBaseStep;
use SprykerEco\Yves\Payone\Handler\ExpressCheckoutHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class LoadExpressCheckoutDetailsStep extends AbstractBaseStep
{

    protected $expressCheckoutHandler;

    public function __construct(
        ExpressCheckoutHandlerInterface $expressCheckoutHandler,
        $stepRoute,
        $escapeRoute
    )
    {
        parent::__construct($stepRoute, $escapeRoute);
        $this->expressCheckoutHandler = $expressCheckoutHandler;
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
        //TODO: check why the second request fails or cache the first request.
        $this->expressCheckoutHandler
            ->loadPaypalExpressCheckoutDetails();

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