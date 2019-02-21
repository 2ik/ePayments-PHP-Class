<?php
/**
 * ePayments library for PHP
 *
 * @link      https://github.com/2ik/ePayments-PHP-Class
 * @license   MIT
 * @copyright Copyright (c) 2019, 2ik (http://2ik.ru/)
 */

class ePayments {
  private $sandbox = true;
  private $shopId;
  private $secretKey;
  private $orderName;
  private $orderNumber;
  private $orderSumAmount;
  private $orderSumCurrency = 'USD';

  // Необязательные
  private $language;
  private $gatewayId;
  private $shopFailUrl;
  private $shopSuccessUrl;
  private $shopDefaultUrl;
  private $operationLifeTime;

  /**
   * Задает тестовый или боевой режим
   */
  public function setTestMode(bool $bool = true): bool{
    return $this->sandbox = $bool;
  }

  /**
   * Идентификатор интернет-магазина, который выдается при регистрации
   */
  public function setShopId(int $value): ?int {
    return $this->shopId = $value;
  }

  /**
   * Секретный ключ
   */
  public function setSecretKey(string $value): ?string {
    return $this->secretKey = $value;
  }

  /**
   * Уникальный номер счета в интернет-магазине.
   * Если платеж с таким номер заказа уже был успешно проведен, то повторные попытки оплаты будут отклонены.
   * К вводу допустимы любые символы. Длина от 1 до 64 символов
   */
  public function setOrderNumber(string $value): ?string {
    return $this->orderNumber = $value;
  }

  /**
   * Сумма платежа.
   * Разделитель - точка.
   * Количество знаков после точки зависит от валюты платежа (orderSumCurrency) и ее требований к разрядности (в соответствии с ISO-4217).
   * Например, для создания инвойса на 10 USD необходимо передать:
   * • orderSumAmount = 10.00
   * • orderSumCurrency = USD
   * При передаче некорректной разрядности для выбранной валюты в ответе будет возвращена ошибка 2002019
   */
  public function setOrderSumAmount(float $value): ?float {
    return $this->orderSumAmount = $value;
  }

  /**
   * Трехбуквенный ISO код валюты платежа
   */
  public function setOrderSumCurrency(string $value): ?string {
    return $this->orderSumCurrency = $value;
  }

  /**
   * Наименование платежа
   */
  public function setOrderName(string $value): ?string {
    return $this->orderName = $value;
  }

  /**
   * Идентификатор способа оплаты:
   * • 1 – банковская карта;
   * • 2 – кошелек ePayments.
   * Если параметр не будет передан, то покупателю откроется страница выбора платежного способа
   */
  public function setGatewayId(int $value): ?int {
    return $this->gatewayId = $value;
  }

  /**
   * Страница, на которую будет переадресован покупатель в случае успешного проведения платежа.
   */
  public function setShopSuccessUrl(string $value): ?string {
    return $this->shopSuccessUrl = $value;
  }

  /**
   * Страница, на которую будет переадресован покупатель в случае появления ошибки при проведении платежа.
   */
  public function setShopFailUrl(string $value): ?string {
    return $this->shopFailUrl = $value;
  }

  /**
   * Страница, на которую вернется покупатель при нажатии на кнопку "Назад в магазин"
   * до получения результата (т.е. покупатель по своей инициативе отказался от оплаты).
   */
  public function setShopDefaultUrl(string $value): ?string {
    return $this->shopDefaultUrl = $value;
  }

  /**
   * Язык платежной страницы. Возможные значения:
   * • En (по умолчанию) – английский;
   * • Ru – русский;
   * • ES – испанский
   */
  public function setLanguage(string $value): ?string {
    return $this->language = $value;
  }

  /**
   * Время действия ссылки до момента перехода на платежную страницу. Указывается в минутах.
   * Допустимо значение от 1 до 4320.
   */
  public function setOperationLifeTime(int $value): ?int {
    return $this->operationLifeTime = $value;
  }

  /**
   * Результат выполнения хеш-функции SHA512.
   * Строка формируется на стороне интернет-магазина с помощью следующих атрибутов платежной формы:
   * shopId;secretKey;orderNumber;orderSumAmount;orderSumCurrency
   * Разделитель атрибутов – точка с запятой «;».
   * Пробелы между атрибутами недопустимы
   */
  public function getSha512(): ?string{
    $parts = [
      $this->getShopId(),
      $this->getSecretKey(),
      $this->getOrderNumber(),
      $this->getOrderSumAmount(),
      $this->getOrderSumCurrency(),
    ];

    return hash('sha512', implode(';', $parts));
  }

  public function getShopId(): ?int {
    return $this->shopId;
  }

  public function getSecretKey(): ?string {
    return $this->secretKey;
  }

  public function getOrderNumber(): ?string {
    return $this->orderNumber;
  }

  public function getOrderSumAmount(): ?string {
    return number_format((float) $this->orderSumAmount, 2, '.', '');
  }

  public function getOrderSumCurrency(): ?string {
    return $this->orderSumCurrency;
  }

  public function getOrderName(): ?string {
    return $this->orderName;
  }

  public function getGatewayId(): ?int {
    return $this->gatewayId;
  }

  public function getShopSuccessUrl(): ?string {
    return $this->shopSuccessUrl;
  }

  public function getShopFailUrl(): ?string {
    return $this->shopFailUrl;
  }

  public function getShopDefaultUrl(): ?string {
    return $this->shopDefaultUrl;
  }

  public function getLanguage(): ?string {
    return $this->language;
  }

  public function getOperationLifeTime(): ?int {
    return $this->operationLifeTime;
  }

  public function getUrl(): ?array{

    $url = $this->sandbox ? 'https://test-ms.epayments.com' : 'https://ms.epayments.com';

    $query = http_build_query([
      'shopId' => $this->getShopId(),
      'orderNumber' => $this->getOrderNumber(),
      'orderSumAmount' => $this->getOrderSumAmount(),
      'orderSumCurrency' => $this->getOrderSumCurrency(),
      'orderName' => $this->getOrderName(),
      'sha512' => $this->getSha512(),
      'gatewayId' => $this->getGatewayId(),
      'shopSuccessUrl' => $this->getShopSuccessUrl(),
      'shopFailUrl' => $this->getShopFailUrl(),
      'shopDefaultUrl' => $this->getShopDefaultUrl(),
      'language' => $this->getLanguage(),
      'operationLifeTime' => $this->getOperationLifeTime(),
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . '/api/v1/public/paymentpage?' . $query);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    return $result;

  }

  /**
   * Редирект на платежную систему или вывод ошибки
   */
  public function send(): void {

    $link = $this->getUrl();

    if ($link['error']) {
      echo $link['error']['messages'][0];
    }

    if ($link['result']['urlToRedirect']) {
      header('Location: ' . $link['result']['urlToRedirect']);
      exit;
    }
    echo '. Create a ticket in panel';
  }
}
