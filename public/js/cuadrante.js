$(document).ready(function () {

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$( "#dialogHorarioDia-form" ).dialog({
    autoOpen: false});
$( "#dialogAusencia-form" ).dialog({
    autoOpen: false});
$( "#dialogEmpleado-form" ).dialog({
    autoOpen: false});

$('.ausencia').on( "click", function(  ) {
  var elemento = $(this);
  dialog_ausencia = $( "#dialogAusencia-form" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
      width: 300,
      modal: true,
      buttons: {
        "Modificar": modificar_ausencia,
        Cancelar: function() {
          dialog_ausencia.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
      }  
  });
    form = dialog_ausencia.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      modificar_ausencia();
    });
    
  var empleado_id = $(this).parent().data('empleado_id');
  var dia = $(this).data('dia');

  $( "#dialogAusencia-form" ).dialog({ title: 'Dia: '+dia+' - Empleado:'+empleado_id });  
  dialog_ausencia
    .data( 'empleado_id', empleado_id ) 
    .data( 'dia', dia ) 
    .dialog('open');
/*TO DO:mostrar dialogo 
  - si es V, dar la opcion de VT y también mostrar el período de vacaciones
  - si es B, AJ, o AN, ver si se permite hacer algo*/
});

$('.editarhorario').on("click", function() {
  var elemento = $(this);
  dialog_horariodia = $( "#dialogHorarioDia-form" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
      width: 300,
      modal: true,
      buttons: {
        "Modificar": modificar_horariodia,
        Cancelar: function() {
          dialog_horariodia.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
      }  
  });
  form = dialog_horariodia.find( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    modificar_horariodia();
  });

  var empleado_id = $(this).parent().data('empleado_id');
  var dia = $(this).data('dia');

/*TO DO: coger el horario y ponerlo en el diálogo para poder modificarlo*/
  $('#dialogHorarioDia-form input.predefinidos-entrada1').val($("#entrada1_"+dia+"_"+empleado_id).html());
  $('#dialogHorarioDia-form input.predefinidos-salida1').val($("#salida1_"+dia+"_"+empleado_id).html());
  $('#dialogHorarioDia-form input.predefinidos-entrada2').val($("#entrada2_"+dia+"_"+empleado_id).html());
  $('#dialogHorarioDia-form input.predefinidos-salida2').val($("#salida2_"+dia+"_"+empleado_id).html());         

  $( "#dialogHorarioDia-form" ).dialog({ title: 'Dia: '+dia+' - Empleado:'+empleado_id });  
  dialog_horariodia
    .data( 'empleado_id', empleado_id ) 
    .data( 'dia', dia ) 
    .dialog('open');
/*TO DO: dar la opción de dia Libre o se le debe (porque no tiene dia libre esa semana por ejemplo y se
quiere que conste en el saldo de horas a devolver*/
});

$('.btn_modify').on('click', function() {
    var elemento = $(this);
    var form;
    dialog_horarios = $( "#dialogEmpleado-form" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 300,
      width: 300,
      modal: true,
      buttons: {
        "Aplicar": aplicar_horarios,
        Cancelar: function() {
          dialog_horarios.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
      }
    });

    form = dialog_horarios.find( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    aplicar_horarios();
    });

    var empleado_id = $(this).parent().data('empleado_id');

    $( "#dialogEmpleado-form" ).dialog({ title: empleado_id });  
    dialog_horarios
    .data( 'empleado_id', empleado_id ) 
    .dialog('open');
    form[0].reset();
});

function modificar_ausencia(){
  /*si ha marcado VT, */alert('funcion');
}
function modificar_horariodia(){
    var empleado_id = $(this).data('empleado_id');
    var dia = $(this).data('dia');
    /*Cojo el horario introducido y lo paso a la tabla*/
    var entrada1 = $('#dialogHorarioDia-form input.predefinidos-entrada1').val();
    var salida1 = $('#dialogHorarioDia-form input.predefinidos-salida1').val();
    var entrada2 = $('#dialogHorarioDia-form input.predefinidos-entrada2').val();
    var salida2 = $('#dialogHorarioDia-form input.predefinidos-salida2').val();    

    $("#entrada1_"+dia+"_"+empleado_id).html(entrada1);
    $("#salida1_"+dia+"_"+empleado_id).html(salida1);
    $("#entrada2_"+dia+"_"+empleado_id).html(entrada2);
    $("#salida2_"+dia+"_"+empleado_id).html(salida2);    

    dialog_horariodia.dialog( "close" );
}

function aplicar_horarios(){
    /* Array para ver los días que se rellenarán automáticamente al aplicar 
    el horario tipo del empleado para esa semana.
    El índice del array va del Domingo [0] al Sábado [6]*/
    var empleado_id = $(this).data('empleado_id');
    var arrayrellenar = [1,1,1,1,1,1,1];
    $("tr[data-empleado_id='"+empleado_id+"'] td.ausencia" ).each(function() {
        var dia = $(this).data('dia');
        arrayrellenar[dia] = 0;
    });    
    $(".C").each(function() {
        var dia = $(this).data('dia');
        arrayrellenar[dia] = 0;
    });
    $(".FC").each(function() {
        var dia = $(this).data('dia');
        arrayrellenar[dia] = 0;
    });

    /*Cojo el horario tipo*/
    var entrada1 = $('#dialogEmpleado-form input.predefinidos-entrada1').val();
    var salida1 = $('#dialogEmpleado-form input.predefinidos-salida1').val();
    var entrada2 = $('#dialogEmpleado-form input.predefinidos-entrada2').val();
    var salida2 = $('#dialogEmpleado-form input.predefinidos-salida2').val();

    /*recorrer el array y asignar el horario a cada uno de los días*/
    $.each(arrayrellenar, function( index, value ) {
      if (value==1) {
        $("#entrada1_"+index+"_"+empleado_id).html(entrada1);
        $("#salida1_"+index+"_"+empleado_id).html(salida1);
        $("#entrada2_"+index+"_"+empleado_id).html(entrada2);
        $("#salida2_"+index+"_"+empleado_id).html(salida2);
      }
    });
    /*pongo a 00:00 los inputs del dialogo (no hace falta porque creo que el reset borra
    todos los inputs) y cierro el dialogo*/

    dialog_horarios.dialog( "close" );


}



});    