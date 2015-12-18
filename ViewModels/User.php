<?php

namespace ShoppingCart\ViewModels;

class User{
    private $id;
    private $user;
    private $pass;
    private $cash;

    public function __construct($user, $pass, $id = null, $cash = null){
        $this->setId($id)
            ->setUsername($user)
            ->setPass($pass)
            ->setCash($cash);
    }


    /**
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    private function setId($id){
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername(){
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return $this
     */
    public function setUsername($user){
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPass(){
        return $this->pass;
    }

    /**
     * @param mixed $pass
     * @return $this
     */
    public function setPass($pass){
        $this->pass = $pass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCash(){
        return $this->cash;
    }

    /**
     * @param mixed $cash
     * @return $this
     */
    public function setCash($cash){
        $this->cash = $cash;
        return $this;
    }
}