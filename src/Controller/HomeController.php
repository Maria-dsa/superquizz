<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    /**
     * Display home page
     */
    public function index(): string
    {
        return $this->twig->render('Home/index.html.twig');
    }

    public function admin(): string
    {
        return $this->twig->render('Admin/admin.html.twig');
    }

    public function result(): string
    {
        return $this->twig->render('Home/result.html.twig');
    }
}
