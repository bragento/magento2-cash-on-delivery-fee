<?php
declare(strict_types = 1);

namespace Brandung\CashOnDeliveryFee\Model\Total\Creditmemo;

class CashOnDeliveryFee extends \Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal
{
    /**
     * @inheritdoc
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        parent::collect($creditmemo);

        $codFee = (float)$creditmemo->getOrder()->getExtensionAttributes()->getCashOnDeliveryFee();
        $baseCodFee = $creditmemo->getOrder()->getExtensionAttributes()->getBaseCashOnDeliveryFee();

        $creditmemo->setData(\Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee::TOTAL_CODE, $codFee);
        $creditmemo->setData(\Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee::BASE_TOTAL_CODE, $baseCodFee);

        if (round($codFee, 2) != 0)
        {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $codFee);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseCodFee);
        }

        return $this;
    }
}
