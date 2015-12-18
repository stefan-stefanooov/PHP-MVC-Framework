<?php
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 12/15/2015
 * Time: 3:41 PM
 */

namespace ShoppingCart\Core;

class Request
{
    public $params;
    public $form;

    /**
     * Request constructor.
     * @param $form
     * @param $params
     */
    public function __construct($form, $params)
    {
        $this->form = new RequestForm($form);
        $this->params = new RequestParams($params);
    }
}