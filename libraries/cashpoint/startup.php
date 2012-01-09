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

// Check for cURL and JSON
if (!function_exists('curl_init'))
{
    throw new Cashpoint_Exception('Cashpoint requires the cURL PHP extension to be installed.');
}

if (!function_exists('json_decode'))
{
    throw new Cashpoint_Exception('Cashpoint requires the JSON PHP extension to be enabled.');
}