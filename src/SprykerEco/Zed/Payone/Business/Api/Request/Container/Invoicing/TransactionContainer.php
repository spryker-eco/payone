<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing;

use SprykerEco\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class TransactionContainer extends AbstractContainer
{
    /**
     * @var string|null
     */
    protected $invoiceid;

    /**
     * @var string|null
     */
    protected $invoice_deliverymode;

    /**
     * @var string|null
     */
    protected $invoice_deliverydate;

    /**
     * @var string|null
     */
    protected $invoice_deliveryenddate;

    /**
     * @var string|null
     */
    protected $invoiceappendix;

    /**
     * @var array<\SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer>
     */
    protected $items = [];

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $i = 1;
        foreach ($this->items as $item) {
            /** @var \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer $item */
            $data = array_merge($data, $item->toArrayByKey($i));
            $i++;
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function hasItems(): bool
    {
        return (count($this->items) > 0);
    }

    /**
     * @param \SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer $item
     *
     * @return void
     */
    public function addItem(ItemContainer $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @param array<\SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer> $items
     *
     * @return void
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return array<\SprykerEco\Zed\Payone\Business\Api\Request\Container\Invoicing\ItemContainer>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param string $invoice_deliverydate
     *
     * @return void
     */
    public function setInvoiceDeliverydate(string $invoice_deliverydate): void
    {
        $this->invoice_deliverydate = $invoice_deliverydate;
    }

    /**
     * @return string|null
     */
    public function getInvoiceDeliverydate(): ?string
    {
        return $this->invoice_deliverydate;
    }

    /**
     * @param string $invoice_deliveryenddate
     *
     * @return void
     */
    public function setInvoiceDeliveryenddate(string $invoice_deliveryenddate): void
    {
        $this->invoice_deliveryenddate = $invoice_deliveryenddate;
    }

    /**
     * @return string|null
     */
    public function getInvoiceDeliveryenddate(): ?string
    {
        return $this->invoice_deliveryenddate;
    }

    /**
     * @param string $invoice_deliverymode
     *
     * @return void
     */
    public function setInvoiceDeliverymode(string $invoice_deliverymode): void
    {
        $this->invoice_deliverymode = $invoice_deliverymode;
    }

    /**
     * @return string|null
     */
    public function getInvoiceDeliverymode(): ?string
    {
        return $this->invoice_deliverymode;
    }

    /**
     * @param string $invoiceappendix
     *
     * @return void
     */
    public function setInvoiceappendix(string $invoiceappendix): void
    {
        $this->invoiceappendix = $invoiceappendix;
    }

    /**
     * @return string|null
     */
    public function getInvoiceappendix(): ?string
    {
        return $this->invoiceappendix;
    }

    /**
     * @param string $invoiceid
     *
     * @return void
     */
    public function setInvoiceid(string $invoiceid): void
    {
        $this->invoiceid = $invoiceid;
    }

    /**
     * @return string|null
     */
    public function getInvoiceid(): ?string
    {
        return $this->invoiceid;
    }
}
