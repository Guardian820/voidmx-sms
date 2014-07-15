<?php

/*
 * (c) 2013 Yann Guineau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Session
{
    
    public function __construct()
    {
        $session_name = session_name();
        
        if(isset($_POST[$session_name]))
        {
            session_id($_POST[$session_name]);
        }

        if(!isset($_SESSION))
        {
            session_start();
        }
    }

    public function setFlash($message, $type = 'success')
    {
        $_SESSION['flash'] = array(
            'message' => $message,
            'type'    => $type
        );
    }

    public function flash()
    {
        if(isset($_SESSION['flash']['message']))
        {
            $html = '<div class="flash '.$_SESSION['flash']['type'].'">'.$_SESSION['flash']['message'].'</div>';
            $_SESSION['flash'] = array();
            return $html;
        }
    }

    public function write($key,$value)
    {
        $_SESSION[$key] = $value;
    }

    public function read($key = null)
    {
        if($key)
        {
            if(isset($_SESSION[$key]))
            {
                return $_SESSION[$key];
            }
            else
            {
                return false;
            }
        }
        else
        {
            return $_SESSION;
        }
    }

    public function isLogged()
    {
        return isset($_SESSION['User']->rank);
    }

    public function user($key)
    {
        if($this->read('User'))
        {
            if(isset($this->read('User')->$key))
            {
                return $this->read('User')->$key;
            }
            else
            {
                return false;
            }
        }
        return false;
    }

}