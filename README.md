# ePayments-PHP-Class
ePayments payment PHP class

Just use:
```php
$ePayments = new ePayments();

$ePayments->setShopId('111');
$ePayments->setSecretKey('7gacvdarb98yKtJBK');
$ePayments->setOrderNumber(1);
$ePayments->setOrderSumAmount(10);
$ePayments->setOrderName('Pay client #1');

$ePayments->send();
```
