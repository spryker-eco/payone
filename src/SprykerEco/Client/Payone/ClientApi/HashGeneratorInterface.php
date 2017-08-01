<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi;

use SprykerEco\Client\Payone\ClientApi\Request\AbstractRequest;

interface HashGeneratorInterface
{

    /**
     * @param \SprykerEco\Client\Payone\ClientApi\Request\AbstractRequest $request
     * @param string $securityKey
     *
     * @return string
     */
    public function generateHash(AbstractRequest $request, $securityKey);

}
