<?php
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 11/26/2015
 * Time: 3:12 PM
 */

namespace ShoppingCart;

use ShoppingCart\Models\Identity;

class IdentitySystem
{
    const DEFAULT_MODELS_NAMESPACE = 'ShoppingCart\\Models\\';
    const MODELS_FILDER_NAME = 'Models';
    const IDENTITY_CONFIG_PATH = 'Config\identity.conf';

    private $projectsFolder;
    private $modelsFolder;
    private $identityConfFile;

    public function __construct(){
        $this->projectsFolder = getcwd();
        $this->modelsFolder = $this->projectsFolder  . DIRECTORY_SEPARATOR . self::MODELS_FILDER_NAME;
    }

    public function init()
    {
        $this->identityConfFile = $this->projectsFolder . DIRECTORY_SEPARATOR . self::IDENTITY_CONFIG_PATH;
        $this->confFileCleanup();

        $modelsFolderFiles = scandir($this->modelsFolder);
        foreach($modelsFolderFiles as $file) {
            if($file != "." && $file != ".." && $file != "Identity" && $file != "User.php"){
                $fileName = explode(".", $file);
                //var_dump($fileName[0]);
                $refModel = new \ReflectionClass("\\ShoppingCart\\Models\\" . $fileName[0]);
                if ($refModel->implementsInterface("ShoppingCart\\Models\\Identity\\IUserIdentity")
                    || $refModel->isSubclassOf("User")){
                    file_put_contents($this->identityConfFile, $fileName[0] , FILE_APPEND | LOCK_EX);
                    //$this->dbUpdate($refModel);
                }

                $docComment = $refModel->getDocComment();
                $this->parseAnotations($docComment);
            }
        }
    }

    private function parseAnotations($docComment){
        preg_match_all("/@@.+/", $docComment, $matches);
        if(count($matches[0]) == 0){
            return;
        }

        foreach ($matches[0] as $annotation) {
            preg_match_all("/@@([A-Za-z]*)\\(([A-Za-z\\(\\d\\)]*)\\)/", $annotation, $matches);
            try{
                $this->dbUpdate($matches[1][0], $matches[2][0]);
            }catch (\InvalidArgumentException $e){
                echo 'ERROR : ',  $e->getMessage(), "\n";
            }

        }
    }

    private function confFileCleanup(){
        if (file_exists($this->identityConfFile)) {
            unlink($this->identityConfFile);
        }
    }

    public function dbUpdate($property, $dataType){
        $db = \ShoppingCart\Core\Database::getInstance('app');
        //var_dump("call addColum(" . "'" .$property . "'" . ", " . "'" .  $dataType . "'" . ")");
        $result = $db->prepare(
            "call addColum2(" . "'" .$property . "'" . ", " . "'" .  $dataType . "'" . ")"
        );
        if(!$result->execute()){
            throw new \InvalidArgumentException("Invalid property definition!");
        }
    }
}