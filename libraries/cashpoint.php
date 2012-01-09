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
 
// Request data
require_once 'cashpoint/request_data.php';
require_once 'cashpoint/address.php';
require_once 'cashpoint/credit_card.php';
require_once 'cashpoint/transaction.php';

// Exceptions
require_once 'cashpoint/exceptions/exception.php';
require_once 'cashpoint/exceptions/invalid_credit_card_exception.php';

// Response
require_once 'cashpoint/response.php';
 
class Cashpoint
{
    /**
     * The API version to use.
     */
    protected $version = 'beta';
    
    /**
     * The access_token parameter, returned by an 
     * authentication call.
     */
    protected $access_token = '';
    
    /**
     * A map of methods on the class to API methods.
     * This way we can significantly cut down on code.
     */
    protected $methods = array(
        'sale' => array('POST', '/authenticate')
    );
    
    /**
     * Instantiate a new instance of the class, optionally
     * authenticating.
     */
    public function __construct($api_key = FALSE, $api_secret = FALSE)
    {
        if ($api_key && $api_secret)
        {
            $this->authenticate($api_key, $api_secret);
        }
    }
    
    /**
     * Authenticates a user with an api_key and api_secret
     * and get/set the access_token
     */
    public function authenticate($api_key, $api_secret)
    {
        $response = $this->_make_request('POST', 'authenticate', array(
            'api_key' => $api_key,
            'api_secret' => $api_secret
        ));
        
        if ($response['success'])
        {
            $this->access_token = $response['access_token'];
            return $this->access_token;
        }
        else
        {
            return $response;
        }
    }
    
    /**
     * Get the access_token
     */
    public function access_token()
    {
        return $this->access_token;
    }
    
    /**
     * Magic methods! All of the other methods have an HTTP method and
     * a URL, and return their response directly. The Cashpoint_Response
     * object handles the response parsing & error throwing, so it's simple.
     */
    public function __call($method, $params)
    {
        if (isset($this->methods[$method]))
        {
            $request = $this->methods[$method][0] . ' ' . $this->methods[$method][1];
            $response = $this->_make_request($this->methods[$method][0], $this->methods[$method][1], $params, TRUE);
            
            return new Cashpoint_Response($response, $request);
        }
        
        return FALSE;
    }
    
    /**
     * Make an arbitrary cURL request
     */
    protected function _make_request($method, $url, $params = array(), $authenticated = TRUE)
    {
        $method = strtoupper($method);
        
        $url = $this->_build_url($url);
        
        if ($authenticated)
        {
            $params['access_token'] = $this->access_token();
        }
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        
        $http_data = http_build_query($params[0]);
        
        if ($method == 'GET')
        {
            $url = $url . ($params ? '?' . $http_data : '');
        }
        
        if (is_array($params))
        {
        	curl_setopt($curl, CURLOPT_POSTFIELDS, $http_data);
        }

        curl_setopt($curl, CURLOPT_URL, $url);

        $response = curl_exec($curl);

        if ($response === FALSE)
        {
            throw new Cashpoint_Exception(curl_error($curl));
        }

        curl_close($curl);

        return json_decode($response);
    }
    
    /**
     * Build a good URL
     */
    protected function _build_url($url)
    {
        return 'http://getcashpoint.com/api/' . $this->version . '/' . $url;
    }
}