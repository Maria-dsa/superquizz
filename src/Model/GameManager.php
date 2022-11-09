<?php

namespace App\Model;

use PDO;
use App\Entity\Game;

class GameManager extends AbstractManager
{
    public const TABLE = 'game';

    public function insert(array $game)
    {
        $query = 'INSERT INTO game (`type`, `createdAt`, `userId`) VALUES (:type, NOW(), :userId)';
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('type', $game['type']);
        $statement->bindValue('userId', $game['userId']);
        $statement->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * Get one row from database by ID.
     */
    public function selectOneGameById(int $id): Game|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchObject('App\Entity\Game');
    }
}
