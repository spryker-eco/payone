<?php
//phpcs:ignoreFile

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Yves\Payone\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;
use RuntimeException;

class PayoneToShipmentBridge implements PayoneToShipmentInterface
{
    /**
     * @var \Spryker\Client\Shipment\ShipmentClientInterface
     */
    protected $shipmentClient;

    /**
     * @param \Spryker\Client\Shipment\ShipmentClientInterface $shipmentClient
     */
    public function __construct($shipmentClient)
    {
        $this->shipmentClient = $shipmentClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        if (method_exists($this->shipmentClient, 'getAvailableMethodsByShipment') === true) {
            $shipmentMethodsCollectionTransfer = $this->shipmentClient->getAvailableMethodsByShipment($quoteTransfer);

            if ($shipmentMethodsCollectionTransfer->getShipmentMethods()->count() > 1) {
                throw new RuntimeException('Split shipping is not supported');
            }

            $shipmentMethodsTransfer = $shipmentMethodsCollectionTransfer
                ->getShipmentMethods()
                ->getIterator()
                ->current();

            return $shipmentMethodsTransfer;
        }

        return $this->shipmentClient->getAvailableMethods($quoteTransfer);
    }
}
