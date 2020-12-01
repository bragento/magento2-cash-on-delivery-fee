<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Model\Total;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Phrase;
use Magento\OfflinePayments\Model\Cashondelivery;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Store\Model\ScopeInterface;

class CashOnDeliveryFee extends AbstractTotal
{
    const CONFIG_PATH_FEE_AMOUNT = 'payment/cashondelivery/fee';

    const TOTAL_CODE = 'cash_on_delivery_fee';
    const BASE_TOTAL_CODE = 'base_cash_on_delivery_fee';

    const LABEL = 'Cash On Delivery Fee';
    const BASE_LABEL = 'Base Cash On Delivery Fee';

    /**
     * @var float
     */
    private $fee;
    private $baseCurrency;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory
    )
    {
        $this->fee = (float)$scopeConfig->getValue(static::CONFIG_PATH_FEE_AMOUNT, ScopeInterface::SCOPE_STORE);
        $currencyCode = $scopeConfig->getValue("currency/options/base", ScopeInterface::SCOPE_WEBSITES);
        $this->baseCurrency =  $currencyFactory->create()->load($currencyCode);
    }

    public function collect(
        Quote $quote,
        ShippingAssignmentInterface
        $shippingAssignment,
        Total $total
    ): CashOnDeliveryFee {
        parent::collect($quote, $shippingAssignment, $total);

        if (count($shippingAssignment->getItems()) == 0) {
            return $this;
        }

        $baseCashOnDeliveryFee = $this->getFee($quote);
        $currency = $quote->getStore()->getCurrentCurrency();
        $cashOnDeliveryFee = $this->baseCurrency->convert($baseCashOnDeliveryFee, $currency);

        $total->setData(static::TOTAL_CODE, $cashOnDeliveryFee);
        $total->setData(static::BASE_TOTAL_CODE, $baseCashOnDeliveryFee);

        $total->setTotalAmount(static::TOTAL_CODE, $cashOnDeliveryFee);
        $total->setBaseTotalAmount(static::TOTAL_CODE, $baseCashOnDeliveryFee);

        return $this;
    }

    public function fetch(Quote $quote, Total $total): array
    {
        $base_value = $this->getFee($quote);
        if ($base_value) {
            $currency = $quote->getStore()->getCurrentCurrency();
            $value = $this->baseCurrency->convert($base_value, $currency);
        } else {
            $value = null;
        }
        return [
            'code' => static::TOTAL_CODE,
            'title' => static::LABEL,
            'base_value' => $base_value,
            'value' => $value
        ];
    }

    public function getLabel(): Phrase
    {
        return __(static::LABEL);
    }

    private function getFee(Quote $quote): float
    {
        if ($quote->getPayment()->getMethod() !== Cashondelivery::PAYMENT_METHOD_CASHONDELIVERY_CODE) {
            return (float)null;
        }

        return (float)$this->fee;
    }
}
