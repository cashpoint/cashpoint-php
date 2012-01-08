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

class Cashpoint_Request_Data
{
    /**
     * Takes an array, and sets the data as a class variable
     */
    public function __construct($data)
    {
        foreach ($data as $key => $value)
        {
            $this->$key = $value;
        }
    }
}