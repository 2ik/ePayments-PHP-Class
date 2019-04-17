# ePayments-PHP-Class
ePayments payment PHP class

Just use:

### Redirection to Payment Gateway:
```php
$ePayments = new ePayments();
$ePayments->setShopId('111');
$ePayments->setSecretKey('7gacvdarb98yKtJBK');
$ePayments->setOrderNumber(1);
$ePayments->setOrderSumAmount(10);
$ePayments->setOrderName('Pay client #1');

$ePayments->send(); // redirect
```

### Check operation:
```php
$ePayments = new ePayments();
$ePayments->setTestMode(false);
$ePayments->setUserName('UserName');
$ePayments->setPassword('Password');

if(!$ePayments->check($id, $OrderNumber)){

  // operation does not exist

} else {

  // operation exists

}
```


### Get operation:
```php
$ePayments = new ePayments();
$ePayments->setTestMode(false);
$ePayments->setUserName('UserName');
$ePayments->setPassword('Password');

var_dump($ePayments->getOperation($id));
