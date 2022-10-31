<?php

namespace App\Model;

use PDO;

class AdminManager extends AbstractManager
{
    public const TABLE = 'user';

    public function checkUser(string $username)
    {
        // prepared request
        $statement = $this->pdo->prepare('SELECT * FROM ' . self::TABLE . ' WHERE username=:username');
        $statement->bindValue('username', $username, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }

    public function addPasswordToUser(array $loginInfos)
    {
        $statement = $this->pdo->prepare('UPDATE ' . self::TABLE . ' SET password=:password WHERE username=:username');
        $statement->bindValue('username', $loginInfos['username'], \PDO::PARAM_STR);
        $statement->bindValue('password', $loginInfos['password'], \PDO::PARAM_STR);
        $statement->execute();
    }


    /**
     * Insert new item in database
     */
    public function insert(array $item): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`title`) VALUES (:title)");
        $statement->bindValue('title', $item['title'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Update item in database
     */
    public function update(array $item): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `title` = :title WHERE id=:id");
        $statement->bindValue('id', $item['id'], PDO::PARAM_INT);
        $statement->bindValue('title', $item['title'], PDO::PARAM_STR);

        return $statement->execute();
    }
}
