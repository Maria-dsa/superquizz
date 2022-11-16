<?php

namespace App\Controller;

use App\Model\AdminManager;

class AdminController extends AbstractController
{
    /**
     * Display SignIn page
     */
    public function signIn(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginInfos = $this->protection($_POST);
            $errors = $this->validate($loginInfos);

            if (empty($errors)) {
                // Vérification du couple username/password
                $adminManager = new AdminManager();
                $user = $adminManager->selectOneByUsername($loginInfos['username']);

                if ($user && password_verify($loginInfos['password'], $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: /admin/show');
                    exit();
                }
                $errors[] = 'Username et/ou mot de passe incorrect';
            }
        }
        return $this->twig->render('Admin/signIn.html.twig', ['errors' => $errors]);
    }

    public function validate(array $loginInfos): array
    {
        $errors = [];
        foreach ($loginInfos as $field => $value) {
            $value ?: $errors[$field] = 'Ce champ doit être complété';
        }
        return $errors;
    }

    public function protection(array $post): array
    {
        $loginInfos = array_map('trim', $post);
        $loginInfos = array_map('htmlspecialchars', $loginInfos);
        return $loginInfos;
    }

    /**
     * Display SignUp page
     */
    public function signUp(): string
    {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginInfos = $this->protection($_POST);
            $errors = $this->validate($loginInfos);

            if (empty($errors)) {
                 // Vérification de l'appartenance du pseudo à la BDD
                $adminManager = new AdminManager();
                $user = $adminManager->checkUser($loginInfos['username']);

                $user ?: $errors['username'] = 'Utilisateur inconnu ou déjà enregistré, veuillez vous rapprochez 
                                            d\'un administrateur';

                if (
                    !isset($loginInfos['password'])
                    || empty($loginInfos['password'])
                    || strlen($loginInfos['password']) < 8
                ) {
                    $errors['password'] = 'Il faut rentrer un mot de passe de plus de 8 caractères';
                }

                if (empty($errors)) {
                    $adminManager->addPasswordToAdmin($loginInfos);
                    return $this->signIn();
                }
            }
        }
        return $this->twig->render('Admin/signUp.html.twig', ['errors' => $errors]);
    }

    public function logout()
    {
        // On supprime l'index ['user_id'] du tableau $_SESSION
        unset($_SESSION['user_id']);
        // puis on le redirige sur une autre page (page d'accueil ici)
        header('Location: /');
        exit();
    }
}
