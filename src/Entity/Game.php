<?php

namespace App\Entity;

use App\Model\QuestionManager;
use App\Model\UserManager;

class Game
{
    // on doit avoir ici les mêmes propriétés que les champs de la table game
    private int $id;
    private int $type;
    private string $createdAt;
    private string|null $endedAt;
    private int $userId;
    private int $score = 0; //@todo getter et setter

    private int $currentQuestion = 0;

    private array $questions;

    // private QuestionManager $questionManager;

    // public function __construct()
    // {
    //     $this->questionManager = new QuestionManager();
    // }

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
    public function setEndedAt($endedAt)
    {
        $this->endedAt = $endedAt;
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

    public function getCurrentQuestion()
    {
        return $this->currentQuestion;
    }

    public function setCurrentQuestion(int $nb)
    {
        $this->currentQuestion = $nb;
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
}
