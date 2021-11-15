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
     * @var string
     */
    protected $invoiceid;

    /**
     * @var string
     */
    protected $invoice_deliverymode;

    /**
     * @var string
     */
    protected $invoice_deliverydate;

    /**
     * @var string
     */
    protected $invoice_deliveryenddate;

    /**
     * @var string
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
    public function setItems($items): void
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
    public function setInvoiceDeliverydate($invoice_deliverydate): void
    {
        $this->invoice_deliverydate = $invoice_deliverydate;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliverydate(): string
    {
        return $this->invoice_deliverydate;
    }

    /**
     * @param string $invoice_deliveryenddate
     *
     * @return void
     */
    public function setInvoiceDeliveryenddate($invoice_deliveryenddate): void
    {
        $this->invoice_deliveryenddate = $invoice_deliveryenddate;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliveryenddate(): string
    {
        return $this->invoice_deliveryenddate;
    }

    /**
     * @param string $invoice_deliverymode
     *
     * @return void
     */
    public function setInvoiceDeliverymode($invoice_deliverymode): void
    {
        $this->invoice_deliverymode = $invoice_deliverymode;
    }

    /**
     * @return string
     */
    public function getInvoiceDeliverymode(): string
    {
        return $this->invoice_deliverymode;
    }

    /**
     * @param string $invoiceappendix
     *
     * @return void
     */
    public function setInvoiceappendix($invoiceappendix): void
    {
        $this->invoiceappendix = $invoiceappendix;
    }

    /**
     * @return string
     */
    public function getInvoiceappendix(): string
    {
        return $this->invoiceappendix;
    }

    /**
     * @param string $invoiceid
     *
     * @return void
     */
    public function setInvoiceid($invoiceid): void
    {
        $this->invoiceid = $invoiceid;
    }

    /**
     * @return string
     */
    public function getInvoiceid(): string
    {
        return $this->invoiceid;
    }
}
