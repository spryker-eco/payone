<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use Spryker\Shared\Shipment\ShipmentConfig;
use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

class ShipmentMapper implements ShipmentMapperInterface
{
    /**
     * @var string
     */
    protected const SHIPMENT_PRODUCT_DESCRIPTION = 'Shipment';

    /**
     * @var int
     */
    protected const ZERRO_ITEM_TAX_RATE = 0;

    /**
     * @var int
     */
    protected const ONE_ITEM_AMOUNT = 1;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $shipmentContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function mapShipment($shipmentContainer, AbstractRequestContainer $container): AbstractRequestContainer
    {
        $arrayIt = $container->getIt();
        $arrayId = $container->getId();
        $arrayPr = $container->getPr();
        $arrayNo = $container->getNo();
        $arrayDe = $container->getDe();
        $arrayVa = $container->getVa();

        $key = count($arrayId) + 1;

        $arrayIt[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_SHIPMENT;
        $arrayId[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_SHIPMENT;
        $arrayPr[$key] = $this->getDeliveryCosts($shipmentContainer);
        $arrayNo[$key] = static::ONE_ITEM_AMOUNT;
        $arrayDe[$key] = static::SHIPMENT_PRODUCT_DESCRIPTION;
        $arrayVa[$key] = static::ZERRO_ITEM_TAX_RATE;

        $container->setIt($arrayIt);
        $container->setId($arrayId);
        $container->setPr($arrayPr);
        $container->setNo($arrayNo);
        $container->setDe($arrayDe);
        $container->setVa($arrayVa);

        return $container;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $expenseContainer
     *
     * @return int
     */
    protected function getDeliveryCosts($expenseContainer): int
    {
        foreach ($expenseContainer->getExpenses() as $expense) {
            if ($expense->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                return (int) $expense->getSumGrossPrice();
            }
        }

        return 0;
    }
}
