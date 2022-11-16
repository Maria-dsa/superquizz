<?php

namespace App\Model;

use PDO;

class AnswerManager extends AbstractManager
{
    public const TABLE = 'answer';

    /**
     *  SELECT All Answer by ID ok
     **/
    public function selectAllByQuestionsId(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT content AS answer, is_correct AS isTrue, id
            FROM " . static::TABLE . " WHERE question_id=:id ORDER BY isTrue DESC");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectAllIdsByQuestionId(int $id): array|false
    {
        $statement = $this->pdo->prepare("SELECT id FROM " . self::TABLE . " WHERE question_id=:id");
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $ids = [];
        foreach ($results as $result) {
            $ids[] = $result['id'];
        }
        return $ids;
    }

    /**
     *  UPDATE the answers associated with a question : ok
     **/
    public function update(array $questionInfos, array $ids)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
                        " SET content=:content, is_correct=:is_correct WHERE id=:id");
        $statement->bindValue(':content', $questionInfos['goodAnswer'], PDO::PARAM_STR);
        $statement->bindValue(':is_correct', 1, PDO::PARAM_INT);
        $statement->bindValue(':id', $ids[0], PDO::PARAM_INT);
        $statement->execute();

        for ($i = 1; $i <= 3; $i++) {
            $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
                        " SET content=:content, is_correct=:is_correct WHERE id=:id");
            $statement->bindValue(':content', $questionInfos['badAnswer' . $i], PDO::PARAM_STR);
            $statement->bindValue(':is_correct', 0, PDO::PARAM_INT);
            $statement->bindValue(':id', $ids[$i], PDO::PARAM_INT);
            $statement->execute();
        }
    }

    /**
     *  Insert at ID : fonctionnel
     **/
    public function insertAtId(array $questionInfos, int $id): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`content`, `is_correct`, `question_id`) VALUES (:content, :is_correct, :question_id)");
        $statement->bindValue(':content', $questionInfos['goodAnswer'], PDO::PARAM_STR);
        $statement->bindValue(':is_correct', 1, PDO::PARAM_INT);
        $statement->bindValue(':question_id', $id, PDO::PARAM_INT);
        $statement->execute();

        for ($i = 1; $i <= 3; $i++) {
            $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
                " (`content`, `is_correct`, `question_id`) VALUES (:content, :is_correct, :question_id)");
            $statement->bindValue(':content', $questionInfos['badAnswer' . $i], PDO::PARAM_STR);
            $statement->bindValue(':is_correct', 0, PDO::PARAM_INT);
            $statement->bindValue(':question_id', $id, PDO::PARAM_INT);
            $statement->execute();
        }
        return (int)$this->pdo->lastInsertId();
    }

    /**
     *  NO USE YET Delete row form an ID DELETE MADE BY Question Manager
     **/
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare('DELETE FROM ' . self::TABLE . ' WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
