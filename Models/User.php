<?php
namespace ShoppingCart\Models;

use ShoppingCart\Core\Database;
use ShoppingCart\Models\Identity;

class User implements Identity\IUserIdentity{
    public function register($username, $password){
        $db = Database::getInstance('app');

        if ($this->exists($username)) {
            throw new \Exception("User already registered");
        }

        $result = $db->prepare("
            INSERT INTO users (username, password)
            VALUES (?, ?);
        ");

        $result->execute(
            [
                $username,
                md5($password)
            ]
        );

        if ($result->rowCount() > 0) {
            return true;
        }

        throw new \Exception('Cannot register user');
    }

    public function exists($username)
    {
        $db = Database::getInstance('app');

        $result = $db->prepare("SELECT id FROM users WHERE username = ?");
        $result->execute([ $username ]);

        return $result->rowCount() > 0;
    }

    public function login($username, $password){
        $db = Database::getInstance('app');
        
        $result = $db->prepare("
            SELECT
                id, username, password, cash
            FROM
                users
            WHERE username = ?
        ");

        $result->execute([$username]);

        if ($result->rowCount() <= 0){
            throw new \Exception('Invalid username');
        }

        $userRow = $result->fetch();
        if (md5($password) == $userRow['password']) {
            return $userRow['id'];
        }

        throw new \Exception('Invalid credentials');
    }

    public function getInfo($id)
    {
        $db = Database::getInstance('app');

        $result = $db->prepare("
            SELECT
                *
            FROM
                users
            WHERE id = ?
        ");

        $result->execute([$id]);

        return $result->fetch();
    }

    public function edit($user, $pass, $id)
    {
        $db = Database::getInstance('app');

        $result = $db->prepare("UPDATE users SET password = ?, username = ? WHERE id = ?");
        $result->execute([
            md5($pass),
            $user,
            $id
        ]);
        return true;
    }
}

    