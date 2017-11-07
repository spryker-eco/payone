<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Response\Container;

class PreAuthorizationResponseContainer extends AbstractResponseContainer
{
    /**
     * @var string
     */
    protected $txid;

    /**
     * @var string
     */
    protected $userid;
}
