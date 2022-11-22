<?php

namespace App\Model;

use PDO;
use App\Entity\Game;

class GameHasQuestionManager extends AbstractManager
{
    public const TABLE = 'game_has_question';

    public function insert(int $gameId, int $questionId, int $answerId, bool $isTrue, float $time)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (game_id, question_id, answer_id, is_true, time)
            VALUE (:game_id, :question_id, :answer_id, :is_true, :time)");
        $statement->bindValue('game_id', $gameId, PDO::PARAM_INT);
        $statement->bindValue('question_id', $questionId, \PDO::PARAM_INT);
        $statement->bindValue('answer_id', $answerId, PDO::PARAM_INT);
        $statement->bindValue('is_true', $isTrue, PDO::PARAM_INT);
        $statement->bindValue(':time', $time, PDO::PARAM_INT);
        $statement->execute();
    }

    public function selectAllUserAnswer($id): array
    {
        $query = "SELECT * FROM " . self::TABLE . " WHERE game_id = :id ;";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
