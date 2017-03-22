$(document).ready(function () {
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$( "#dialogAsignar-form" ).dialog({
    autoOpen: false});

$('.btn_asignar').on('click', function() {

  var elemento = $(this);
  var form;
  dialog_asignar = $( "#dialogAsignar-form" ).dialog({
    position: { my: "left center", at: "right top", of: elemento }, 
    autoOpen: false,
    height: 300,
    width: 300,
    modal: true,
    buttons: {
      "Aplicar": asignar_compensable,
      Cancelar: function() {
        dialog_asignar.dialog( "close" );
      }
    },
    close: function() {
      form[ 0 ].reset();
    }
  });

  form = dialog_asignar.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      asignar_compensable();
   });

   var compensable_id = $(this).parent().parent().data('compensable_id');
   var empleado_nombre = $(this).parent().parent().data('empleado_nombre');
   $( "#dialogAsignar-form" ).dialog({ title: 'Compensar día '+empleado_nombre });  
   dialog_asignar
    .data( 'compensable_id', compensable_id ) 
    .dialog('open');
   form[0].reset();


});
// $('input[name="genderS"]:checked').val();
$(function(){
  $('input[name="radio_compensar"]').click(function(){
    val = $(this).val();
    switch(val){
      case 'P':
        $('#group_libre').hide();
        $('#group_pagar').show();
        $('#div_fecha').hide();
        $('#div_nota').show();
        break;
      case 'DL':
        $('#group_pagar').hide();        
        $('#group_libre').show();        
        $('#div_fecha').show();        
        $('#div_nota').show();        
        break;
      default:
        alert('Esta opción no puede salir. Revisar programa');
        break;
    }

    // if ($(this).is(':checked'))
    // {
    //   alert($(this).val());
    // }else{
    // alert('not checked');}

  });

});


function asignar_compensable(){
  var compensable_id = $(this).data('compensable_id');
  var form = $('#Asignar-form');
  var url = form.attr('action').replace(':COMPENSABLE_ID',compensable_id);   
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

