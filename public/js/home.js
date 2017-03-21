$(document).ready(function () {
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$( "#dialogRespuestaNota" ).dialog({
    autoOpen: false});

$('.btn_respuesta').on('click',function(){
    var elemento = $(this);
    var nota_id = $(this).parents("tr").data('nota_id');
    
//prerellenar la nota, si hay respuesta
    var tr = $(this).parents('tr').next();
    var hayrespuesta = tr.data('hayrespuesta');
    if(hayrespuesta=='yes'){
        var visible = tr.data('visible');
        if(visible=='1'){
          $('#check_visible').prop('checked', true);
        }        
        var respuesta = tr.find('.nota_respuesta').text();
        $('#respuesta').val(respuesta);
    }
    dialog_respuesta = $( "#dialogRespuestaNota" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
      width: 380,
      modal: true,
      buttons: {
        "Guardar": guardar_respuesta,
        Cancelar: function() {
          dialog_respuesta.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
      }  
    });
    var form = dialog_respuesta.find( "form" ).on( "submit", function( event ) {
        event.preventDefault();
        guardar_respuesta(nota_id);
    });
    dialog_respuesta.dialog({ title: 'Respuesta a la nota' });  
    dialog_respuesta
    .data('nota_id',nota_id)
    .dialog('open');
    // form[0].reset();

});

function guardar_respuesta(){
    var nota_id = $(this).data('nota_id');
    var form = $('#respuesta_form');
    var url = form.attr('action').replace(':NOTA_ID',nota_id);   
    var data = form.serialize();
    $.post(url, data).done(function(data){
            console.log(data);
            alert(data);
            location.reload();
    }).fail(function(data){
        console.log(data);
        alert(data);
    });    
}



}); 

