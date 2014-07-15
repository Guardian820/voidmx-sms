<?php

/*
 * (c) 2013 Yann Guineau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Request
{
    public $url;                // URL appellé par l'utilisateur
    public $page = 1;            // pour la pagination 
    public $prefix = false;        // Prefixage des urls /prefix/url
    public $data = false;        // Données envoyé dans le formulaire

    function __construct()
    {
        $this->url = isset($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:'/';

        if(isset($_GET['page']))
        {
            if(is_numeric($_GET['page']))
            {
                if($_GET['page'] > 0)
                {
                    $this->page = round($_GET['page']);
                }
            }
        }
        
        if(!empty($_POST))
        {
            $this->data = new stdClass();
            
            foreach($_POST as $k=>$v)
            {
                $this->data->$k=$v;
            }
        }
    }
}