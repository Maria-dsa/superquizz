<?php

namespace App\Entity;

use App\Model\GameManager;
use App\Model\UserManager;
use App\Model\QuestionManager;
use DateTime;

class Game
{
    // on doit avoir ici les mêmes propriétés que les champs de la table game
    private int $id;
    private int $type;
    private DateTime|string $createdAt;
    private DateTime|null|string $endedAt;
    private int $userId;
    private array $score = [];

    private int $currentQuestion = 0;
    private string $gameDuration;

    private array $questions;

    /**
     * Get the value of id
     */
    public function getScore(): array
    {
        return $this->score;
    }

    public function getScoreById($id): int
    {
        return $this->score[$id];
    }

    /**
     * Set the value of id
     */
    public function setScore($userAnswer): void
    {
        $this->score[] = $userAnswer;
    }





    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of created_at
     *
     * @return  self
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the value of ended_at
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    /**
     * Set the value of ended_at
     *
     * @return  self
     */
    public function setEndedAt(DateTime $endedAt)
    {
        $this->endedAt = $endedAt;
        $gameManager = new GameManager();
        $gameManager->updateEndedAt($this->id, $endedAt);
        return $this;
    }


    /**
     * Get the value of user_id
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getCurrentQuestion(): int
    {
        return $this->currentQuestion;
    }

    public function setCurrentQuestion(int $currentQuestion)
    {
        $this->currentQuestion = $currentQuestion;
    }

    public function incrementCurrentQuestion()
    {
        $this->currentQuestion++;
    }

    public function setQuestions(array $questions)
    {
        $this->questions = $questions;
    }




    public function selectOneQuestion(int $number): array
    {
        return $this->questions[$number];
    }

    /**
     * Get the value of questions
     */
    public function getQuestions(): array
    {
        return $this->questions;
    }

    /**
     * Get the value of gameDuration
     */
    public function getGameDuration(): string
    {
        return $this->gameDuration;
    }

    /**
     * Set the value of gameDuration
     *
     * @return  self
     */
    public function setGameDuration()
    {
        $this->gameDuration = $this->calculateGameDuration($this->createdAt, $this->endedAt);
        return $this;
    }

    public function calculateGameDuration(string $start, DateTime $end): string
    {
        $start = new DateTime($start);
        $interval = $end->diff($start);
        $test = floatval($interval->format('%a')) * 86400
            + floatval($interval->format('%h')) * 3600
            + floatval($interval->format('%m')) * 60
            + floatval($interval->format('%s'));
        return strval($test);
    }
}
