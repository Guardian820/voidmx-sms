<?php
class User extends Model
{
    function checkAccount($login, $password)
    {
        $u = $this->findFirst(array(
            'conditions' => array('username' => $login),
        ));

        if(isset($u->id))
        {
            if(strtoupper($u->password) == strtoupper($this->passwordHash($password)))
            {
                return $u;
            }
        }

        return false;
    }

    function passwordHash($password)
    {
        return hash('sha512', $password);
    }
}