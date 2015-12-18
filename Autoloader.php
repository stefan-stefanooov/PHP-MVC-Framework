<?php
namespace ShoppingCart;

class Autoloader
{
    public static function init()
    {
        spl_autoload_register(function($class) {

            //var_dump($class);

            $pathParams = explode("\\", $class);
            $path = implode(DIRECTORY_SEPARATOR, $pathParams);
            $path = str_replace($pathParams[0], "", $path);

            //var_dump($path);

            if(file_exists('C:\wamp\www\ShoppingCart' . $path . ".php")){
                require_once $path . '.php';
            }
        });
    }
}