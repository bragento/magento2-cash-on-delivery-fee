/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'Magento_Checkout/js/model/quote',
        'Brandung_CashOnDeliveryFee/js/model/resource-url-manager',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/full-screen-loader',
        'mage/storage',
        'underscore'
    ],
    function (quote, resourceUrlManager, customer, fullScreenLoader, storage, _) {
        'use strict';
        return function (paymentMethod) {
            var payload = {
                cartId: quote.getQuoteId(),
                billingAddress: quote.billingAddress(),
                paymentMethod: _.pick(
                    paymentMethod,
                    'method',
                    'additional_data',
                    'po_number',
                    'extension_attributes'
                )
            };

            if (!customer.isLoggedIn()) {
                payload.email = quote.guestEmail;
            }

            fullScreenLoader.startLoader();
            storage.post(
                resourceUrlManager.getSetPaymentAndGetTotalsUrl(quote),
                JSON.stringify(payload)
            ).done(function (response) {
                quote.setTotals(response);
            }).always(function () {
                fullScreenLoader.stopLoader();
            });
        };
    }
);
