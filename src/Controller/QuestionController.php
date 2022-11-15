<?php

namespace App\Controller;

use App\Model\AnswerManager;
use App\Model\QuestionManager;
use App\Model\ThemeManager;

class QuestionController extends AbstractController
{
    private QuestionManager $questionManager;
    private ThemeManager $themeManager;
    private array $allTheme = [];

    public const LEVEL = ['Débutant', 'Confirmé', 'Expert'];

    public function __construct()
    {
        parent::__construct();
        $this->questionManager = new QuestionManager();
        $this->themeManager = new ThemeManager();
        $this->setAllTheme();
    }

    public function setAllTheme()
    {
        $results = $this->themeManager->selectAll();
        foreach ($results as $result) {
            $this->allTheme[] = $result['content'];
        }
    }
    /**
     * List items
     */
    public function index(): string
    {
        if (!$this->admin) {
            echo 'Unauthorized access';
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
        $questions = $this->questionManager->selectAllWithAnswer();
        return $this->twig->render('Admin/show.html.twig', ['questions' => $questions]);
    }

    public function update(int $id)
    {
        if (!$this->admin) {
            echo 'Unauthorized access';
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
        // Controle de l'id provenant d'un champ caché
        $id = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if (!$id) {
            header("Location: /");
            exit("Wrong input parameter");
        }

        $answersIds = '';

        $questionInfos = $this->questionManager->selectOneWithAnswerForUpdate($id);
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $questionInfos = array_map('trim', $_POST);
            $errors = $this->validate($questionInfos);

            if ($_FILES['picture']['error'] != 4) {
                $errorsFiles = $this->validateImg();
                empty($errorsFiles) ?: $errors['file'] = [...$errorsFiles];
            }


            // if validation is ok, update and redirection
            if (empty($errors)) {
                $this->questionManager->update($questionInfos);
                $answerManager = new AnswerManager();
                $answersIds = $answerManager->selectAllIdsByQuestionId($id);
                $answerManager->update($questionInfos, $answersIds);
                $_FILES['picture']['error'] ?:
                    $this->questionManager->updatePicture(basename($_FILES['picture']['name']), $id);
                header('Location:/admin/show');
                return null;
            }
        }

        return $this->twig->render('Admin/update.html.twig', [
            'questionsInfos' => $questionInfos,
            'errors' => $errors,
            'id' => $id,
            'themes' => $this->allTheme,
            'levels' => self::LEVEL,
        ]);
    }
    /**
     * Show informations for a specific question
     **/
    public function display(int $choosenId): string
    {
        if (!$this->admin) {
            echo 'Unauthorized access';
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
        $id = filter_var($choosenId, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if (!$id) {
            header("Location: /");
            //exit("Wrong input parameter");
        }
        $question = $this->questionManager->selectOneWithAnswer($id);

        if (!isset($question['content']) || empty($question['content'])) {
            header('Location: /');
            //exit("Question not found");
        }




        return $this->twig->render('Admin/display.html.twig', ['question' => $question]);
    }

    /**
     * Add a new item
     */
    public function add(): ?string
    {
        if (!$this->admin) {
            echo 'Unauthorized access';
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $questionInfos = array_map('trim', $_POST);
            $errors = $this->validate($questionInfos);



            if ($_FILES['picture']['error'] != 4) {
                $errorsFiles = $this->validateImg();
                empty($errorsFiles) ?: $errors['file'] = [...$errorsFiles];
            }

            // if validation is ok, insert and redirection
            if (empty($errors)) {
                $lastId = $this->questionManager->insert($questionInfos);
                $answerManager = new AnswerManager();
                $answerManager->insertAtId($questionInfos, $lastId);
                $_FILES['picture']['error'] ?:
                    $this->questionManager->updatePicture(basename($_FILES['picture']['name']), $lastId);
                header('Location:/admin/show');
                return null;
            }

            return $this->twig->render('Admin/add.html.twig', [
                'questionsInfos' => $questionInfos,
                'errors' => $errors,
                'themes' => $this->allTheme,
                'levels' => self::LEVEL,
            ]);
        }

        return $this->twig->render('Admin/add.html.twig', [
            'errors' => $errors,
            'themes' => $this->allTheme,
            'levels' => self::LEVEL,
        ]);
    }

    private function validateImg(): array
    {
        $errors = [];
        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );

        if (!$_FILES['picture']['error']) {
            // Je sécurise et effectue mes tests
            // Je récupère l'extension du fichier
            $extension = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            // Les extensions autorisées
            $authorizedExtensions = ['jpg', 'JPG','jpeg','png', 'gif', 'webp'];
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
            $errors[] = $phpFileUploadErrors[$_FILES['picture']['error']];
        }

        if (empty($errors)) {
            // ON récupère l'extension
            $fileExtension = pathinfo($_FILES['picture']['full_path'])['extension'];
            //ON donne un nom unique au fichier avec son extension
            $_FILES['picture']['name'] = uniqid() . '.' . $fileExtension;
            // chemin vers un dossier sur le serveur qui va recevoir les fichiers transférés
            //(attention ce dossier doit être accessible en écriture)
            $uploadDir = 'images/';
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

    private function validate(array $questionInfos)
    {
        $errors = [];
        foreach ($questionInfos as $field => $adminInput) {
            $adminInput ?: $errors[$field] = 'Ce champ doit être complété';
        }

        if (!in_array($questionInfos['theme'], $this->allTheme)) {
            $errors['theme'] = 'Le thème choisit doit être dans la liste';
        }

        if (strlen($questionInfos['question']) > 500) {
            $errors['question'] = 'La question doit faire moins de 500 charactères';
        }

        if (!in_array($questionInfos['level'], self::LEVEL)) {
            $errors['level'] = 'Le niveau choisit doit être Débutant, Confirmé ou Expert';
        }

        if (strlen($questionInfos['goodAnswer']) > 255) {
            $errors['goodAnswer'] = 'La bonne réponse doit faire moins de 255 charactères';
        }

        for ($i = 1; $i <= 3; $i++) {
            if (strlen($questionInfos['badAnswer' . $i]) > 255) {
                $errors['badAnswer' . $i] = 'La mauvaise réponse numéro ' . $i . ' doit faire moins de 255 charactères';
            }
        }
        return $errors;
    }

    /**
     * Delete a specific item
     */
    public function delete(): void
    {
        if (!$this->admin) {
            echo 'Unauthorized access';
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $id = htmlentities($id);
            $this->questionManager->delete((int)$id);
            header('Location:/admin/show');
        }
    }
}
