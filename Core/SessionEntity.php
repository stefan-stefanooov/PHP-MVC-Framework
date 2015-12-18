<?php
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 12/17/2015
 * Time: 11:48 PM
 */

namespace ShoppingCart\Core;


class SessionEntity
{
    private $name;

    /**
     * SessionEntity constructor.
     * @param $name
     * @param $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $_SESSION[$name] = $value;
    }

    public function val(){
        return $_SESSION[$this->name];
    }

    public function changeValue($value){
        $_SESSION[$this->name] = $value;
    }

    public function sessionUnset(){
        session_unset();
    }

    public function sessionDestroy(){
        session_destroy();
    }
}