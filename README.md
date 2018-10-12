
![alt text](https://www.agentur-brandung.de/fileadmin/img/logo-250x74.png "Brandung")

## Cash on Delivery Fee

this module adds a configurable fee for the Magento2 default cash on delivery payment Method

#### Install:

From Magento2 root dir:
```bash
composer require brandung/cash-on-delivery-fee
bin/magento module:enable Brandung_CashOnDeliveryFee
bin/magento setup:upgrade
```

also, if in production mode:
```bash
bin/magento setup:static-content:deploy
```

#### Usage

Go to Stores -> Configuration -> Sales -> Payment Methods
If the Module has been installed successfully, there should be a new Configuration "Cash on Delivery Fee" under the Cash on Delivery Payment Method with a default value of 0.0

The Fee total will display in the Checkout and Order totals as soon as the Cash on Delivery Payment Method has been used and the value of the Fee is > 0.
