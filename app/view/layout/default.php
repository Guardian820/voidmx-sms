<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>VoidMX SMS</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/sms.ico') }}">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
</head>
<body>
    <div id="content">
        <header>
            <h1>VoidMX SMS</h1>
        </header>
    
        <?php if($this->Session->isLogged()) { ?>
        <menu>
            <a href="{{ url('user/account') }}">Mon compte</a> - 
            <a href="{{ url('user/sms') }}">Envoyer un SMS</a> - 
            <a href="{{ url('auth/logout') }}">Déconnexion</a>
        </menu>
        <?php } else { ?>
        <menu>
            <a href="{{ url('auth/login') }}">Connexion</a>
        </menu>
        <?php } ?>

        <?php echo $this->Session->flash(); ?>
        {% block content %}{% endblock %}

        <footer>
            <span class="copyright">
                <i>VoidMX SMS par Yann GUINEAU</i> - 
                <a href="{{ url('about') }}">À propos</a> - 
                <a href="http://tracker.voidmx.net/" target="_blank">Support</a>
            </span>
        </footer>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>