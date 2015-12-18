<?php
/**
 * Created by PhpStorm.
 * User: stefan.stefanov
 * Date: 10/3/2015
 * Time: 2:53 PM
 */

namespace ShoppingCart\BindingModels;

class EditUserBindingModel {
    private $username;
    private $password;
    private $confirm;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * @param mixed $confirm
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;
    }
}