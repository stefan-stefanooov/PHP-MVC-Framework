<?php
namespace ShoppingCart;

class RequestTypeChecker {
    private $controllerName;
    private $actionName;
    private $requestType;
    private $controllerNameSpace;
    private $projectsFolder;
    private $requestTypeConfFile;

    const REQUEST_TYPE_CONFIG_PATH = 'Config\requestType.conf';
    const RESOURSE_NOT_IN_ANNOTATIONS = 0;

    public function __construct($controllerNameSpace, $controllerName, $actionName, $requestType){
        $this->controllerNameSpace = strtolower($controllerNameSpace);
        $this->controllerName = strtolower($controllerName);
        $this->actionName = strtolower($actionName);
        $this->requestType = strtolower($requestType);
        $this->projectsFolder = getcwd();
    }

    public function analise(){
        $this->requestTypeConfFile = $this->projectsFolder . DIRECTORY_SEPARATOR . self::REQUEST_TYPE_CONFIG_PATH;
        $fh = fopen($this->requestTypeConfFile, 'r');
        $requestTypeAnnotationsString = fgets($fh);
        fclose($fh);

        $annotations = explode("@#@", $requestTypeAnnotationsString);
        foreach ($annotations as $annotation) {
            if($annotation == ""){
                continue;
            }

            $annotationArr = explode(" ", $annotation);
            $controllerNameSpaceAnno =  strtolower(array_shift($annotationArr));
            $controllerNameAnno = strtolower(array_shift($annotationArr));
            $actionNameAnno = strtolower(array_shift($annotationArr));
            $an = $annotationArr[0];
            preg_match_all("/@@([A-Z]+)/", $an, $annotationRequestType);
            $diffArr = array_diff_assoc(
                [
                    $this->controllerNameSpace,
                    $this->controllerName,
                    $this->actionName
                ],
                [
                    $controllerNameSpaceAnno,
                    $controllerNameAnno,
                    $actionNameAnno
                ]);
            if(!$diffArr){
                if($this->requestType != strtolower($annotationRequestType[1][0])){
                    throw new \BadMethodCallException("Bad Method");
                    return false;
                }
            }
        }

        return true;
    }
}