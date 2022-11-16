<?php

namespace App\Model;

use PDO;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';

    /**
     * Insert new user in database
     */
    public function insert(string $nickname): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`username`) VALUES (:nickname)");
        $statement->bindValue('nickname', $nickname, PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
