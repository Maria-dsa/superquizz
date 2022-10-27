<?php

namespace App\Controller;

use App\Model\QuestionManager;

class QuestionController extends AbstractController
{
    /**
     * List items
     */
    public function index(): string
    {
        $questionManager = new QuestionManager();
        $requestQuestions = $questionManager->selectAll();

        $result = [];
        foreach ($requestQuestions as $requestLine) {
            $id = $requestLine['id'];
            $question = $requestLine['question'];
            $answer = $requestLine['answer'];
            $theme = $requestLine['theme'];
            $value = $requestLine['is_correct'];
            if (!array_key_exists($question, $result)) {
                $result[$question]['id'] = $id;
                $result[$question]['theme'] = $theme;
                $result[$question]['answer'][$answer] = $value;
            } else {
                $result[$question]['answer'][$answer] = $value;
            }
        }
        return $this->twig->render('Admin/show.html.twig', ['questions' => $result]);
    }

    /**
     * Show informations for a specific item
     */
    public function show(int $id): string
    {
        $itemManager = new QuestionManager();
        $item = $itemManager->selectOneById($id);

        return $this->twig->render('Item/show.html.twig', ['item' => $item]);
    }

    /**
     * Edit a specific item
     */
    public function edit(int $id): ?string
    {
        $itemManager = new QuestionManager();
        $item = $itemManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $item = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $itemManager->update($item);

            header('Location: /items/show?id=' . $id);

            // we are redirecting so we don't want any content rendered
            return null;
        }

        return $this->twig->render('Item/edit.html.twig', [
            'item' => $item,
        ]);
    }

    /**
     * Add a new item
     */
    public function add(): ?string
    {
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

        return $this->twig->render('Item/add.html.twig');
    }

    /**
     * Delete a specific item
     */
    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $itemManager = new QuestionManager();
            $itemManager->delete((int)$id);

            header('Location:/items');
        }
    }
}
