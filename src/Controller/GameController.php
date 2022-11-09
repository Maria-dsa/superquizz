<?php

namespace App\Controller;

use App\Entity\Game;
use App\Model\GameHasQuestionManager;
use App\Model\GameManager;
use App\Model\QuestionManager;
use App\Model\UserManager;
use DateTime;

class GameController extends AbstractController
{
    private QuestionManager $questionManager;

    public function startGame()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newGame = array_map('trim', $_POST); // champ type de jeu + nickname
            $newGame = array_map('htmlspecialchars', $newGame);
            $newGame['type'] = 1;

            foreach ($newGame as $field => $input) {
                $input ?: $errors[$field] = 'Ce champ doit être complété';
            }

            // $newGame['nickname'] vaut nickname choisit;
            if (strlen($newGame['nickname']) > 45) {
                $errors['nickname'] = 'Le pseudo doit faire moins de 45 caractères';
            }

            if (empty($errors)) {
                $userManager = new UserManager();
                $newGame['userId'] = $userManager->insert($newGame['nickname']);

                $gameManager = new GameManager();
                // Insère en BDD un nouveau game et récupère son ID
                $gameId = $gameManager->insert($newGame);
                // Retourne un objet de type Game avec clé valeur correspondants à la table
                $game = $gameManager->selectOneGameById($gameId);

                $this->questionManager = new QuestionManager();
                $game->setQuestions($this->questionManager->selectQuestionsWithAnswer());

                $_SESSION['game'] = $game;
                $_SESSION['nickname'] = $newGame['nickname'];

                header('Location: /question');
            }
        }
        return $this->twig->render('Home/index.html.twig', ['errors' => $errors]);
    }

    public function question()
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userAnswer = array_map('trim', $_POST);
            $userAnswer = array_map('htmlspecialchars', $userAnswer);

            $game = $_SESSION['game'];
            $questions = $game->getQuestions();
            $index = $game->getCurrentQuestion();
            // On récupère les ID des réponses associées à la question
            $answerId = [];
            foreach ($questions[$index]['answers'] as $answer) {
                $answerId[$answer['id']] = $answer['isTrue'];
            }

            if (!array_key_exists('answer', $userAnswer)) {
                $errors[] = 'Invalid Answer';
            } else {
                $userAnswer['answer'] ?: $errors[] = "Vous devez répondre à la question";
                // On vérifie que la réponse de l'utilisateur soit bien parmi les réponses possibles
                if (!array_key_exists($userAnswer['answer'], $answerId)) {
                    $errors[] = 'Invalid Answer';
                }
            }

            if (empty($errors)) {
                $gameQuestionManager = new GameHasQuestionManager();
                //écrire requete de stockage en BDD réponse serait $answerId[$userAnswer['answer']] = 0 ou 1
                $gameQuestionManager->insert(
                    $game->getId(),
                    $game->getQuestions()[$game->getCurrentQuestion()]['id'],
                    intval($userAnswer['answer']),
                    $answerId[$userAnswer['answer']]
                );
                if ($game->getCurrentQuestion() <= 13) {
                    $game->incrementCurrentQuestion();
                    header('Location: /question');
                }
                return $this->twig->render('Game/fin.html.twig', ['session' => $_SESSION]);
            }

            unset($_SESSION['game']);
            unset($_SESSION['nickname']);
            header('Location: /');
            exit();
        }
        return $this->displayQuestion($_SESSION['game']);
    }

    public function displayQuestion(Game $game)
    {
        $currentQuestion = $game->getCurrentQuestion();
        $question = $game->selectOneQuestion($currentQuestion);
        shuffle($question['answers']);

        return $this->twig->render('Game/index.html.twig', ['question' => $question, 'session' => $_SESSION]);
    }

    // public function displayScore(Game $game)
    // {
    // }

    // public function calculateQuestionDuration(DateTime $start, DateTime $end): void //int
    // {
    // }
}
