<?php

namespace App\Controller;

use App\Model\AdminManager;

class AdminController extends AbstractController
{
    /**
     * Display SignIn page
     */

    public function login(): string
    {

        session_start();
        //$errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginInfos = array_map('trim', $_POST);
            $loginInfos = array_map('htmlspecialchars', $loginInfos);
        }
        return $this->twig->render('Admin/login.html.twig');
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


            //TODO Verif

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

        // if (isset($_POST['username'])){
        //     $username = stripslashes($_REQUEST['username']);
        //     $username = mysqli_real_escape_string($conn, $username);
        //     $password = stripslashes($_REQUEST['password']);
        //     $password = mysqli_real_escape_string($conn, $password);
        //         $query = "SELECT * FROM `users` WHERE username='$username' and
        //password='".hash('sha256', $password)."'";
        //     $result = mysqli_query($conn,$query) or die(mysql_error());
        //     $rows = mysqli_num_rows($result);
        // if($rows==1){
        //     $_SESSION['username'] = $username;
        //     header("Location: index.php");
        // }else{
        //     $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
        // }
        // }






        return $this->twig->render('Admin/signUp.html.twig', ['display' => $display]);
    }
}
