<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Plugin\Order;

use Brandung\CashOnDeliveryFee\Model\Order\CashOnDeliveryFeeExtensionManagement;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;

class LoadCashOnDeliveryFeeOnCollection
{
    /**
     * @var CashOnDeliveryFeeExtensionManagement
     */
    private $extensionManagement;

    public function __construct(CashOnDeliveryFeeExtensionManagement $extensionManagement)
    {
        $this->extensionManagement = $extensionManagement;
    }

    public function afterGetItems(OrderCollection $subject, array $orders): array
    {
        return array_map(function (Order $order) {
            return $this->extensionManagement->setExtensionFromData($order);
        }, $orders);
    }
}
