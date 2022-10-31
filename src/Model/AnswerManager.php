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

    /**
     *  UPDATE the answers associated with a question : ok
     **/
    public function update(array $questionsPost, $questions)
    {
        $answers = [];
        foreach ($questions['answers'] as $question) {
            array_push($answers, $question);
        }
        $answers[0]['answer'] = $questionsPost['goodAnswer'];
        $answers[1]['answer'] = $questionsPost['badAnswer1'];
        $answers[2]['answer'] = $questionsPost['badAnswer2'];
        $answers[3]['answer'] = $questionsPost['badAnswer3'];

        foreach ($answers as $answer) {
            $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
                " SET `content` = :content WHERE id=:id");
            $statement->bindValue(':id', $answer['id'], PDO::PARAM_INT);
            $statement->bindValue(':content', $answer['answer'], PDO::PARAM_STR);
            $statement->execute();
        }
        return $answers;
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
}
