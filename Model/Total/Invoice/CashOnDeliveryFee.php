<?php
declare(strict_types = 1);

namespace Brandung\CashOnDeliveryFee\Model\Total\Invoice;

class CashOnDeliveryFee extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal
{
    /**
     * @inheritdoc
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        parent::collect($invoice);

        $codFee = (float)$invoice->getOrder()->getExtensionAttributes()->getCashOnDeliveryFee();
        $baseCodFee = $invoice->getOrder()->getExtensionAttributes()->getBaseCashOnDeliveryFee();

        $invoice->setData(\Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee::TOTAL_CODE, $codFee);
        $invoice->setData(\Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee::BASE_TOTAL_CODE, $baseCodFee);

        if (round($codFee, 2) != 0)
        {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $codFee);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseCodFee);
        }

        return $this;
    }
}
