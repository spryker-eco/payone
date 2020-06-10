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
    public const TXACTION_APPOINTED = 'appointed';
    public const TXACTION_CAPTURE = 'capture';
    public const TXACTION_PAID = 'paid';
    public const TXACTION_UNDERPAID = 'underpaid';
    public const TXACTION_CANCELATION = 'cancelation';
    public const TXACTION_REFUND = 'refund';
    public const TXACTION_DEBIT = 'debit';
    public const TXACTION_REMINDER = 'reminder';
    public const TXACTION_VAUTHORIZATION = 'vauthorization';
    public const TXACTION_VSETTLEMENT = 'vsettlement';
    public const TXACTION_TRANSFER = 'transfer';
    public const TXACTION_INVOICE = 'invoice';

    public const STATUS_NEW = 'new';
    public const STATUS_REFUND_APPROVED = 'refund approved';
    public const STATUS_REFUND_FAILED = 'refund failed';
    public const STATUS_CAPTURE_APPROVED = 'capture approved';
    public const STATUS_CAPTURE_FAILED = 'capture failed';
}
