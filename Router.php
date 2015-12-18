<?php
namespace ShoppingCart;

class Router {
    public $controllerNameSpace;
    public $area;
    public $controller;
    public $action;
    public $requestParams;

    private $projectsFolder;
    private $routeConfFile;

    const CONTROLLER_DEFAULT_NAMESPACE= 'ShoppingCart\\Controllers\\';
    const CONTROLLER_ARIAS_NAMESPACE= 'ShoppingCart\\Areas';
    const CONTROLLERS_SUFFIX = 'Controller';
    const CONTROLLERS_FOLDER_NAME = 'Controllers';
    const AREAS_FOLDER_NAME = 'Areas';
    const DEFAULT_CONTROLLER_NAME = 'User';
    const PHP_FILE_PREFIX = '.php';
    const ROUTER_CONFIG_PATH = 'Config\router.conf';


    public function __construct($requestParams){
        //var_dump($requestParams);
        $this->requestParams = $requestParams;
        $this->controllerNameSpace = self::CONTROLLER_DEFAULT_NAMESPACE;
        $this->projectsFolder = getcwd();
    }

    public function init(){
        if($this->annotationRoute()){
            return;
        }

        else{
            $this->route();
        }
    }

    private function route(){
        if(count($this->requestParams) >= 3){
            if($this->routeToArea()){
                return;
            }

            else{
                $this->routeToDefaultControllers();
            }
        }

        else{
            $this->routeToDefaultControllers();
        }
    }

    private function annotationRoute(){
        $this->routeConfFile = $this->projectsFolder . DIRECTORY_SEPARATOR . self::ROUTER_CONFIG_PATH;

        $fh = fopen($this->routeConfFile, 'r');
        $routeAnnotationsString = fgets($fh);
        fclose($fh);

        $annotations = explode("@#@", $routeAnnotationsString);
        foreach ($annotations as $annotation) {
            if($annotation == ""){
                continue;
            }

            $annotationArr = explode(" ", $annotation);
            $controllerNameSpaceAnno =  array_shift($annotationArr);
            $controllerNameAnno = array_shift($annotationArr);
            $actionNameAnno = array_shift($annotationArr);
            $an = $annotationArr[0];
            preg_match_all("/\(([^}]*)\)/", $an, $annotationPath);
            $annotationPathArr = explode("/", $annotationPath[1][0]);
            $diffArr = array_diff_assoc($this->requestParams, $annotationPathArr);
            //var_dump($diffArr);
            if(!$diffArr){
                //var_dump($controllerNameSpaceAnno, $controllerNameAnno, $actionNameAnno);
                $this->controllerNameSpace = $controllerNameSpaceAnno;
                $this->controller = $controllerNameAnno;
                $this->action = $actionNameAnno;
                return true;
            }
        }

        return false;
    }

    private function routeToDefaultControllers(){
        $requestParams = $this->requestParams;
        $controller = array_shift($requestParams);
        $action = array_shift($requestParams);
        $this->controller = $controller;
        $this->action = $action;
        $this->requestParams = $requestParams;
    }

    private function routeToArea(){
        $requestParams = $this->requestParams;
        $area = array_shift($requestParams);
        $controller = array_shift($requestParams);
        $action = array_shift($requestParams);
        if($this->initAreaController($area, $controller, $action)){
            $this->controllerNameSpace =
                self::CONTROLLER_ARIAS_NAMESPACE
                .DIRECTORY_SEPARATOR
                . ucfirst($area)
                . DIRECTORY_SEPARATOR
                ."Controllers"
                .DIRECTORY_SEPARATOR;
                //var_dump($this->controllerNameSpace);
            $this->controller = $controller;
            $this->action = $action;
            $this->requestParams = $requestParams;
            return true;
        }

        else{
            return false;
        }
    }

    private function initAreaController($area, $controller, $action) {

        $controllerName =
            self::CONTROLLER_ARIAS_NAMESPACE
            .DIRECTORY_SEPARATOR
            .ucfirst($area)
            . DIRECTORY_SEPARATOR
            . self::CONTROLLERS_FOLDER_NAME
            . DIRECTORY_SEPARATOR
            . ucfirst($controller)
            . self::CONTROLLERS_SUFFIX;

        $controllerFilePath =
            $this->projectsFolder
            . DIRECTORY_SEPARATOR
            . self::AREAS_FOLDER_NAME
            . DIRECTORY_SEPARATOR
            . ucfirst($area)
            .DIRECTORY_SEPARATOR
            .self::CONTROLLERS_FOLDER_NAME
            .DIRECTORY_SEPARATOR
            .ucfirst($controller)
            .self::CONTROLLERS_SUFFIX
            . self::PHP_FILE_PREFIX;
        //var_dump($controllerFilePath);

        if(file_exists($controllerFilePath)){

            $refController = new \ReflectionClass($controllerName);
            //var_dump($refController);

            try{
                $refController->getMethod($action);
            }catch(\ReflectionException $e) {
                return false;
            }
            return true;
        }
        else{
            return false;
        }
    }
}