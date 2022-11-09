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

    public function selectQuestionsWithAnswer(int $number = 15): array
    {
        $query = 'SELECT * FROM ' . self::TABLE .  ' ORDER BY RAND() LIMIT :number';

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':number', $number, PDO::PARAM_INT);
        $statement->execute();
        $questions = $statement->fetchAll();
        $answerManager = new AnswerManager();
        foreach ($questions as &$question) {
            $question['answers'] = $answerManager->selectAllByQuestionsId($question['id']);
        }
        return $questions;
    }

    /**
     * SELECT a question and their answers with their id : ok
     **/
    public function selectOneWithAnswerForUpdate(int $id): array|false
    {
        $answerManager = new AnswerManager();
        $question = $this->selectOneById($id);

        if (!isset($question['id']) || empty($question['id'])) {
            return $question;
        }
        $answers = $answerManager->selectAllByQuestionsId($question['id']);

        $index = 1;
        foreach ($answers as $answer) {
            $question['answers']['answer' . $index] = $answer;
            $index++;
        }
        return $question;
    }

    /**
     * SELECT a question and their answers : ok
     **/
    public function selectOneWithAnswer(int $id): array|false
    {
        $answerManager = new AnswerManager();
        $question = $this->selectOneById($id);

        if (!isset($question['id']) || empty($question['id'])) {
            return $question;
        }
        $answers = $answerManager->selectAllByQuestionsId($question['id']);
        foreach ($answers as $answer) {
            $question['answers'][$answer['answer']] = $answer['isTrue'];
        }
        return $question;
    }

    /**
     * UPDATE all question and answer associated : ok
     **/
    public function update(array $questionsPost, $questions)
    {
        $answerManager = new AnswerManager();

        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
            " SET `content` = :question, `theme` = :theme, difficulty_level = :level WHERE id=:id");
        $statement->bindValue(':id', $questionsPost['id'], PDO::PARAM_INT);
        $statement->bindValue(':question', $questionsPost['question'], PDO::PARAM_STR);
        $statement->bindValue(':theme', $questionsPost['theme'], PDO::PARAM_STR);
        $statement->bindValue(':level', $questionsPost['level'], PDO::PARAM_STR);

        $statement->execute();

        return $answerManager->update($questionsPost, $questions);
    }

    /**
     * Delete question from an ID : ok !
     **/
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare('DELETE FROM ' . self::TABLE . ' WHERE id=:id');
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * Insert question in BDD ok !
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
