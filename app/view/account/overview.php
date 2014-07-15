{% extends 'layout/default.php' %}

{% block content %}
<section>
    <h3>Mon compte</h3>
    <hr />
    <b>Utilisateur</b>: {{ user.username }} | 
    <b>Accès</b>: <?php echo rankName({{ user.rank }}); ?> | 
    <b>Crédits</b>: {% if user.rank == 0 and user.credit == 0 %}<font color="red">{% else %}<font color="green">{% endif %}<?php echo ({{ user.rank }} > 0 ? "&#x221e;" : "{{ user.credit }}"); ?></font> 
    {% if user.rank == 0 %}<i>(<a href="{{ url('credits') }}">obtenir plus de crédits</a>)</i>{% endif %}

    <br /><br />

    <table class="list" width="100%">
        <tr>
            <th width="40px">#</th>
            <th>Expéditeur</th>
            <th>Destinataire</th>
            <!--<th>Statue</th>-->
            <th width="120px">Date</th>
        </tr>
        {% for s in sms %}
        <tr align="center">
            <td>{{ s.id }}</td>
            <td>{{ s.fromNum }}</td>
            <td>{{ s.toNum }}</td>
            <!--<td>{{ s.status }}</td>-->
            <td>{{ s.date | date("d/m/y h:i") }}</td>
        </tr>
        {% endfor %}
    </table>
</section>
{% endblock %}