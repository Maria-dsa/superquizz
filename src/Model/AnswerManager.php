<?php

namespace App\Model;

use PDO;

class AnswerManager extends AbstractManager
{
    public const TABLE = 'answer';

    public function selectAllByQuestionsId(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT content AS answer, is_correct AS isTrue 
            FROM " . static::TABLE . " WHERE question_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function selectOneById(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM ' . self::TABLE . ' 
            INNER JOIN answer ON question.id=answer.question_id' . WHERE question.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function update(array $item): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `content` = :content WHERE id=:id");
        $statement->bindValue(':id', $item['id'], PDO::PARAM_INT);
        $statement->bindValue(':content', $item['content'], PDO::PARAM_STR);

        return $statement->execute();
    }

        /**
     * Delete row form an ID
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare('DELETE FROM ' . self::TABLE . ' WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function insert(array $item): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`content`, `theme`) VALUES (:content, :theme)");
        $statement->bindValue(':content', $item['content'], PDO::PARAM_STR);
        $statement->bindValue(':theme', $item['theme'], PDO::PARAM_STR);

        $statementAnswer = $this->pdo->prepare("INSERT INTO answer (`content`, `theme`) VALUES (:content, :theme)");

        $statementAnswer->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
