<?php
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 12/17/2015
 * Time: 10:03 PM
 */

namespace ShoppingCart\Core;


class RequestParams
{
    /**
     * RequestParams constructor.
     */
    public function __construct($params)
    {
        $this->initForm($params);
    }

    private function initForm($params){
        foreach($params as $key => $val){
            $this->$key = $val;
        }
    }
}