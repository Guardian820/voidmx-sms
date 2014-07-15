{% extends 'layout/default.php' %}

{% block content %}
<div class="flash info">VoidMX SMS est un site privé, de ce fait l'accès à la plateforme n'est possible que sur invitation par un autre membre.</div>
<section>
    <h3>Connexion</h3>
    <hr />
    <form action="" method="post">
        <table>
            <tr>
                <td>Identifiant:</td>
                <td><input type="text" name="username" tabindex="1" /></td>
                <td rowspan="2"><input type="submit" value="Connexion" style="height:50px" tabindex="3" /></td>
            </tr>
            <tr>
                <td>Mot de passe:</td>
                <td><input type="password" name="password" tabindex="2" /></td>
            </tr>
        </table>
    </form>
</section>
{% endblock %}