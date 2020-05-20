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

// ⚠ fixe le problème d'utilisation tardive de jQuery
//  create global $ and jQuery variables
global.$ = global.jQuery = $;

/* ⚠
 * Pour fixer le menu ☰ Bootstrap avec jQueyy 3.5, il faut modifier le fichier
 * node_modules/bootstrap/dist/js/bootstrap.js
 * remplacer
 *      if (!data && _config.toggle && /show|hide/.test(config)) {
 * par
 *      if (!data && _config.toggle && /show|hide/.test(_config)) {
 * 
 */


console.log('%c app.js ', 'background-color: #222; color: #fff');

/* 
 * Fonction pour  l'affichage de l'image juste après le téléchargement depuis un input file 
 * Déclencher cette fonction dans l'écouteur d'évènement "change" des inputs 
*/
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();     
        reader.onload = function (e) {
            let nom = $(this).attr("name");
            $(input).parent().parent().find("label img").remove();
            $(input).parent().parent().find("label").append("<img class='mini ml-3' src='" + e.target.result + "'>");
            $(input).next('.custom-file-label').html(input.files[0].name);    
        }
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
        readURL(this);
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);    
    });

});