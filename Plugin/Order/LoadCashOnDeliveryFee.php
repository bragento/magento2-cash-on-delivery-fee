<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Plugin\Order;

use Brandung\CashOnDeliveryFee\Model\Order\CashOnDeliveryFeeExtensionManagement;
use Magento\Sales\Model\Order;

class LoadCashOnDeliveryFee
{
    /**
     * @var CashOnDeliveryFeeExtensionManagement
     */
    private $extensionManagement;

    public function __construct(CashOnDeliveryFeeExtensionManagement $extensionManagement)
    {
        $this->extensionManagement = $extensionManagement;
    }

    public function afterLoad(Order $subject, Order $returnedOrder): Order
    {
        return $this->extensionManagement->setExtensionFromData($returnedOrder);
    }
}
