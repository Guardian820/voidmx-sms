<?php

class AuthController extends Controller
{
    function actionLogin()
    {
        if($this->Session->isLogged())
        {
            $this->redirect('');
        }

        if(isset($this->request->data->username) && isset($this->request->data->password))
        {
            $this->loadModel('User');
            $user = $this->User->checkAccount($this->request->data->username, $this->request->data->password);

            if(!$user)
            {
                $this->Session->setFlash('Nom de compte ou mot de passe incorrect.', 'critical');
            }
            else
            {
                // if($this->request->data->remember == 1)
                // {
                //     $autoLogin = $this->Security->rc4Encrypt($this->request->data->username."|".$this->request->data->password);
                //     setcookie("AL", $autoLogin, strtotime('+1 year'), "/");
                // }

                $this->Security->generateToken();

                $this->Session->write('User', $user);
                $this->Session->setFlash("Bonjour ".ucfirst($user->username).".");
                $this->Log("Authentification réussi", __METHOD__);
                $this->redirect('');
            }
        }
        
        $this->render('auth/login.php');
    }

    function actionLogout()
    {
        // $_COOKIE['AL'] = "";
        // setcookie("AL", "", strtotime('+1 year'), "/");
        unset($_SESSION['User']);
        $this->Session->setFlash('Vous ête maintenant déconnecté'); 
        $this->redirect(''); 
    }
}