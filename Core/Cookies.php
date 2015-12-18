<?php
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 12/17/2015
 * Time: 10:44 PM
 */

namespace ShoppingCart\Core;

class Cookies
{
    public $phpSessionId;

    /**
     * Cookies constructor.
     * @param $cookies
     */
    public function __construct($cookies)
    {
        $this->phpSessionId =  new Cookie("PHPSESSID", $cookies["PHPSESSID"]);
    }

    public function Add($cookie_name, $cookie_value)
    {
        $this->$cookie_name =  new Cookie($cookie_name, $cookie_value);
    }
}