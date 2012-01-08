# cashpoint.php

This class allows you to authenticate and connect to the [Cashpoint HTTPS API](https://getcashpoint.com/api/docs). It's officially supported by the Cashpoint team and its aim is to make connecting to Cashpoint as simple as possible.

## Synopsis

```php
$cashpoint = new Cashpoint();

$cashpoint->authenticate('api_key', 'api_secret');

$response = $cashpoint->sale(new Cashpoint_Transaction(array(
    'first_name' => 'Jamie',
    'last_name' => 'Rumbelow',
    'amount' => 10000,
    'currency' => 'GBP',
    'credit_card' => new Cashpoint_Credit_Card(array(
        'type'   => 'visa',
        'number' => 4899035848652006,
        'expiry' => '01/12',
        'cvv'    => 123
    )),
    'address' => new Cashpoint_Address(array(
        'street' => '1 Test Lane',
        'city' => 'Testtown',
        'region' => 'Testshire',
        'country' => 'GB',
        'postal_code' => 'TE1 1ST'
    ))
));

try
{
    $response = $cashpoint->sale($transaction);
    echo "Successfully processed payment! (Transaction: " . $response->transaction() . ")";
}
catch (Cashpoint_Exception $e)
{
    echo "There was a problem! " . $e->getMessage();
}
```