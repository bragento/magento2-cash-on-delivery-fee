<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Plugin\Order;

use Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Sales\Block\Order\Totals;
use Magento\Sales\Model\Order;

class AddCashOnDeliveryFeeToTotalsBlock
{
    public function afterGetOrder(Totals $subject, Order $order): Order
    {
        if (empty($subject->getTotals())) {
            return $order;
        }

        if ($subject->getTotal(CashOnDeliveryFee::TOTAL_CODE) !== false) {
            return $order;
        }

        if (0 < ($fee = $order->getExtensionAttributes()->getCashOnDeliveryFee())) {
            $subject->addTotalBefore(new DataObject([
                'code' => CashOnDeliveryFee::TOTAL_CODE,
                'base_value' => $order->getExtensionAttributes()->getBaseCashOnDeliveryFee(),
                'value' => $fee,
                'label' => __('Cash on Delivery Fee')
            ]), TotalsInterface::KEY_GRAND_TOTAL);
        }

        return $order;
    }
}
