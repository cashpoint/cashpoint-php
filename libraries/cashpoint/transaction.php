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

class Cashpoint_Transaction extends Cashpoint_Request_Data {
    
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->ip_address = $_SERVER['REMOTE_ADDR'];
    }
    
}