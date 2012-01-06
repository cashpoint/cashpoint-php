# cashpoint.php

This class allows you to authenticate and connect to the [Cashpoint HTTPS API](https://getcashpoint.com/api/docs). It's officially supported by the Cashpoint team and its aim is to make connecting to Cashpoint as simple as possible.

## Synopsis

```php
$cashpoint = new Cashpoint();

$cashpoint->authenticate('api_key', 'api_secret');

$response = $cashpoint->sale(array(
    'first_name' => 'Jamie',
    'last_name' => 'Rumbelow',
    'amount' => 10000,
    'currency' => 'GBP',
    'credit_card' => array(
        'type' => 'visa',
        'number' => 0000000000000000,
        'expiry' => '01/12',
        'cvv' => 123
    ),
    'address' => array(
        'street' => '1 Test Lane',
        'city' => 'Testtown',
        'region' => 'Testshire',
        'country' => 'GB',
        'postal_code' => 'TE1 1ST'
    )
));

if ($response['success'])
{
    echo "Successfully processed payment! (Transaction: " . $response['transaction'] . ")";
}
else
{
    echo "There was a problem! " . $response['error'];
}
```