<?php
/**
 * cashpoint.php
 *
 * The official Cashpoint PHP API library
 *
 * @link https://github.com/cashpoint/cashpoint-php
 * @version 1.0.0.beta
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @copyright Copyright (c) 2012, Cashpoint <https://getcashpoint.com>
 * @license MIT
 */

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