<?php

class SmsController extends Controller
{
    function user_actionIndex()
    {
        if(isset($this->request->data->fromNum) && isset($this->request->data->toNum) && isset($this->request->data->message))
        {
            if($this->request->data->fromNum != "" && $this->request->data->toNum != "" && $this->request->data->message != "")
            {
                $isCorrect = true;

                if(strlen($this->request->data->fromNum) > 11)
                {
                    $this->Session->setFlash('Le nom ou numéro de l\'expéditeur ne doit pas dépasser 11 caractères.', 'critical');
                    $isCorrect = false;
                }

                if(!preg_match("/^[06|07]{2}\d{8}$/", $this->request->data->toNum))
                {
                    $this->Session->setFlash('Le numéro du destinataire n\'est pas correcte.', 'critical');
                    $isCorrect = false;
                }

                if(strlen($this->request->data->message) > 140)
                {
                    $this->Session->setFlash('Le message ne doit pas dépasser 140 caractères.', 'critical');
                    $isCorrect = false;
                }
                if($isCorrect)
                {
                    $tm4b = new TM4B\API(Conf::$tm4b['username'], Conf::$tm4b['password']);

                    #$tm4b->setSimulation(true);

                    $tm4b->setFrom($this->request->data->fromNum);
                    $toNum  = "33".substr($this->request->data->toNum, 1);
                    $response = $tm4b->broadcast($toNum, $this->request->data->message);

                    $status = $tm4b->checkStatus($response['broadcastID'].'-'.$response['recipients']);

                    $this->request->data->author_id = $this->Session->user('id');
                    $this->request->data->broadcastID = $response['broadcastID'];
                    $this->request->data->status = $status['status'];
                    unset($this->request->data->message);

                    $this->loadModel('Sms');

                    $this->Sms->save($this->request->data);
                    $this->Session->setFlash("Le SMS est en cours d'envoie.");
                    $this->Log("Nouveau SMS #".$response['broadcastID'], __METHOD__);
                    $this->redirect('user/account');
                }
            }
        }

        $this->render('sms/index.php');
    }
}
