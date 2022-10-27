<?php

namespace App\Model;

use PDO;

class QuestionManager extends AbstractManager
{
    public const TABLE = 'question';

    public function selectAllWithAnswer(string $orderBy = '', string $direction = 'ASC'): array
    {
        $answerManager = new AnswerManager();
        $questions = $this->selectAll($orderBy, $direction);
        foreach ($questions as &$question) {
            $answers = $answerManager->selectAllByQuestionsId($question['id']);
            foreach ($answers as $answer) {
                $question['answers'][$answer['answer']] = $answer['isTrue'];
            }
        }
        return $questions;
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

    public function update(array $questions): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `content` = :content WHERE id=:id");
        $statement->bindValue(':id', $questions['id'], PDO::PARAM_INT);
        $statement->bindValue(':content', $questions['content'], PDO::PARAM_STR);

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

    public function insert(array $questions): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`content`, `theme`) VALUES (:content, :theme)");
        $statement->bindValue(':content', $questions['content'], PDO::PARAM_STR);
        $statement->bindValue(':theme', $questions['theme'], PDO::PARAM_STR);

        $statementAnswer = $this->pdo->prepare("INSERT INTO answer (`content`, `theme`) VALUES (:content, :theme)");

        $statementAnswer->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
