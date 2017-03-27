$(document).ready(function () {
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$( "#dialogContrato" ).dialog({
    autoOpen: false});
$( "#dialog_newContrato" ).dialog({
    autoOpen: false});

$('#button_new_contrato').on("click", function(e){
    e.preventDefault();
    var elemento = $(this);
    dialog_new = $("#dialog_newContrato").dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
      width: 300,
      modal: true,
      buttons: {
        "Crear": crear_contrato,
        Cancelar: function() {
          dialog_new.dialog( "close" );
        }
      },
      close: function() {
      } 
    });

});

function crear_contrato(){

}

$('#button_modificar_vigente').on("click", function(e){
    e.preventDefault();
    var elemento = $(this);

    dialog_vigente = $( "#dialogContrato" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Modificar": modificar_vigente,
        Cancelar: function() {
          dialog_vigente.dialog( "close" );
        }
      },
      close: function() {
      }  
    });
    form = dialog_vigente.find( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    modificar_vigente();
    });
    dialog_vigente
    .dialog('open');

});

$('#button_new_contrato').on("click", function(e){
    e.preventDefault();
    var elemento = $(this);

    dialog_nuevo = $( "#dialog_newContrato" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Crear": nuevo_contrato,
        Cancelar: function() {
          dialog_vigente.dialog( "close" );
        }
      },
      close: function() {
      }  
    });
    form = dialog_nuevo.find( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    nuevo_contrato();
    });
    dialog_nuevo
    .dialog('open');

});

function modificar_vigente(){
    var form = $('#form_contrato_actual');
    var url = form.attr('action');  
    var data = form.serialize();
    $.post(url, data).done(function(data){
            alert(data);
    }).fail(function(data){
        alert(data);
    }); 

    dialog_vigente.dialog( "close" );


}

function nuevo_contrato(){
    var form = $('#form_newContrato');
    var url = form.attr('action');
    alert(url);  
    var data = form.serialize();
    $.post(url, data).done(function(data){
            alert(data);
    }).fail(function(data){
        alert(data);
    }); 


    dialog_nuevo.dialog("close");
}




}); 

