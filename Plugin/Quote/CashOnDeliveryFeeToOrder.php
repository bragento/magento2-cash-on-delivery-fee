<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Plugin\Quote;

use Brandung\CashOnDeliveryFee\Model\Order\CashOnDeliveryFeeExtensionManagement;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Quote\Model\Quote\Address\ToOrder as QuoteAddressToOrder;
use Magento\Quote\Model\Quote\Address as QuoteAddress;

class CashOnDeliveryFeeToOrder
{
    /**
     * @var CashOnDeliveryFeeExtensionManagement
     */
    private $extensionManagement;

    public function __construct(CashOnDeliveryFeeExtensionManagement $extensionManagement)
    {
        $this->extensionManagement = $extensionManagement;
    }

    public function aroundConvert(
        QuoteAddressToOrder $subject,
        \Closure $proceed,
        QuoteAddress $quoteAddress,
        array $data = []
    ): OrderInterface {
        return $this->extensionManagement->setExtensionFromAddressData($proceed($quoteAddress, $data), $quoteAddress);
    }
}
