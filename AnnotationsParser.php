<?php

namespace ShoppingCart;

class AnnotationsParser {

    const DEFAULT_CONTROLLERS_NAMESPACE = 'ShoppingCart\\Controllers\\';
    const AREAS_NAMESPACE = 'ShoppingCart\\Areas';
    const CONTROLLERS_FOLDER_NAME = 'Controllers';
    const AREAS_FOLDER_NAME = 'Areas';
    const ROUTER_CONFIG_PATH = 'Config\router.conf';
    const REQUEST_TYPE_CONFIG_PATH = 'Config\requestType.conf';
    const AUTHORIZE_CONFIG_PATH = 'Config\authorize.conf';

    private $routeConfFile;
    private $requestTypeConfFile;
    private $authorizeConfFile;
    private $defaultControllersFolder;
    private $defaultAreasFolder;
    private $projectsFolder;

    public function __construct(){
        $this->projectsFolder = getcwd();
    }

    public function init(){

        $this->routeConfFile = $this->projectsFolder . DIRECTORY_SEPARATOR . self::ROUTER_CONFIG_PATH;
        $this->requestTypeConfFile = $this->projectsFolder . DIRECTORY_SEPARATOR . self::REQUEST_TYPE_CONFIG_PATH;
        $this->authorizeConfFile = $this->projectsFolder . DIRECTORY_SEPARATOR . self::AUTHORIZE_CONFIG_PATH;
        $this->confFileCleanup();

        $this->defaultAreasFolder = $this->projectsFolder . DIRECTORY_SEPARATOR . self::AREAS_FOLDER_NAME;
        $areasFolders = scandir($this->defaultAreasFolder);
        foreach ($areasFolders as $areaFolder) {
            if($areaFolder != "." && $areaFolder != ".."){
                $areaPath = $this->defaultAreasFolder . DIRECTORY_SEPARATOR . $areaFolder . DIRECTORY_SEPARATOR . self::CONTROLLERS_FOLDER_NAME;
                $areaControllersFolderFiles = scandir($areaPath);
                $controllersNameSpace =
                    self::AREAS_NAMESPACE
                    .DIRECTORY_SEPARATOR
                    .$areaFolder
                    .DIRECTORY_SEPARATOR
                    .self::CONTROLLERS_FOLDER_NAME
                    .DIRECTORY_SEPARATOR;
                $this->controllersFolderParser($areaControllersFolderFiles, $controllersNameSpace);
            }
        }

        $this->defaultControllersFolder = $this->projectsFolder . DIRECTORY_SEPARATOR . self::CONTROLLERS_FOLDER_NAME;
        $controllersFolderFiles = scandir($this->defaultControllersFolder);
        $this->controllersFolderParser($controllersFolderFiles, self::DEFAULT_CONTROLLERS_NAMESPACE);
    }

    private function confFileCleanup(){
        if (file_exists($this->routeConfFile)) {
            unlink($this->routeConfFile);
        }

        if (file_exists($this->requestTypeConfFile)) {
            unlink($this->requestTypeConfFile);
        }

        if (file_exists($this->authorizeConfFile)) {
            unlink($this->authorizeConfFile);
        }
    }

    private function controllersFolderParser($controllersFolderFiles, $controllersFolderNameSpace){
        foreach($controllersFolderFiles as $file) {
            if(preg_match('/^.+Controller\.(php)$/', $file)){
                $controllerNamesArr = explode(".", $file);
                preg_match_all('/(.+)(?:Controller)/', $controllerNamesArr[0],$controllerName );
                //
                //var_dump($controllerName);
                $controllerRef = new \ReflectionClass($controllersFolderNameSpace . $controllerName[0][0]);
                $controllerMethodsRef = $controllerRef->getMethods();
                foreach ($controllerMethodsRef as $methodReflection) {
                    //var_dump($methodReflection);
                    $this->methodDocParse(
                        $methodReflection,
                        $controllersFolderNameSpace,
                        $controllerName[1][0]);
                }
            }
        }
    }

    private function methodDocParse($methodReflection, $controllersFolderNameSpace, $controllerName){
        $methodComments = $methodReflection->getDocComment();
        preg_match_all("/@@.+/", $methodComments, $matches);
        if(count($matches[0]) == 0){
            return;
        }

        foreach ($matches[0] as $annotation) {
            $confString =
                $controllersFolderNameSpace
                ." "
                . $controllerName
                . " "
                . $methodReflection->getName()
                . " "
                .$annotation
                ."@#@";
            if(preg_match("/@@Route.+/", $annotation)){
                file_put_contents($this->routeConfFile, $confString, FILE_APPEND | LOCK_EX);
            }

            elseif(
                preg_match("/@@Authorize.*/", $annotation)
                || preg_match("/@@Admin.*/", $annotation)
                || preg_match("/@@Moderator.*/", $annotation)
            ){
                file_put_contents($this->authorizeConfFile, $confString, FILE_APPEND | LOCK_EX);
            }
            else{
                file_put_contents($this->requestTypeConfFile, $confString, FILE_APPEND | LOCK_EX);
            }
        }
    }
}