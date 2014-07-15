$("#sms-message").bind('keyup', function(event){
    var max = 140;
    var calcul = max - $("#sms-message").val().length;

    if($("#sms-message").val().length > max)
    {
        $(".count").html('<font color="red">'+ calcul +'</font> Vous avez dépassez la limite de caractères !');
    }
    else
        $(".count").html(calcul);
});