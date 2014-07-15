<?php

/*
 * (c) 2013 Yann Guineau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Dispatcher
{
    var $request;
    var $security;

    /**
    * Fonction principale du dispatcher
    * Charge le controller en fonction du routing
    **/
    function __construct()
    {
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);
        $controller = $this->loadController();
        $action = $this->request->action;

        $action = "action".ucfirst($action);

        if($this->request->prefix)
        {
            $action = $this->request->prefix.'_'.$action;
        }

        if(!in_array($action, array_diff(get_class_methods($controller), get_class_methods('Controller'))))
        {
            $this->error("Le controller '{$this->request->controller}' n'a pas de méthode '$action'");
        }

        call_user_func_array(array($controller, $action), $this->request->params);

        /*$controller->render($action);*/
    }

    /**
    * Permet de générer une page d'erreur en cas de problème au niveau du routing (page inexistante)
    **/
    function error($message)
    {
        $controller = new Controller($this->request);
        $controller->e404($message);
    }

    /**
    * Permet de charger le controller en fonction de la requête utilisateur
    **/
    function loadController()
    {
        $name = ucfirst($this->request->controller).'Controller';
        $file = APP.DS.'controller'.DS.$name.'.php';

        if(!file_exists($file))
        {
            $this->error("Le controller '{$this->request->controller}' n'existe pas");
        }

        require $file;

        $controller = new $name($this->request);
        
        return $controller;
    }
}