<?php

namespace App\Model;

use PDO;

class QuestionManager extends AbstractManager
{
    public const TABLE = 'question';

    /**
     * SELECT all question & associates answers : ok !
    **/
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


    /* TODO */
    public function selectOneById(int $id): array|false
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM ' . self::TABLE . ' 
            INNER JOIN answer ON question.id=answer.question_id' . WHERE question.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    /* TODO */
    public function update(array $questions)
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE . " SET `content` = :content WHERE id=:id");
        $statement->bindValue(':id', $questions['id'], PDO::PARAM_INT);
        $statement->bindValue(':content', $questions['content'], PDO::PARAM_STR);

        $statement->execute();

        $temp = (int)$this->pdo->lastInsertId();

        $statement = $this->pdo->prepare("UPDATE answer SET `content` = :content WHERE id=:id");
        $statement->bindValue(':id', $temp, PDO::PARAM_INT);
        $statement->bindValue(':content', $questions['goodAnswer'], PDO::PARAM_STR);

        return;
    }

    /**
     * Delete question form an ID : ok !
    **/
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare('DELETE FROM ' . self::TABLE . ' WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Delete question form an ID : ok !
    **/
    public function insert(array $questions): int
    {
        $statement = $this->pdo->prepare('INSERT INTO ' . self::TABLE .
            ' (`content`, `theme`, `difficulty_level`) VALUES (:content, :theme, :level)');
        $statement->bindValue(':content', $questions['question'], PDO::PARAM_STR);
        $statement->bindValue(':theme', $questions['theme'], PDO::PARAM_STR);
        $statement->bindValue(':level', $questions['level'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
