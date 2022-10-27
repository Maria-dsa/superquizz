<?php

namespace App\Controller;

use App\Model\QuestionManager;

class QuestionController extends AbstractController
{
    private QuestionManager $questionManager;

    public function __construct()
    {
        parent::__construct();
        $this->questionManager = new QuestionManager();
    }
    /**
     * List items
     */
    public function index(): string
    {
        $questions = $this->questionManager->selectAllWithAnswer();
        return $this->twig->render('Admin/show.html.twig', ['questions' => $questions]);
    }

    /**
     * Add a new item
     */
    public function add(): ?string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $itemManager = new QuestionManager();
            $id = $itemManager->insert($item);

            header('Location:/items/show?id=' . $id);
            return null;
        }

        return $this->twig->render('Admin/add.html.twig', ['errors' => $errors]);
    }

    /**
     * Delete a specific item
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $id = htmlentities($id);
            $this->questionManager->delete((int)$id);
            header('Location:/admin/show');
        }
    }
}
