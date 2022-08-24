/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */


import './sass/app.scss';

// start the Stimulus application
import './bootstrap';

//require jQuery
const $ = require('jquery');

// create global $ and jQuery variables
 global.$ = global.jQuery = $;

 //bootstrap js
 require('bootstrap'); 

 //bootstrap colorpicker
 require('bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css');
 require('bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');

 //font awesome
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

$(function(){
    $(document).on('click','.delete_item',function(){
        var id = $(this).data('id');
        $("#yes_delete_item").attr('data-id',id);
        $("#confirm_delete_item").modal('show');
    });

    $('#confirm_delete_item').on('hidden.bs.modal', function () {
        $("#yes_delete_item").attr('data-id',0);
    })

    $(document).on('click','#yes_delete_item',function(){
        var id = $(this).data('id');
        $.ajax({
            url: "/item/delete/" + id,
            method: 'POST',
            data: { id: id },
            success: function(data) {
                location.reload();
            }
        })
    });

    $(document).on('click','.delete_list',function(){
        var id = $(this).data('id');
        $("#yes_delete_list").attr('data-id',id);
        $("#confirm_delete_list").modal('show');
    });

    $(document).on('click','#yes_delete_list',function(){
        var id = $(this).data('id');
        $.ajax({
            url: "/list/delete/" + id,
            method: 'POST',
            data: { id: id },
            success: function(data) {
                location.reload();
            }
        })
    });

});