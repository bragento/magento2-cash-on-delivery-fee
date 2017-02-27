<?php
declare(strict_types = 1);
namespace Brandung\CashOnDeliveryFee\Service\V1;

use Brandung\CashOnDeliveryFee\Api\PaymentInformationManagementInterface;
use Magento\Checkout\Api\PaymentInformationManagementInterface as MagentoPaymentInformationManagementInterface;
use Magento\Quote\Api\CartTotalRepositoryInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Quote\Api\Data\TotalsInterface;

class PaymentInformationManagement implements PaymentInformationManagementInterface
{
    /**
     * @var MagentoPaymentInformationManagementInterface
     */
    private $paymentInformationManagement;
    /**
     * @var CartTotalRepositoryInterface
     */
    private $cartTotalRepository;

    public function __construct(
        MagentoPaymentInformationManagementInterface $paymentInformationManagement,
        CartTotalRepositoryInterface $cartTotalRepository
    ) {
        $this->paymentInformationManagement = $paymentInformationManagement;
        $this->cartTotalRepository = $cartTotalRepository;
    }

    /**
     * @inheritdoc
     */
    public function savePaymentInformationAndGetTotals(
        $cartId,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ): TotalsInterface {
        $this->paymentInformationManagement->savePaymentInformation(
            $cartId,
            $paymentMethod,
            $billingAddress
        );

        return $this->cartTotalRepository->get($cartId);
    }
}
