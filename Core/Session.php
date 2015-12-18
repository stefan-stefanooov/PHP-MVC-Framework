<?php
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 12/17/2015
 * Time: 11:45 PM
 */

namespace ShoppingCart\Core;


class Session
{

    public $userId;

    /**
     * Session constructor.
     * @param $session
     */
    public function __construct($session)
    {
        $this->userId = new SessionEntity("id", $session["id"]);
    }

    public function Add($name, $value)
    {
        $this->$name =  new SessionEntity($name, $value);
    }
}