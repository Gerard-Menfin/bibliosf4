$(function(){
    // ⚠ : il faut utiliser 'prop' plutôt que 'attr'
    var selecteur = "[name='abonne[roles][]']";
    selecteur = ".unique :checkbox";
    $(selecteur).change(function() {
        console.log("checkbox change:" + selecteur);
        $(selecteur).prop("checked", false);
        $(this).prop("checked", true);
    });
});
