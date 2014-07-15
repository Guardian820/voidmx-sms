{% extends 'layout/default.php' %}

{% block content %}
<div class="flash critical">
    <h3>{{ error_title }}</h3>
    <hr />
    {{ message }}<br />
    <i>Retournez sur la page d'accueil en cliquant ici : <a href="{{ url('') }}">accueil</a>.</i>
</div>
{% endblock %}