<?php
namespace ShoppingCart\Models\Identity;
/**
 * Created by PhpStorm.
 * User: Stefan Stefanov
 * Date: 11/26/2015
 * Time: 4:44 PM
 */
interface IUserIdentity
{
    public function register($username, $password);
    public function exists($username);
    public function login($username, $password);
    public function getInfo($id);
    public function edit($user, $pass, $id);
}