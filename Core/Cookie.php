<?php
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 12/17/2015
 * Time: 11:10 PM
 */

namespace ShoppingCart\Core;


class Cookie
{
    private $cookie_name;
    /**
     * Cookie constructor.
     */
    public function __construct($name, $value)
    {
        $this->cookie_name = $name;
        setcookie($name, $value, time() + (86400 * 30), "/");
    }

    public function val(){
        return $_COOKIE[$this->cookie_name];
    }

    public function changeValue($value){
        $_COOKIE[$this->cookie_name] = $value;
    }

    public function delete(){
        setcookie($this->cookie_name, "", time() - 3600);
    }
}