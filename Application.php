<?php
namespace ShoppingCart;

class Application {
    private $controllerName;
    private $actionName;
    public $requestParams = [];
    private $requestBindingModelInstance;
    private $controllerNameSpace;
    private $projectsFolder;

    private $controller;

    const CONTROLLERS_SUFFIX = 'Controller';
    const DEFAULT_CONTROLLER_NAME = 'User';
    const DEFAULT_ACTION_NAME = 'defaultAction';
    const PHP_FILE_PREFIX = '.php';

    public function __construct($controllerNameSpace, $controllerName, $actionName, $requestParams = []) {
        $this->controllerNameSpace = $controllerNameSpace;
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->requestParams = $requestParams;
        $this->projectsFolder = getcwd();
    }

    public function start() {
        //var_dump("APP start");
        $actionParametersClassName = '';
        $actionParametersClass = '';

        $this->initController();
        $reflectionController = new \ReflectionClass($this->controller);

        try{
            $action = $reflectionController->getMethod($this->actionName);
        }catch(\ReflectionException $e) {
            $this->actionName = self::DEFAULT_ACTION_NAME;
            $action = $reflectionController->getMethod($this->actionName);
        }

        if($action->getParameters() != null){
            $actionParametersClass = $action->getParameters()[0]->getClass();
            $actionParametersClassName = $actionParametersClass->getName();
        }

        if(preg_match('/\w+BindingModel/', $actionParametersClassName)){
            $bindModelInstance = new $actionParametersClassName();
            $this->requestBindingModelInstance = $bindModelInstance;
            $bindModelProperties = $actionParametersClass->getProperties();
            foreach ($bindModelProperties as $prop) {
                if(!isset($_POST[$prop->getName()])){
                    throw new \Exception("Parameters Do not respond to the Binding Model.");
                }

                $setterName = 'set' . $prop->getName();
                $bindModelInstance->$setterName(($_POST[$prop->getName()]));
            }

            $this->callControllerAction($this->requestBindingModelInstance);
        }
        else{
            $this->callControllerAction($this->requestParams);
        }
    }

    private function callControllerAction($params){

        View::$controllerName = $this->controllerName;
        View::$actionName = $this->actionName;
        $actionName = $this->actionName;
        $this->controller->$actionName($params);
    }

    private function initController() {
        $controllerName =
            //self::CONTROLLERS_NAMESPACE
            $this->controllerNameSpace
            . ucfirst($this->controllerName)
            . self::CONTROLLERS_SUFFIX;

        //var_dump($controllerName);
        if(
            file_exists(
                dirname($this->projectsFolder)
                . DIRECTORY_SEPARATOR
                . $controllerName
                . self::PHP_FILE_PREFIX) && $this->controllerName != ''
        ){
            //var_dump($controllerName);
            $this->controller = new $controllerName();
        }
        else{
                $controllerName =
                    //self::CONTROLLERS_NAMESPACE
                    $this->controllerNameSpace
                    . 'Home'
                    . self::CONTROLLERS_SUFFIX;
                $this->controller = new $controllerName();
        }
    }
}