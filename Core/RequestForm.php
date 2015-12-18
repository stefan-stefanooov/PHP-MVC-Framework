<?php
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 12/17/2015
 * Time: 10:02 PM
 */

namespace ShoppingCart\Core;


class RequestForm
{
    /**
     * RequestForm constructor.
     */
    public function __construct($form)
    {
        $this->initForm($form);
    }

    private function initForm($form){
        foreach($form as $key => $val){
            $this->$key = $val;
        }
    }
}