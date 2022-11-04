<?php

namespace App\Controller;

use App\Entity\Game;
use App\Model\GameManager;

class GameController extends AbstractController
{
    private array $arrayAllQuestionId;
    private QuestionManager $questionManager;
    private array $questionsWithAnswerGame1;
    private int $numberQuestion = 0;




    public function startGame()
    {
        $newGame = $_POST; // champ type de jeu + nickname
        //@todo sécurisation
        // $newGame['type'] vaut 1;
        // $newGame['nickname'] vaut nickname choisit;
        $userManager = new UserManager();
        $newGame['userId'] = $userManager->insert($newGame['nickname']);

        // @todo
        // $userManager = new UserManager();
        // $user = $userManager->selectOneByNickname($newGame['nickname']);
        // $newGame['user_id'] = $user['id'];

        //$newGame['user_id'] vaut 1 pour le 1er user de la base;
        $gameManager = new GameManager();
        $gameId = $gameManager->insert($newGame);


        $game = $gameManager->selectOneGameById($gameId);
        $game->setQuestions($this->questionManager->selectAllId());

        $_SESSION['game'] = $game;
        header('Location: /game');
    }

    public function userAnswer()
    {
        $answerId = $_POST['answerId'];
        $game = $_SESSION['game'];
        $game->setAnswer($answerId)->setCurrentQuestion()//+1;
        // header('Location: /game');
    }

    public function displayQuestion()
    {

    }



    public function getArrayAllQuestionId(): array
    {
        return $this->arrayAllQuestionId;
    }

    public function setArrayAllQuestionId(int $number): self
    {
        $this->arrayAllQuestionId = $this->questionManager->selectAllId();
        //shuffle($this->arrayAllQuestionId);
        return $this;
    }

    public function getQuestionsWithAnswerGame1(): array
    {
        return $this->questionsWithAnswerGame1;
    }

    public function setquestionsWithAnswerGame1(): self
    {
        $ids =  array_slice($this->arrayAllQuestionId, 0, $number);
        foreach ($ids as $id) {
            $this->questionsWithAnswerGame1[] = $this->questionManager->selectOneWithAnswer($id);
        }
        return $this;
    }


    public function startGame1()
    {
        if (!count($this->arrayAllQuestionId) >= 15) {
            header('Location : /');
            exit();
        }
        // $question = $this

        // var_dump($questions);

        return $this->twig->render('Game/index.html.twig');
    }
}
