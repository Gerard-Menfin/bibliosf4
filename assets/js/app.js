/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

const $ = require('jquery'); 
require('bootstrap');


/* Fonction pour  l'affichage de l'image juste après le téléchargement depuis un input file 
   Déclencher cette fonction dans l'écouteur d'évènement "change" des inputs 
*/
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function (e) {
            let nom = $(this).attr("name");
            console.log("onload : " + e.target.result, "name: " + nom, $(input));
            $(input).parent().parent().find("label img").remove();
            $(input).parent().parent().find("label").append("<img class='mini ml-3' src='" + e.target.result + "'>");
            console.log("onload : " + e.target.result);
            $(input).next('.custom-file-label').html(input.files[0].name);    
        }
        console.log("reader.readAsDataURL : ", input.files[0]); 
        reader.readAsDataURL(input.files[0]);
    }
}




$(function(){
    $('[data-toggle="popover"]').popover();
    // tables
    $("table.table").addClass("table-secondary table-bordered table-hover");
    $("table").addClass("table");
    
    // forms
    $("[type='file']").change(function(){
        console.log("change");
        readURL(this);
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);    });

    console.log($);
});