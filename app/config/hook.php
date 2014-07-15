<?php

if(!$this->Session->isLogged() && isset($_COOKIE['AL']) && $_COOKIE['AL'] != "")
{
    $ident = explode('|', $this->Security->rc4Decrypt($_COOKIE['AL']));

    $this->loadModel('User');
    $user = $this->User->checkAccount(@$ident[0], @$ident[1]);

    if($user)
    {
        $this->Security->generateToken();

        $this->Session->write('User', $user);
        $this->Session->setFlash("Bonjour ".ucfirst($user->login).".");
        $this->Log("Authentification réussi", __METHOD__);
        $this->redirect();
    }
}

if($this->request->prefix == "op")
{
    if(!$this->Session->isLogged() || $this->Session->user('rank') < OP_LEVEL)
    {
        $this->Session->setFlash("Vous ne disposez pas des permissions nécessaire pour accerder à cette page.", "error");
        $this->redirect('auth/login');
    }
}

if($this->request->prefix == "admin")
{
    if(!$this->Session->isLogged() || $this->Session->user('rank') < ADMIN_LEVEL)
    {
        $this->Session->setFlash("Vous ne disposez pas des permissions nécessaire pour accerder à cette page.", "error");
        $this->redirect('auth/login');
    }
}

if($this->request->prefix == "user+")
{
    if(!$this->Session->isLogged() || $this->Session->user('rank') < MEMB_LEVEL)
    {
        $this->Session->setFlash("Vous ne disposez pas des permissions nécessaire pour accerder à cette page.", "error");
        $this->redirect('auth/login');
    }
}

if($this->request->prefix == "user")
{
    if(!$this->Session->isLogged() || $this->Session->user('rank') < USER_LEVEL)
    {
        $this->Session->setFlash("Vous ne disposez pas des permissions nécessaire pour accerder à cette page.", "error");
        $this->redirect('auth/login');
    }
}
