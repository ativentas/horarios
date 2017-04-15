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
      height: 300,
      width: 300,
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

});

// function crear_predefinido(){

// }

$('.btn_modify_predefinido').on("click", function(e){
    e.preventDefault();
    var elemento = $(this);

    dialog_predefinido = $( "#dialogPredefinido" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
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
    $('#dialogPredefinido input.nombre').val($("#alta_"+predefinido_id).val());
    $('#dialogPredefinido input.entrada1').val($("#alta_"+predefinido_id).val()); 
    $('#dialogPredefinido input.salida1').val($("#alta_"+predefinido_id).val()); 




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

// $('#button_new_contrato').on("click", function(e){
//     e.preventDefault();
//     var elemento = $(this);

//     dialog_nuevo = $( "#dialog_newContrato" ).dialog({
//       position: { my: "left center", at: "right top", of: elemento }, 
//       autoOpen: false,
//       height: 300,
//       width: 350,
//       modal: true,
//       buttons: {
//         "Crear": nuevo_contrato,
//         Cancelar: function() {
//           dialog_vigente.dialog( "close" );
//         }
//       },
//       close: function() {
//       }  
//     });
//     form = dialog_nuevo.find( "form" ).on( "submit", function( event ) {
//     event.preventDefault();
//     nuevo_contrato();
//     });
//     dialog_nuevo
//     .dialog('open');

// });



// function nuevo_contrato(){
//     var form = $('#form_newContrato');
//     var url = form.attr('action');
//     var data = form.serialize();
//     $.post(url, data).done(function(data){
//             alert(data);
//             location.reload();
//     }).fail(function(data){
//         alert(data);
//     }); 


//     dialog_nuevo.dialog("close");
// }




}); 

