<?php

namespace App\Model;

use PDO;

class AdminManager extends AbstractManager
{
    public const TABLE = 'user';

    public function checkUser(string $username)
    {
        // prepared request
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE . ' WHERE username=:username AND role="admin"');
        $statement->bindValue('username', $username, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    public function addPasswordToUser(array $loginInfos)
    {
        $statement = $this->pdo->prepare('UPDATE ' . self::TABLE . ' SET password=:password WHERE username=:username');
        $statement->bindValue('username', $loginInfos['username'], \PDO::PARAM_STR);
        $statement->bindValue('password', hash('sha256', $loginInfos['password']), \PDO::PARAM_STR);
        $statement->execute();
    }

    public function checkUserAndPassword(array $loginInfos)
    {
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE .
            ' WHERE username=:username AND password=:password');
        $statement->bindValue('username', $loginInfos['username'], \PDO::PARAM_STR);
        $statement->bindValue('password', hash('sha256', $loginInfos['password']), \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
}
