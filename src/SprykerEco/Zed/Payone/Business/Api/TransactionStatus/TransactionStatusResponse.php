<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\TransactionStatus;

class TransactionStatusResponse
{
    /**
     * part of payone specification
     *
     * @var string
     */
    public const STATUS_OK = 'TSOK';

    /**
     * not in payone specification, for the purpose if payone should queue transaction status and resend again
     *
     * @var string
     */
    public const STATUS_ERROR = 'TSERROR';

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $errorMessage = '';

    /**
     * @param bool $isSuccess
     */
    public function __construct(bool $isSuccess)
    {
        $this->status = $isSuccess ? static::STATUS_OK : static::STATUS_ERROR;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $output = $this->getStatus();
        if ($this->isError() && $this->getErrorMessage()) {
            $output .= ' : ' . $this->getErrorMessage();
        }

        return $output;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    public function setErrorMessage(string $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return ($this->getStatus() === static::STATUS_OK);
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return (!$this->isSuccess());
    }
}
