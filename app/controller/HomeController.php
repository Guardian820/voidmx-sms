<?php

class HomeController extends Controller
{
    function actionIndex()
    {
        if($this->Session->isLogged())
        {
            $this->redirect('user/account');
        }
        else
        {
            $this->redirect('auth/login');
        }
    }
}