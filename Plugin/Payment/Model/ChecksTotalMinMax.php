<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Plugin\Payment\Model;

use Brandung\CashOnDeliveryFee\Model\Total\CashOnDeliveryFee;
use Magento\OfflinePayments\Model\Cashondelivery;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Model\Quote;

/**
 * Cash for delivery max grandtotal limit has to take into account the fee. This is important if the grandtotal is near the limit
 * and the fee would put it over. On the first visit to checkout, grandtotal will not include the fee so the payment method would
 * be listed, but after selecting it the grandtotal would go over and the payment method is no longer selectable.
 */
class ChecksTotalMinMax
{
    /**
     * @see    \Magento\Payment\Model\Checks\TotalMinMax::isApplicable
     * @plugin around
     * @param \Magento\Payment\Model\Checks\TotalMinMax $subject
     * @param \Closure $proceed
     * @param MethodInterface $paymentMethod
     * @param Quote $quote
     * @return bool
     */
    public function aroundIsApplicable(\Magento\Payment\Model\Checks\TotalMinMax $subject,
                                       \Closure $proceed,
                                       MethodInterface $paymentMethod,
                                       Quote $quote)
    {
        $quoteTotals = $quote->getTotals();

        if ($paymentMethod->getCode() == Cashondelivery::PAYMENT_METHOD_CASHONDELIVERY_CODE &&
            isset($quoteTotals[CashOnDeliveryFee::TOTAL_CODE]) &&
            $quoteTotals[CashOnDeliveryFee::TOTAL_CODE]->getData('value') == 0)
        {
            $total = $quote->getGrandTotal() + $paymentMethod->getConfigData('fee');
            $minTotal = $paymentMethod->getConfigData($subject::MIN_ORDER_TOTAL);
            $maxTotal = $paymentMethod->getConfigData($subject::MAX_ORDER_TOTAL);

            if (!empty($minTotal) && $total < $minTotal || !empty($maxTotal) && $total > $maxTotal)
            {
                return false;
            }
            return true;
        } else
        {
            return $proceed($paymentMethod, $quote);
        }
    }
}