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

class Cashpoint_Credit_Card extends Cashpoint_Request_Data
{
    public function valid()
    {
        // The API will validate this, as will the gateway,
        // but the earlier we catch it, the better. We can use
        // a few regexes and a Luhn check to ensure the CC is valid.
        return $this->_verify_credit_card();
    }
    
    /**
     * Originally from http://stackoverflow.com/a/174772/39979
     */
    protected function _verify_credit_card(){
        $cards = array(
            "visa" => "(4\d{12}(?:\d{3})?)",
            "amex" => "(3[47]\d{13})",
            "jcb" => "(35[2-8][89]\d\d\d{10})",
            "maestro" => "((?:5020|5038|6304|6579|6761)\d{12}(?:\d\d)?)",
            "solo" => "((?:6334|6767)\d{12}(?:\d\d)?\d?)",
            "mastercard" => "(5[1-5]\d{14})",
            "switch" => "(?:(?:(?:4903|4905|4911|4936|6333|6759)\d{12})|(?:(?:564182|633110)\d{10})(\d\d)?\d?)",
        );
        $names = array_keys($cards);

        $matches = array();
        $pattern = "#^(?:".implode("|", $cards).")$#";
        $result = preg_match($pattern, str_replace(" ", "", $this->number), $matches);

        $type = ($result > 0) ? $names[sizeof($matches)-2] : false;

        if ($this->type !== $type || $this->_luhn_check($this->number) === FALSE)
        {
            throw new Cashpoint_Invalid_Credit_Card_Exception("Your credit card information didn't validate. Please check and try again.");
        }
        else
        {
            return TRUE;
        }
    }

    /**
     * https://gist.github.com/1287893
     */
    protected function _luhn_check($number) {
        settype($number, 'string');

        $sumTable = array(
            array(0,1,2,3,4,5,6,7,8,9),
            array(0,2,4,6,8,1,3,5,7,9));

        $sum = 0;
        $flip = 0;

        for ($i = strlen($number) - 1; $i >= 0; $i--)
        {
            $sum += $sumTable[$flip++ & 0x1][$number[$i]];
        }

        return $sum % 10 === 0;
    }
}