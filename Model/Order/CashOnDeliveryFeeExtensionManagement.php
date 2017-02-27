<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Model\Order;

use Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee;
use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Model\Order;

class CashOnDeliveryFeeExtensionManagement
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    public function __construct(OrderExtensionFactory $orderExtensionFactory)
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    public function setExtensionFromData(Order $order): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);

        $orderExtension->setCashOnDeliveryFee($order->getData(CashOnDeliveryFee::TOTAL_CODE));
        $orderExtension->setBaseCashOnDeliveryFee($order->getData(CashOnDeliveryFee::BASE_TOTAL_CODE));

        return $order;
    }

    public function setExtensionFromAddressData(Order $order, QuoteAddress $address): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);

        $orderExtension->setCashOnDeliveryFee($address->getData(CashOnDeliveryFee::TOTAL_CODE));
        $orderExtension->setBaseCashOnDeliveryFee($address->getData(CashOnDeliveryFee::BASE_TOTAL_CODE));

        return $order;
    }

    public function setDataFromExtension(Order $order): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);

        $order->setData(CashOnDeliveryFee::TOTAL_CODE, $orderExtension->getCashOnDeliveryFee());
        $order->setData(CashOnDeliveryFee::BASE_TOTAL_CODE, $orderExtension->getBaseCashOnDeliveryFee());

        return $order;
    }

    private function getOrInitOrderExtension(Order $order): OrderExtensionInterface
    {
        $orderExtension = $order->getExtensionAttributes();

        if ($orderExtension === null) {
            $orderExtension = $this->orderExtensionFactory->create();
            $order->setExtensionAttributes($orderExtension);

            return $orderExtension;
        }

        return $orderExtension;
    }
}
