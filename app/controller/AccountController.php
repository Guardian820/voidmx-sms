<?php

class AccountController extends Controller
{
    function user_actionIndex()
    {
        $this->loadModel('User');
        $this->loadModel('Sms');

        $d['user'] = $this->User->findFirst(array(
            'conditions' => array("id" => $this->Session->user('id')),
        ));

        $d['sms'] = $this->Sms->find(array(
            'conditions' => array("author_id" => $this->Session->user('id')),
        ));

        $this->set($d);
        $this->render('account/overview.php');
    }
}