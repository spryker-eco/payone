<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence\Propel;

use Orm\Zed\Payone\Persistence\Base\SpyPaymentPayoneTransactionStatusLogQuery as BaseSpyPaymentPayoneTransactionStatusLogQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'spy_payment_payone_transaction_status_log' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyPaymentPayoneTransactionStatusLogQuery extends BaseSpyPaymentPayoneTransactionStatusLogQuery
{
    /**
     * @var string
     */
    public const SEQUENCE_NUMBER = 'sequence_number';
}
