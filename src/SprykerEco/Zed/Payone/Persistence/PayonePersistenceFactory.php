<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Persistence;

use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLogQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItemQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerEco\Zed\Payone\Persistence\Propel\Mapper\PayonePersistenceMapper;

/**
 * @method \SprykerEco\Zed\Payone\PayoneConfig getConfig()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\Payone\Persistence\PayoneQueryContainerInterface getQueryContainer()
 */
class PayonePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogQuery
     */
    public function createPaymentPayoneTransactionStatusLogQuery(): SpyPaymentPayoneTransactionStatusLogQuery
    {
        return SpyPaymentPayoneTransactionStatusLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneQuery
     */
    public function createPaymentPayoneQuery(): SpyPaymentPayoneQuery
    {
        return SpyPaymentPayoneQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiLogQuery
     */
    public function createPaymentPayoneApiLogQuery(): SpyPaymentPayoneApiLogQuery
    {
        return SpyPaymentPayoneApiLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneTransactionStatusLogOrderItemQuery
     */
    public function createPaymentPayoneTransactionStatusLogOrderItemQuery(): SpyPaymentPayoneTransactionStatusLogOrderItemQuery
    {
        return SpyPaymentPayoneTransactionStatusLogOrderItemQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneApiCallLogQuery
     */
    public function createPaymentPayoneApiCallLogQuery(): SpyPaymentPayoneApiCallLogQuery
    {
        return SpyPaymentPayoneApiCallLogQuery::create();
    }

    /**
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayoneOrderItemQuery
     */
    public function createPaymentPayoneOrderItemQuery(): SpyPaymentPayoneOrderItemQuery
    {
        return SpyPaymentPayoneOrderItemQuery::create();
    }

    /**
     * @return \SprykerEco\Zed\Payone\Persistence\Propel\Mapper\PayonePersistenceMapper
     */
    public function createPayonePersistenceMapper(): PayonePersistenceMapper
    {
        return new PayonePersistenceMapper();
    }
}
