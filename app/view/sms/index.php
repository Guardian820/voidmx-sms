{% extends 'layout/default.php' %}

{% block content %}
<div class="flash info">Seul les méta données sont enregistré sur le site, les messages quant à eux sont simplement transmis par SMS puis supprimer du site.</div>
<section>
    <h3>Envoyer un SMS</h3>
    <hr />
    <form action="" method="post">
        <table>
            <tr>
                <td width="80px">Expéditeur:</td>
                <td width="100px"><input type="text" name="fromNum" value="" /></td>
                <td><i>Nom ou numéro (11 caractères max)</i></td>
            </tr>
            <tr>
                <td width="80px">Destinataire:</td>
                <td><input type="text" name="toNum" value="" /></td>
                <td><i>Numéro de téléphone (sans +33)</i></td>
            </tr>
            <tr>
                <td colspan="3">
                    Message<br />
                    <textarea id="sms-message" rows="6" name="message"></textarea>
                    <span class="count">140</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <input type="submit" value="Envoyer" style="width:100%; height:30px;" />
                </td>
            </tr>
        </table>
    </form>
</section>
{% endblock %}