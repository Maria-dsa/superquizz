<?php

namespace App\Controller;

use App\Entity\Game;
use App\Model\GameManager;
use App\Model\QuestionManager;
use App\Model\UserManager;

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

                //var_dump($_SESSION);
                return $this->displayQuestion($_SESSION['game']);

                //header('Location: /game');
                //return $this->twig->render('Game/index.html.twig');
            }
        }
        return $this->twig->render('Home/index.html.twig', ['errors' => $errors]);
    }

    public function userAnswer()
    {
        var_dump($_POST);
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userAnswer = array_map('trim', $_POST);
            $userAnswer = array_map('htmlspecialchars', $userAnswer);

            $userAnswer['answer'] ?: $errors[] = "Vous devez répondre à la question";

            $game = $_SESSION['game'];
            $questions = $game->getQuestions();
            $index = $game->getCurrentQuestion();
            // On récupère les ID des réponses associées à la question
            $answerId = [];
            foreach ($questions[$index]['answers'] as $answer) {
                $answerId[$answer['id']] = $answer['isTrue'];
            }

            // On vérifie que la réponse de l'utilisateur soit bien parmi les réponses possibles
            if (!array_key_exists($userAnswer['answer'], $answerId)) {
                $errors[] = "Réponse invalide";
            }

            if (empty($errors)) {
                //origine
                if ($answerId[$userAnswer['answer']]) {
                    $rep = "bonne réponse";
                    // faire requete pour enregistrer la réponses en 
                } else {
                    $rep = "mauvaise rep";
                }

                var_dump($rep);
                // ou alors
                //écrire requete de stockage en BDD réponse serait $answerId[$userAnswer['answer']] = 0 ou 1

                $game->incrementCurrentQuestion();

                return $this->displayQuestion($_SESSION['game']);
            }

            header('Location : /');
            exit();
        }











        //$currentQuestion = $game->getCurrentQuestion();
        //$question = $game->selectOneQuestion($currentQuestion);

        //return $this->twig->render('Game/index.html.twig', ['question' => $question]);

        //$answerId = $_POST['answerId'];
        //$game = $_SESSION['game'];
        //$game->setAnswer($answerId)->setCurrentQuestion(); //+1;
        // header('Location: /game');
    }

    public function displayQuestion(Game $game)
    {
        $currentQuestion = $game->getCurrentQuestion();
        $question = $game->selectOneQuestion($currentQuestion);
        shuffle($question['answers']);



        return $this->twig->render('Game/index.html.twig', ['question' => $question, 'session' => $_SESSION]);
    }
}
