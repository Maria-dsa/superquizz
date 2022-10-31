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
        session_start();
        $errors = [];
        $display = "d-none";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginInfos = array_map('trim', $_POST);
            $loginInfos = array_map('htmlspecialchars', $loginInfos);

            // Vérification du couple username/password
            $adminManager = new AdminManager();
            $user = $adminManager->checkUserAndPassword($loginInfos);

            $user ?: $errors['login'] = 'Username inconnu ou mot de passe incorrect, veuillez réessayer';


            if (empty($errors)) {
                $display = "d-block";
                $_SESSION['username'] = $loginInfos['username'];
                // page de vérif temporaire ensuite mettre header('Location:/admin/show')
                //après merge us_7.3;
                return $this->twig->render(
                    'Admin/signIn.html.twig',
                    ['display' => $display, 'loginInfos' => $_SESSION]
                );
            } else {
                return $this->twig->render('Admin/signIn.html.twig', ['errors' => $errors, 'display' => $display]);
            }
        }

        return $this->twig->render('Admin/signIn.html.twig', ['display' => $display]);
    }

    /**
     * Display SignUp page
     */
    public function signUp(): string
    {

        session_start();
        $errors = [];
        $display = "d-none";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginInfos = array_map('trim', $_POST);
            $loginInfos = array_map('htmlspecialchars', $loginInfos);


            // Vérification de l'appartenance du pseudo à la BDD
            $adminManager = new AdminManager();
            $user = $adminManager->checkUser($loginInfos['username']);

            $user ?: $errors['username'] = 'Username inconnu, veuillez vous rapprochez d\'un administrateur';


            if (
                !isset($loginInfos['password'])
                || empty($loginInfos['password'])
                || strlen($loginInfos['password']) < 8
            ) {
                $errors['password'] = 'Il faut rentrer un mot de passe de plus de 8 caractères';
            }


            if (empty($errors)) {
                $display = "d-block";
                $adminManager->addPasswordToUser($loginInfos);
                return $this->twig->render('Admin/signUp.html.twig', ['display' => $display]);

                // echo 'le dernier index est ' . $lastId;
                // header('Location:/admin/show');
                // return null;
            } else {
                return $this->twig->render('Admin/signUp.html.twig', ['errors' => $errors, 'display' => $display]);
            }
        }


        return $this->twig->render('Admin/signUp.html.twig', ['display' => $display]);
    }
}
