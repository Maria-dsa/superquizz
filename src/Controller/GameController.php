<?php

namespace App\Controller;

use App\Entity\Game;
use App\Model\GameHasQuestionManager;
use App\Model\GameManager;
use App\Model\QuestionManager;
use App\Model\ResultManager;
use App\Model\UserManager;
use DateTime;

class GameController extends AbstractController
{
    private QuestionManager $questionManager;
    private int $maxQuestion = 15;

    public const GAME_TYPES = ['1', '2'];
    public const FILE_UPLOAD_ERROR = [
        0 => 'There is no error, the file uploaded with success',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.',
    ];

    public const ID_AVATAR = [];

    public function startGame()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newGame = array_map('trim', $_POST); // champ type de jeu + nickname

            $errors = $this->validate($newGame);

            $uploadError = self::FILE_UPLOAD_ERROR[$_FILES['picture']['error']];
            if ($uploadError != self::FILE_UPLOAD_ERROR[4]) {
                $errorsFiles = $this->validateImg();
                empty($errorsFiles) ?: $errors['file'] = [...$errorsFiles];
            }

            if (empty($errors)) {
                $userManager = new UserManager();
                //$picture = basename($_FILES['picture']['name']) ?? 'avatar_default_' . rand(1,19);
                $picture = basename($_FILES['picture']['name']) ?: 'avatar_default_' . rand(1, 19) . ".png";
                $newGame['userId'] = $userManager->insert($newGame['nickname'], $picture);

                $gameManager = new GameManager();
                // Insère en BDD un nouveau game et récupère son ID
                $gameId = $gameManager->insert($newGame);
                // Retourne un objet de type Game avec clé valeur correspondants aux champs de la table
                $game = $gameManager->selectOneGameById($gameId);

                $this->questionManager = new QuestionManager();

                if ($newGame['gameType'] === self::GAME_TYPES[1]) {
                    $this->maxQuestion = 30;
                    $game->setEndedAtGame2();
                }
                $game->setQuestions($this->questionManager->selectQuestionsWithAnswer(
                    $this->maxQuestion,
                    $newGame['theme']
                ));

                $_SESSION['game'] = $game;
                $_SESSION['nickname'] = $newGame['nickname'];

                header('Location: /question');
            }
        }
        $questionController = new QuestionController();
        $allThemes = $questionController->getAllTheme();
        return $this->twig->render('Home/index.html.twig', [
            'errors' => $errors,
            'themes' => $allThemes
        ]);
    }

    public function validate(array &$newGame): array
    {
        $errors = [];
        foreach ($newGame as $field => $userInput) {
            $userInput ?: $errors[$field] = 'Ce champ doit être complété';
        }

        // $newGame['nickname'] vaut nickname choisit;
        if (strlen($newGame['nickname']) > 45) {
            $errors['nickname'] = 'Le pseudo doit faire moins de 45 caractères';
        }

        $questionController = new QuestionController();
        $allThemes = $questionController->getAllTheme();
        $allThemes[] = 'all';

        if (!in_array($newGame['theme'], $allThemes)) {
            $errors['theme'] = 'Le thème choisit doit être dans la liste';
        }

        if (!in_array($newGame['gameType'], self::GAME_TYPES)) {
            $errors['gameType'] = 'Le jeu choisit est invalide';
        }

        return $errors;
    }

    private function validateImg(): array
    {
        $errors = [];
        if (!$_FILES['picture']['error']) {
            // Je sécurise et effectue mes tests
            // Je récupère l'extension du fichier
            $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            // Les extensions autorisées
            $authorizedExtensions = ['jpg', 'JPG', 'jpeg', 'png', 'gif', 'webp'];
            // Le poids max géré en octet
            $maxFileSize = 2000000;

            /****** Si l'extension est autorisée *************/
            if ((!in_array($extension, $authorizedExtensions))) {
                $errors[] = 'Veuillez sélectionner une image de type jpg, jpeg, png, gif ou webp !';
            }
            /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
            if (
                file_exists($_FILES['picture']['tmp_name'])
                && filesize($_FILES['picture']['tmp_name']) > $maxFileSize
            ) {
                $errors[] = "Votre fichier doit faire moins de 2M !";
            }
        } else {
            $errors[] = self::FILE_UPLOAD_ERROR[$_FILES['picture']['error']];
        }

        if (empty($errors)) {
            // ON récupère l'extension
            $fileExtension = pathinfo($_FILES['picture']['full_path'])['extension'];
            //ON donne un nom unique au fichier avec son extension
            $_FILES['picture']['name'] = uniqid() . '.' . $fileExtension;
            // chemin vers un dossier sur le serveur qui va recevoir les fichiers transférés
            //(attention ce dossier doit être accessible en écriture)
            $uploadDir = 'uploads/avatar/';
            // le nom de fichier sur le serveur est celui du nom d'origine du fichier sur
            //le poste du client (mais d'autre stratégies de nommage sont possibles)
            $uploadFile = $uploadDir . basename($_FILES['picture']['name']);
            //pour récupérer le type MIME
            //var_dump(mime_content_type($_FILES['picture']['tmp_name']));
            // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur.
            //Ça y est, le fichier est uploadé
            move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile);
        }
        return $errors;
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

            $errors = $this->validateAnswer($userAnswer, $answerId);
            var_dump($errors);

            if (empty($errors)) {
                $gameQuestionManager = new GameHasQuestionManager();
                $time = $game->setQuestionsDuration();

                //écriture requete de stockage en BDD réponse serait $answerId[$userAnswer['answer']] = 0 ou 1
                $gameQuestionManager->insert(
                    $game->getId(),
                    $game->getQuestions()[$game->getCurrentQuestion()]['id'],
                    intval($userAnswer['answer']),
                    $answerId[$userAnswer['answer']],
                    $time
                );
                $game->setScore($answerId[$userAnswer['answer']]);

                if ($game->getType() == self::GAME_TYPES[0]) {
                    if ($game->getCurrentQuestion() <= $this->maxQuestion - 2) {
                        $game->incrementCurrentQuestion();
                        header('Location: /question');
                        exit();
                    }
                } else {
                    $end = $game->getEndedAt();
                    if (new DateTime($end) > new DateTime('now')) {
                        $game->incrementCurrentQuestion();
                        header('Location: /question');
                        exit();
                    }
                }
                $game->setEndedAt();
                $game->setGameEnded(true);
                header('Location: /result');
                exit();
            }

            unset($_SESSION['game']);
            unset($_SESSION['nickname']);
            header('Location: /');
            exit();
        }
        return $this->displayQuestion($_SESSION['game']);
    }

    public function validateAnswer(array $userAnswer, array $answerId): array
    {
        $errors = [];
        if (!array_key_exists('answer', $userAnswer)) {
            $errors[] = 'Invalid Answer';
        } else {
            $userAnswer['answer'] ?: $errors[] = "Vous devez répondre à la question";
            // On vérifie que la réponse de l'utilisateur soit bien parmi les réponses possibles
            if (!array_key_exists($userAnswer['answer'], $answerId)) {
                $errors[] = 'Invalid Answer';
            }
        }
        return $errors;
    }





    public function displayQuestion(Game $game)
    {
        $currentQuestion = $game->getCurrentQuestion();
        $game->setQuestionStartedAt();
        $question = $game->selectOneQuestion($currentQuestion);
        shuffle($question['answers']);
        $nbQuestions = count($game->getQuestions());

        $temporaryScore = [];
        if (!empty($game->getScore())) {
            for ($i = 0; $i < $nbQuestions; $i++) {
                $temporaryScore[] = $game->getScoreById($game->getCurrentQuestion() - 1);
            }
        }

        return $this->twig->render(
            'Game/index.html.twig',
            [
                'question' => $question,
                'session' => $_SESSION,
                'nbQuestions' => $nbQuestions,
                'temporaryScore' => $temporaryScore,
                'score' => $game->getScore()
            ]
        );
    }

    public function result(): string
    {
        if (!isset($_SESSION['game'])) {
            echo 'Unauthorized access';
            header('HTTP/1.1 403 Forbidden');
            exit();
        }

        $resultmanager = new ResultManager();
        $game =  $_SESSION['game'];
        if (!$game->getGameEnded()) {
            unset($_SESSION['game']);
            unset($_SESSION['nickname']);
            header('Location: /');
            exit();
        }
        $nbGoodAnswer = array_sum($game->getScore());
        $game->setGameDuration();
        $nbQuestions = count($game->getQuestions());

        // Calcul de la durée de la partie en seconde


        //US.5.3.1 : Affichage scores/stats sur page results
        $resultManager = new ResultManager();
        $allUsersRanks = $resultManager->selectAllByRank();
        $podium = $resultManager->selectPodium($game->getType());
        $id = $game->getUserId();
        $userRank = $resultManager->selectOneRankById($id);
        $questionSuccess = $resultManager->selectAllQuestionSuccess();

        $arrayResult = [];
        $answeredQuestions = array_slice($game->getQuestions(), 0, count($game->getScore()));
        $percentGoodAnswers = $resultmanager->answerIntoPercent($nbGoodAnswer, count($answeredQuestions));
        foreach ($answeredQuestions as $question) {
            $result = $resultManager->selectQuestionSuccessById($question['id']);
            $arrayResult[] = $result['pourcentage_reussite'];
        }

        $userAnswers = $resultManager->matchingAnswerByGameId($game->getId());

        $questionTimer = $game->getQuestionsDuration();
        return $this->twig->render('Game/result.html.twig', [
            'session' => $_SESSION,


            'allUsersRanks' => $allUsersRanks,
            'userRank' => $userRank,
            'podium' => $podium,
            'questionSuccess' => $questionSuccess,
            'arrayResult' => $arrayResult,
            'nbGoodAnswer' => $nbGoodAnswer,
            'nbQuestions' => $nbQuestions,
            'percentGoodAnswers' => $percentGoodAnswers,
            'userAnswers' => $userAnswers,
            'questionsTimer' => $questionTimer,
            'answeredQuestions' => $answeredQuestions
        ]);
    }

    public function rank(): string
    {
        $resultmanager = new ResultManager();
        $ranks = $resultmanager->selectAllByRank();

        return $this->twig->render('Game/rank.html.twig', [
            'ranks' => $ranks,
        ]);
    }
}
