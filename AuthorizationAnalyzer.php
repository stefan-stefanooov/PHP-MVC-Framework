<?php
namespace ShoppingCart;

class AuthorizationAnalyzer {
    private $controllerName;
    private $actionName;
    private $requestType;
    private $controllerNameSpace;
    private $projectsFolder;
    private $authorizationConfFile;

    const ADMIN_ROLE_IDENTIFIER = 1;
    const MODERATOR_ROLE_IDENTIFIER = 2;
    const AUTHORIZATION_CONFIG_PATH = 'Config\authorize.conf';

    public function __construct($controllerNameSpace, $controllerName, $actionName){
        $this->controllerNameSpace = strtolower($controllerNameSpace);
        $this->controllerName = strtolower($controllerName);
        $this->actionName = strtolower($actionName);
        $this->projectsFolder = getcwd();
    }

    public function analise(){
        $this->authorizationConfFile = $this->projectsFolder . DIRECTORY_SEPARATOR . self::AUTHORIZATION_CONFIG_PATH;
        $fh = fopen($this->authorizationConfFile, 'r');
        $authAnnotationsString = fgets($fh);
        fclose($fh);

        $annotations = explode("@#@", $authAnnotationsString );
        foreach ($annotations as $annotation) {
            if($annotation == ""){
                continue;
            }

            $annotationArr = explode(" ", $annotation);
            $controllerNameSpaceAnno =  strtolower(array_shift($annotationArr));
            $controllerNameAnno = strtolower(array_shift($annotationArr));
            $actionNameAnno = strtolower(array_shift($annotationArr));
            $an = $annotationArr[0];
            preg_match_all("/@@([A-Za-z]+)/", $an, $annotationAuth);
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
            var_dump($diffArr);
            if(!$diffArr){
                //var_dump($annotationAuth[1][0]);
                //var_dump($_SESSION['id'] );
                if($annotationAuth[1][0] == 'Admin' && $_SESSION['id'] == self::ADMIN_ROLE_IDENTIFIER){
                    //throw new \BadMethodCallException("Unauthorized!");
                    return true;
                }
                elseif(
                    ($annotationAuth[1][0] == 'Moderator')
                    && ($_SESSION['id'] == self::MODERATOR_ROLE_IDENTIFIER ||  $_SESSION['id'] == self::ADMIN_ROLE_IDENTIFIER)
                ){
                    //throw new \BadMethodCallException("Unauthorized!");
                    return true;
                }
                elseif($annotationAuth[1][0] == 'Authorize' && isset($_SESSION['id'])){
                    //throw new \BadMethodCallException("Unauthorized!");
                    return true;
                }

                return false;
            }
        }

        return true;
    }
}