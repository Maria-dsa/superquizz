<?php

namespace App\Model;

use PDO;

class QuestionManager extends AbstractManager
{
    public const TABLE = 'question';

    public function selectAll(string $orderBy = '', string $direction = 'ASC'): array
    {
        $query = "SELECT q.id, q.content AS question, a.content AS answer, q.theme, a.is_correct FROM question AS q INNER JOIN answer AS a ON q.id=a.question_id";
        if ($orderBy) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $direction;
        }

        return $this->pdo->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function selectOneById(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM ' . self::TABLE . ' INNER JOIN answer ON question.id=answer.question_id' . WHERE question.id=:id");
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
        $statement = $this->pdo->prepare("DELETE FROM ' . static::TABLE . ' INNER JOIN answer ON question.id=answer.question_id' . WHERE question.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public function insert(array $item): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`content`, `theme`) VALUES (:content, :theme)");
        $statement->bindValue(':content', $item['content'], PDO::PARAM_STR);
        $statement->bindValue(':theme', $item['theme'], PDO::PARAM_STR);

        $statementAnswer = $this->pdo->prepare("INSERT INTO answer (`content`, `theme`) VALUES (:content, :theme)");

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

}
