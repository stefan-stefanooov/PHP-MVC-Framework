<?php
namespace ShoppingCart\Controllers;

use ShoppingCart\Application;
use ShoppingCart\Core\Cookies;
use ShoppingCart\Core\Request;
use ShoppingCart\Core\Session;
use ShoppingCart\Models\AppUser;

abstract class Controller
{
    const IDENTITY_CONFIG_PATH = 'Config\identity.conf';
    const DEFAULT_MODELS_NAMESPACE = 'ShoppingCart\\Models\\';

    private $identityConfFile;
    private $projectsFolder;

    public $Request;
    public $Session;
    public $Cookies;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->Request = new Request($_POST, $_GET);
        $this->Cookies = new Cookies($_COOKIE);
        $this->Session = new Session($_SESSION);
    }

    public function isLogged()
    {
        return isset($_SESSION['id']);
    }

    public function getUserModel()
    {
        $this->projectsFolder = getcwd();
        $this->identityConfFile = $this->projectsFolder . DIRECTORY_SEPARATOR . self::IDENTITY_CONFIG_PATH;
        $fh = fopen($this->identityConfFile , 'r');
        $userClassName = fgets($fh);
        fclose($fh);

        $userClassName = self::DEFAULT_MODELS_NAMESPACE . $userClassName;
        $userModel = new $userClassName();
        return new $userClassName();
    }
}