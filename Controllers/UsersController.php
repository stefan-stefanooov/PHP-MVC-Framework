<?php
namespace ShoppingCart\Controllers;

use ShoppingCart\BindingModels\EditUserBindingModel;
use ShoppingCart\Models\User;
use ShoppingCart\View;
use ShoppingCart\ViewModels\LoginInformation;
use ShoppingCart\ViewModels\RegisterInformation;

class UsersController extends Controller
{
    public function login()
    {
        $viewModel = new LoginInformation();

        if (isset($_POST['username'], $_POST['password'])) {
            try {
                $user = $_POST['username'];
                $pass = $_POST['password'];

                $this->initLogin($user, $pass);
            } catch (\Exception $e) {
                $viewModel->error = $e->getMessage();
                return new View($viewModel);
            }
        }

        return new View($viewModel);
    }

    private function initLogin($user, $pass)
    {
        $userModel = $this->getUserModel();
        $userId = $userModel->login($user, $pass);
        $_SESSION['id'] = $userId;
        header("Location: profile");
    }

    /**
     */
    public function register()
    {
        $viewModel = new RegisterInformation();

        if (isset($_POST['username'], $_POST['password'])) {
            try {
                $user = $_POST['username'];
                $pass = $_POST['password'];

                $userModel = new User();
                $userModel->register($user, $pass);

                $this->initLogin($user, $pass);
            } catch (\Exception $e) {
                var_dump("HAHAHA");
                $viewModel->error = $e->getMessage();
                return new View($viewModel);
            }
        }

        return new View($viewModel);
    }

    /**
     * @return View
     * @@Authorize
     * @@Route(profile/profile/init)
     */
    public function profile()
    {
        if (!$this->isLogged()) {
            header("Location: login");
        }

        $userModel = $this->getUserModel();
        //var_dump($userModel);
        $userInfo = $userModel->getInfo($_SESSION['id']);
        $userViewModel = new \ShoppingCart\ViewModels\User(
            $userInfo['username'],
            $userInfo['password'],
            $userInfo['id'],
            $userInfo['cash']
        );

        return new View($userViewModel);
    }

    /**
     * @return View
     * @@Route(rfrfrf/rfrffr/rfrf)
     * @@POST
     */
    public function editProfile(EditUserBindingModel $bindingModel){
        $userModel = new User();
        $userInfo = $userModel->getInfo($_SESSION['id']);
        $userViewModel = new \ShoppingCart\ViewModels\User(
            $userInfo['username'],
            $userInfo['password'],
            $userInfo['id'],
            $userInfo['cash']
        );

        if (true) {
            if ($_POST['password'] != $_POST['confirm'] || empty($_POST['password'])) {
                echo json_encode("Error");
                return;
            }

            if ($userModel->edit(
                $_POST['username'],
                $_POST['password'],
                $_SESSION['id']
            )) {
                $userViewModel->success = 1;
                $userViewModel->setUsername($_POST['username']);
                $userViewModel->setPass($_POST['password']);
                echo json_encode("Success");
                return;
            }
        }
    }
}