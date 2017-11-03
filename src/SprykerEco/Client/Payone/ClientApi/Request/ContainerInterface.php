<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Client\Payone\ClientApi\Request;

interface ContainerInterface
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function __toString();

    /**
     * @return string
     */
    public function toJson();
}
