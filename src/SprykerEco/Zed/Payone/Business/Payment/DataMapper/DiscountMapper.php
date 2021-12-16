<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Payment\DataMapper;

use SprykerEco\Shared\Payone\PayoneApiConstants;
use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

class DiscountMapper implements DiscountMapperInterface
{
    /**
     * @var int
     */
    protected const ZERRO_ITEM_TAX_RATE = 0;

    /**
     * @var string
     */
    protected const DISCOUNT_PRODUCT_DESCRIPTION = 'Discount';

    /**
     * @var int
     */
    protected const ONE_ITEM_AMOUNT = 1;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer|\Generated\Shared\Transfer\QuoteTransfer $discountContainer
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return \SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer
     */
    public function mapDiscounts($discountContainer, AbstractRequestContainer $container): AbstractRequestContainer
    {
        $arrayIt = $container->getIt();
        $arrayId = $container->getId();
        $arrayPr = $container->getPr();
        $arrayNo = $container->getNo();
        $arrayDe = $container->getDe();
        $arrayVa = $container->getVa();

        $key = count($arrayId) + 1;

        $arrayIt[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_VOUCHER;
        $arrayId[$key] = PayoneApiConstants::INVOICING_ITEM_TYPE_VOUCHER;
        $arrayPr[$key] = - $discountContainer->getTotals()->getDiscountTotal();
        $arrayNo[$key] = static::ONE_ITEM_AMOUNT;
        $arrayDe[$key] = static::DISCOUNT_PRODUCT_DESCRIPTION;
        $arrayVa[$key] = static::ZERRO_ITEM_TAX_RATE;

        $container->setIt($arrayIt);
        $container->setId($arrayId);
        $container->setPr($arrayPr);
        $container->setNo($arrayNo);
        $container->setDe($arrayDe);
        $container->setVa($arrayVa);

        return $container;
    }
}
