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

require '../libraries/cashpoint.php';

// Create the Cashpoint object
$cashpoint = new Cashpoint();

// Authenticate to the API
$cashpoint->authenticate('310d0042', 'b85ec2cbe7165b8608562ba546a027aa6dae7b2e');

// Create our credit card object
$credit_card = new Cashpoint_Credit_Card(array(
    'type'   => 'visa',
    'number' => 4899035848652006,
    'expiry' => '01/12',
    'cvv'    => 123
));

// ...and our address object
$address = new Cashpoint_Address(array(
    'street' => '1 Test Lane',
    'city' => 'Testtown',
    'region' => 'Testshire',
    'country' => 'GB',
    'postal_code' => 'TE1 1ST'
));

// ...and create our final transaction object with this data!
$transaction = new Cashpoint_Transaction(array(
    'first_name'    => 'Jamie',
    'last_name'     => 'Rumbelow',
    'amount'        => 1000,
    'currency'      => 'GBP',
    'credit_card'   => $credit_card,
    'address'       => $address
));

// Execute the transaction, and create a new payment.
try
{
    $response = $cashpoint->sale($transaction);
    echo "Successfully processed payment! (Transaction: " . $response->transaction() . ")";
}
catch (Cashpoint_Exception $e)
{
    echo "There was a problem! " . $e->getMessage();
}