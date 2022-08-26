<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container;

interface RequestContainerInterface extends ContainerInterface
{
    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $email
     *
     * @return void
     */
    public function setEmail(?string $email = null): void;
}
