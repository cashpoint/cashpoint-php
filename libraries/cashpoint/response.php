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

class Cashpoint_Response
{
    /**
     * The raw response data
     */
    public $data = array();
    
    /**
     * Was the response a success?
     */
    public $success = NULL;
    
    /**
     * The request string (in form of ':verb :path')
     */
    public $request = '';
    
    /**
     * Create a new instance of the response object.
     */
    public function __construct($data, $request = '')
    {
        $this->data = $data;
        $this->request = $request;
        $this->success = $data['success'];
        
        if (!$this->success())
        {
            $this->_handle_error();
        }
    }
    
    /**
     * Was the response a success?
     */
    public function success()
    {
       return $this->success; 
    }
    
    /**
     * Return the request string
     */
    public function request()
    {
       return $this->request; 
    }
    
    /**
     * A simple getter for the response data
     */
    public function __call($method, $params)
    {
        if (isset($this->data[$method]))
        {
            return $method;
        }
    }
    
    /**
     * The response wasn't successful... so what went wrong?
     */
    protected function _handle_error()
    {
        // @todo Use more specific exception classes... although this requires that the API has a rethink
        throw new Cashpoint_Exception($this->data['error']);
    }
}