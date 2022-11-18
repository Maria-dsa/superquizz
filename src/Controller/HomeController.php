<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    /**
     * Display home page
     */
    public function index(): string
    {
        $questionController = new QuestionController();
        $allThemes = $questionController->getAllTheme();

        return $this->twig->render('Home/index.html.twig', [
            'themes' => $allThemes,
        ]);
    }
}
