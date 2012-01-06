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
     * a URL, and return their response directly. Simple.
     */
    public function __call($method, $params)
    {
        if (isset($this->methods[$method]))
        {
            return $this->_make_request($this->methods[$method][0], $this->methods[$method][1], $params, TRUE);
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
        
        if ($method == 'GET')
        {
            $url = $url . ($params ? '?' . http_build_query($params) : '');
        }
        
        if (is_array($params))
		{
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
		}
		
		curl_setopt($curl, CURLOPT_URL, $url);
		
		$response = curl_exec($curl);
		
		if ($response === FALSE)
		{
            throw new Exception(curl_error($curl));
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