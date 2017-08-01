<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \SprykerEco\Zed\Payone\PayoneConfig getConfig()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainer getQueryContainer()
 */
class PayonePersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createPaymentPayoneTransactionStatusLogQuery()
    {
        return SpyPaymentPayoneTransactionStatusLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentPayoneQuery()
    {
        return SpyPaymentPayoneQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createPaymentPayoneApiLogQuery()
    {
        return SpyPaymentPayoneApiLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery
     */
    public function createPaymentPayoneTransactionStatusLogOrderItemQuery()
    {
        return SpyPaymentPayoneTransactionStatusLogOrderItemQuery::create();
    }

}
