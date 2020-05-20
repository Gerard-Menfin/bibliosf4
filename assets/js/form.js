import '../css/form.scss';

$(function(){
    // ⚠ : il faut utiliser 'prop' plutôt que 'attr'
    // var selecteur = "[name='abonne[roles][]']";
    console.log('%c form.js ', 'background: #222; color: #bada55');
    var selecteur = ".unique :checkbox";
    $(selecteur).change(function() {
        console.log("checkbox change:" + selecteur);
        $(selecteur).parent().find(":checkbox").prop("checked", false);
        $(this).prop("checked", true);
    });
});
