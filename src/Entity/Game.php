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
    private string $createdAt;
    private null|string $endedAt;
    private int $userId;
    private array $score = [];

    private DateTime $questionStartedAt;
    private array $questionsDuration = [];

    private int $currentQuestion = 0;
    private float $gameDuration;

    private array $questions;

    private bool $gameEnded = false;

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
    public function setEndedAt()
    {
        $gameManager = new GameManager();
        $gameManager->updateEndedAt($this->id);
        if ($this->type === 1) {
            $this->endedAt = $gameManager->selectEndedAtById($this->id);
        }
        return $this;
    }

    public function setEndedAtGame2()
    {
        $end = new DateTime($this->createdAt);
        $this->endedAt = $end->modify('+60 second')->format('Y-m-d H:i:s');
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
    public function getGameDuration(): float
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
        $start = new DateTime($this->createdAt);
        $end = new DateTime($this->endedAt);
        $this->gameDuration = $this->calculateGameDuration($start, $end);
        return $this;
    }

    public function calculateGameDuration(DateTime $start, DateTime $end): float
    {
        $interval = $end->diff($start);
        $duration = floatval($interval->format('%a')) * 86400
            + floatval($interval->format('%h')) * 3600
            + floatval($interval->format('%i')) * 60
            + floatval($interval->format('%s'))
            + floatval($interval->format('%f')) / 1000000;
        return $duration;
    }

    public function getQuestionStartedAt(): DateTime
    {
        return $this->questionStartedAt;
    }

    public function setQuestionStartedAt()
    {
        $this->questionStartedAt = new DateTime();

        return $this;
    }

    public function getQuestionsDuration(): array
    {
        return $this->questionsDuration;
    }

    public function setQuestionsDuration(): float
    {
        $duration = $this->calculateGameDuration($this->questionStartedAt, new DateTime());
        $this->questionsDuration[] = round($duration, 2);

        return round($duration, 2);
    }

    /**
     * Get the value of gameEnded
     */
    public function getGameEnded(): bool
    {
        return $this->gameEnded;
    }

    /**
     * Set the value of gameEnded
     *
     * @return  self
     */
    public function setGameEnded(bool $gameEnded)
    {
        $this->gameEnded = $gameEnded;

        return $this;
    }
}
