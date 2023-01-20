[<img src="https://blockbee.io/static/assets/images/blockbee_logo_nospaces.png" width="300"/>](image.png)

# BlockBee's Checkout PHP Library
Official PHP library of BlockBee Checkout

## Requirements:

```
PHP >= 5.6
ext-curl
```

## Install

```
composer require blockbee/php-blockbee-checkout
```

[on GitHub](https://github.com/blockbee-io/php-blockbee-checkout) &emsp;
[on Composer](https://packagist.org/packages/blockbee/php-blockbee-checkout)

## Usage

To use our Checkout page, you will need to follow these steps:
* (1) Create an account on our Dashboard.
* (2) Create a "Company" within the account.
* (3) In the "Company" Settings, generate a new "API Key". 
* (4) Set up the Payment Settings.

### Requesting Payment

```php
<?php
require 'vendor/autoload.php'; // Where your vendor directory is

$bb = new BlockBee\Checkout($api_key, $parameters, $blockbee_params);
$payment_address = $bb->payment_request($redirect_url, $value);
```
Where:
* ``$api_key`` is the API Key provided by our [Dashboard](https://dash.blockbee.io/).
* ``$parameters`` is any parameter you wish to send to identify the payment, such as `['order_id' => 1234]`
* ``$blockbee_params`` parameters that will be passed to BlockBee _(check which extra parameters are available here: https://docs.blockbee.io/#operation/payment)
* ``$redirect_url`` URL in your platform, where the user will be redirected to following the payment. Should be able to process the payment using the `success_token`.
* ``$value`` amount in currency set in Payment Settings you want to receive from the user.

### Getting notified when the user completes the Payment
> When receiving payments, you have the option to receive them in either the ``notify_url`` or the ``redirect_url``, but adding the ``redirect_url``  is required (refer to our documentation at https://docs.blockbee.io/#operation/paymentipn).

### Requesting Deposit

```php
<?php
require 'vendor/autoload.php'; // Where your vendor directory is

$bb = new BlockBee\Checkout($api_key, $parameters, $blockbee_params);
$payment_address = $bb->deposit_request($notify_url);
```
Where:
* ``$api_key`` is the API Key provided by our [Dashboard](https://dash.blockbee.io/).
* ``$parameters`` is any parameter you wish to send to identify the payment, such as `['order_id' => 1234]`
* ``$blockbee_params`` parameters that will be passed to BlockBee _(check which extra parameters are available here: https://docs.blockbee.io/#operation/deposit)
* ``$notify_url`` URL in your platform, where the user will be redirected to following the payment. Should be able to process the payment using the `success_token`.


### Getting notified when the user makes a deposit
> When receiving deposits, you must provide a ``notify_url`` where our system will send an IPN every time a user makes a deposit (refer to our documentation at https://docs.blockbee.io/#operation/depositipn).

## Help

Need help?  
Contact us @ https://blockbee.io/contacts/

### Changelog

1.0.0
* Initial Release