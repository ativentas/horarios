$(document).ready(function () {
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$( "#dialogContrato" ).dialog({
    autoOpen: false});
$( "#dialogContrato2" ).dialog({
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

$('.btn_modify_contrato').on("click", function(e){
    e.preventDefault();
    var elemento = $(this);

    dialog_contrato = $( "#dialogContrato2" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "Modificar": modificar_contrato,
        Cancelar: function() {
          dialog_contrato.dialog( "close" );
        }
      },
      close: function() {
      }  
    });
    form = dialog_contrato.find( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    modificar_contrato();
    });

    var contrato_id = $(this).data('id');
    var centro_id = $(this).data('centro_id');

    // /*coger el contrato y ponerlo en el diálogo para poder modificarlo*/
    form[0].reset();
    $('#dialogContrato2 input.contrato_alta').val($("#alta_"+contrato_id).val());
    $('#dialogContrato2 input.contrato_baja').val($("#alta_"+contrato_id).val()); 
    $("#dialogContrato2 select option[value="+centro_id+"]").prop('selected',true);
    dialog_contrato
    .data('contrato_id',contrato_id)
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
            location.reload();
    }).fail(function(data){
        alert(data);
    }); 

    dialog_vigente.dialog( "close" );
}

function modificar_contrato(){
    var contrato_id = $(this).data('contrato_id');
    var form = $('#form_contrato2');
    var url = form.attr('action').replace(':CONTRATO_ID', contrato_id);  
    var data = form.serialize();
    $.post(url, data).done(function(data){
            alert(data);
            location.reload();
    }).fail(function(data){
        alert(data);
    }); 

    dialog_contrato.dialog( "close" );
}

function nuevo_contrato(){
    var form = $('#form_newContrato');
    var url = form.attr('action');
    var data = form.serialize();
    $.post(url, data).done(function(data){
            alert(data);
            location.reload();
    }).fail(function(data){
        alert(data);
    }); 


    dialog_nuevo.dialog("close");
}




}); 

