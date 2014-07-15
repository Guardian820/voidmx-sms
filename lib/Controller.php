<?php

/*
 * (c) 2013 Yann Guineau
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Controller
{
    public $request;
    private $vars     = array();
    public $layout    = 'default';
    private $rendered = false;
    private $view;

    /**
    * Constructeur
    * @param $request Objet request
    **/
    function __construct($request = null)
    {
        $this->Session = new Session();
        $this->Security = new Security();
        $this->Cache = new Cache();

        if($request != null && ($request->prefix == "op" || $request->prefix == "admin" || $request->prefix == "tech"))
        {
            $this->Security->AntiCSRF();
            /*if(!$this->Security->AntiCSRF())
            {
                $this->Session->setFlash("Jeton d'accès expiré, retentez votre action.", "error");
                $this->redirect('');
            }*/
        }

        if($request)
        {
            $this->request = $request;
            require APP.DS.'config'.DS.'hook.php';
        }
    }

    /**
    * Afficher une vue
    * @param $view Ne du fichier de vue
    **/
    public function render($view)
    {
        if($this->rendered){ return false; }

        $this->view = $view;

        if(!file_exists(APP.DS.'view'.DS.$this->view))
        {
            $this->error("La vue '{$this->view}' n'existe pas");
        }

        $this->set('title', "WebSite");
        $this->set('session', $_SESSION);

        extract($this->vars);
    
        /** Twig & PHP rendering **/
        $content = $this->twigMyRender();
        $content = $this->phpMyRender($content);

        echo $content;

        $this->rendered = true;
    }

    /**
    * Twig template rendering.
    **/
    private function twigMyRender()
    {
        $loader = new Twig_Loader_Filesystem(APP.DS.'view');
        //$twig = new Twig_Environment($loader, array('cache' => APP.DS.'cache',)); // Système de cache pas top
        $twig = new Twig_Environment($loader);

        $twig->addFunction(new Twig_SimpleFunction('url', 'Router::url'));
        $twig->addFunction(new Twig_SimpleFunction('asset', 'Router::asset'));
        $twig->addFunction(new Twig_SimpleFunction('request', 'Controller::request'));
        $twig->addFunction(new Twig_SimpleFunction('dump', 'var_dump'));
        $twig->addFunction(new Twig_SimpleFunction('bbcode', 'bbcode'));
        $twig->addFunction(new Twig_SimpleFunction('rankName', 'rankName'));
        
        return $twig->render($this->view, $this->vars);
    }

    /**
    * PHP template rendering.
    **/
    private function phpMyRender($content)
    {
        ob_start();
        eval("?>".$content);
        $content = ob_get_clean();

        return $content;
    }

    /**
    * Defini les variable pour la vue
    * @param $key Nom de la variable OU tableau de variables
    * @param $value Valeur de la variable
    **/
    public function set($key, $value = null)
    {
        if(is_array($key))
        {
            $this->vars += $key;
        }
        else
        {
            $this->vars[$key] = $value;
        }
    }

    protected function loadModel($name)
    {
        if(!isset($this->$name))
        {
            $file = APP.DS.'model'.DS.$name.'.php';
            require_once($file);
            $this->$name = new $name();

            if(isset($this->Form))
            {
                $this->$name->Form = $this->Form;
            }
        }
    }

    /**
    * Affiche l'erreur 404
    * @param $message Message à afficher
    **/
    public function e404($message)
    {    
        header("HTTP/1.0 404 Not Found");
        $this->set('message',$message);
        $this->render('/errors/404.php');
        die();
    }

    /**
    * Affiche une erreur spécifique
    * @param $message Message de l'erreur
    * @param $title Titre de l'erreur
    * @param $code Code de l'erreur
    **/
    public function error($message, $title = "Une erreur est survenu lors du chargement de la page", $code = 404)
    {    
        header("HTTP/1.0 $code");
        $this->set('error_title', $title);
        $this->set('message', $message);
        $this->render('/errors/error.php');
        die();
    }

    /**
    * Créer une requete vers un controller et une fonction spécifique
    * @param $controller Nom du controller à appeler
    * @param $action Nom de la fonction à appeler
    **/
    static function request($controller, $action)
    {
        $controller = ucfirst($controller).'Controller';
        require_once APP.DS.'controller'.DS.$controller.'.php';
        $c = new $controller();
        return $c->$action();
    }

    /**
    * Créer une redirection
    * @param $url Url de la redirection
    * @param $code Code HTTP
    **/
    public function redirect($url, $code = null)
    {
        if($code == 301)
        {
            header("HTTP/1.1 301 Moved Permanently");
        }

        header("Location: ".Router::url($url));
        
        exit();
    }

    /**
    * Vérifie la validité du token.
    * En cas de non validitée, renvoie sur la page d'accueil.
    **/
    protected function checkToken()
    {
        if(!$this->Security->AntiCSRF())
        {
            $this->Session->setFlash("Jeton d'accès invalide ou expiré, retentez votre action.", "error");
            $this->redirect('');
        }
    }

    /**
    * Logger d'action.
    * @param $action Action réaliser
    * @param $function Fonction d'origine
    **/
    protected function log($action, $function)
    {
        if(!isset($this->Log))
            $this->loadModel('Log');

        $this->Log->save(array(
            "action" => $action,
            "author" => $this->Session->user('login'),
            "author_id" => $this->Session->user('id'),
            "ip" => $_SERVER['REMOTE_ADDR'],
            "function" => $function
        ));
    }
}