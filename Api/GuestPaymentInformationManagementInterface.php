<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Api;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;

interface GuestPaymentInformationManagementInterface
{
    /**
     * Set payment information for a specified cart.
     *
     * @param string $cartId
     * @param string $email
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @return \Magento\Quote\Api\Data\TotalsInterface
     */
    public function savePaymentInformationAndGetTotals(
        $cartId,
        $email,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    );
}
