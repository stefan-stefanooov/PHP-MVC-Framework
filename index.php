<?php
session_start();
//var_dump($_COOKIE);
//return;
require_once 'Autoloader.php';

\ShoppingCart\Autoloader::init();

$appParser = new \ShoppingCart\AnnotationsParser();
$appParser->init();

//$request = new \ShoppingCart\Request($_SERVER);
//$request->analyze();

$uri = $_SERVER['REQUEST_URI'];
//var_dump($uri);
$self = $_SERVER['PHP_SELF'];

$directories = str_replace(basename($self), '', $self);
//var_dump($directories);
$requestString = str_replace($directories, '', $uri);
//var_dump($requestString);

$requestParams = explode("/", $requestString);
//var_dump($requestParams);
$router = new \ShoppingCart\Router($requestParams);
$router->init();

\ShoppingCart\Core\Database::setInstance(
    \ShoppingCart\Config\DatabaseConfig::DB_INSTANCE,
    \ShoppingCart\Config\DatabaseConfig::DB_DRIVER,
    \ShoppingCart\Config\DatabaseConfig::DB_USER,
    \ShoppingCart\Config\DatabaseConfig::DB_PASS,
    \ShoppingCart\Config\DatabaseConfig::DB_NAME,
    \ShoppingCart\Config\DatabaseConfig::DB_HOST
);

$identity = new  \ShoppingCart\IdentitySystem();
$identity->init();
//return;

$requestTypeChecker = new \ShoppingCart\RequestTypeChecker(
    $router->controllerNameSpace,
    $router->controller,
    $router->action,
    $_SERVER['REQUEST_METHOD']
);

//var_dump($router->controllerNameSpace);
//var_dump($router->controller);
//var_dump($router->action);


$authorizationAnalyzer = new \ShoppingCart\AuthorizationAnalyzer(
    $router->controllerNameSpace,
    $router->controller,
    $router->action
);
//var_dump($requestTypeChecker->analise());
//var_dump($authorizationAnalyzer->analise());
//return;

if($requestTypeChecker->analise() && $authorizationAnalyzer->analise()){
    $app = new \ShoppingCart\Application(
        $router->controllerNameSpace,
        $router->controller,
        $router->action,
        $router->requestParams
    );
    $app->start();
};

