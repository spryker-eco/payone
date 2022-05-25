<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Shared\Payone;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PayoneTransactionStatusConstants
{
    /**
     * @var string
     */
    public const TXACTION_APPOINTED = 'appointed';

    /**
     * @var string
     */
    public const TXACTION_CAPTURE = 'capture';

    /**
     * @var string
     */
    public const TXACTION_PAID = 'paid';

    /**
     * @var string
     */
    public const TXACTION_UNDERPAID = 'underpaid';

    /**
     * @var string
     */
    public const TXACTION_CANCELATION = 'cancelation';

    /**
     * @var string
     */
    public const TXACTION_REFUND = 'refund';

    /**
     * @var string
     */
    public const TXACTION_DEBIT = 'debit';

    /**
     * @var string
     */
    public const TXACTION_REMINDER = 'reminder';

    /**
     * @var string
     */
    public const TXACTION_VAUTHORIZATION = 'vauthorization';

    /**
     * @var string
     */
    public const TXACTION_VSETTLEMENT = 'vsettlement';

    /**
     * @var string
     */
    public const TXACTION_TRANSFER = 'transfer';

    /**
     * @var string
     */
    public const TXACTION_INVOICE = 'invoice';

    /**
     * @var string
     */
    public const STATUS_NEW = 'new';

    /**
     * @var string
     */
    public const STATUS_REFUND_APPROVED = 'refund approved';

    /**
     * @var string
     */
    public const STATUS_REFUND_FAILED = 'refund failed';

    /**
     * @var string
     */
    public const STATUS_CAPTURE_APPROVED = 'capture approved';

    /**
     * @var string
     */
    public const STATUS_CAPTURE_FAILED = 'capture failed';
}
