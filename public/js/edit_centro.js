$(document).ready(function () {
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$( "#dialogPredefinido" ).dialog({
    autoOpen: false});
$( "#dialog_newPredefinido" ).dialog({
    autoOpen: false});

$('#button_new_predefinido').on("click", function(e){
    e.preventDefault();
    var elemento = $(this);
    dialog_new = $("#dialog_newPredefinido").dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 380,
      width: 350,
      modal: true,
      buttons: {
        "Crear": crear_predefinido,
        Cancelar: function() {
          dialog_new.dialog( "close" );
        }
      },
      close: function() {
      } 
    });

    form = dialog_new.find( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    crear_predefinido();
    });
    dialog_new
    .dialog('open');


});




function crear_predefinido(){
    var form = $('#form_newPredefinido');
    var url = form.attr('action');
    var data = form.serialize();
    $.post(url, data).done(function(data){
            alert(data);
            location.reload();
    }).fail(function(data){
        alert(data);
    }); 


    dialog_new.dialog("close");
}




$('.btn_modify_predefinido').on("click", function(e){
    e.preventDefault();
    var elemento = $(this);

    dialog_predefinido = $( "#dialogPredefinido" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 380,
      width: 350,
      modal: true,
      buttons: {
        "Modificar": modificar_predefinido,
        Cancelar: function() {
          dialog_predefinido.dialog( "close" );
        }
      },
      close: function() {
      }  
    });
    form = dialog_predefinido.find( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    modificar_predefinido();
    });
    
    var predefinido_id = $(this).data('predefinido_id');

    // /*coger el predefinido y ponerlo en el diálogo para poder modificarlo*/
    form[0].reset();
    $('#dialogPredefinido input[name=nombre]').val($("#predefinido_nombre_"+predefinido_id).val());
    $('#dialogPredefinido input[name=entrada1]').val($("#entrada1_"+predefinido_id).val()); 
    $('#dialogPredefinido input[name=salida1]').val($("#salida1_"+predefinido_id).val()); 
    $('#dialogPredefinido input[name=entrada2]').val($("#entrada2"+predefinido_id).val()); 
    $('#dialogPredefinido input[name=salida2]').val($("#salida2_"+predefinido_id).val()); 


    dialog_predefinido
    .data('predefinido_id',predefinido_id)
    .dialog('open');

});


function modificar_predefinido(){
    var predefinido_id = $(this).data('predefinido_id');
    
    var form = $('#form_predefinido');
    var url = form.attr('action').replace(':PREDEFINIDO_ID',predefinido_id);  
    var data = form.serialize();
    $.post(url, data).done(function(data){
            alert(data);
            location.reload();
    }).fail(function(data){
        alert(data);
    }); 

    dialog_predefinido.dialog( "close" );


}




}); 

