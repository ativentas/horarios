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
$( "#dialogAbierto-form" ).dialog({
    autoOpen: false});

$('.ausencia').on( "click", function(event) {
  event.stopPropagation();
  var elemento = $(this);
  var situacion = $(this).html();
  $('#container_horarioVT').hide();  
/*si no es V o L o B, no mostrar checkbox*/
  $('#check_trabaja').hide();
  myarray=['V','L','B'];
  if(jQuery.inArray(situacion, myarray) !== -1){
    $('#check_trabaja').show();    
  }
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
      modificar_ausencia($(this));
    });
    
  var empleado_id = $(this).parent().parent().parent().data('empleado_id');
  var dia = $(this).parent().parent().data('dia');

  $( "#dialogAusencia-form" ).dialog({ title: 'Dia: '+dia+' - Empleado:'+empleado_id });  
  dialog_ausencia
    .data( 'empleado_id', empleado_id ) 
    .data( 'dia', dia ) 
    .data( 'situacion', situacion ) 
    .data( 'elemento', elemento ) 
    .dialog('open');
/*TO DO:mostrar dialogo 
  - si es V, dar la opcion de VT y también mostrar el período de vacaciones
  - si es B, AJ, o AN, ver si se permite hacer algo*/
});

/*para que cuando en una ausencia (V o L) se marque que trabaja en el diálogo*/
$("#check_trabaja").change(function() {
    if(this.checked) {
      $('#container_horarioVT').show();
      return;
    }
      $('#container_horarioVT').hide();
});
$("#check_libre").change(function() {
    if(this.checked) {
      $('#container_horarioL').hide();
      return;
    }
      $('#container_horarioL').show();
});


$('.wrapper').on("click", function() {
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

  var empleado_id = $(this).parent().parent().data('empleado_id');
  var dia = $(this).parent().data('dia');

/*TO DO: coger el horario y ponerlo en el diálogo para poder modificarlo*/
  $('#dialogHorarioDia-form input.predefinidos-entrada1').val($("#entrada1_"+dia+"_"+empleado_id).val());
  $('#dialogHorarioDia-form input.predefinidos-salida1').val($("#salida1_"+dia+"_"+empleado_id).val());
  $('#dialogHorarioDia-form input.predefinidos-entrada2').val($("#entrada2_"+dia+"_"+empleado_id).val());
  $('#dialogHorarioDia-form input.predefinidos-salida2').val($("#salida2_"+dia+"_"+empleado_id).val());         

  $( "#dialogHorarioDia-form" ).dialog({ title: 'Dia: '+dia+' - Empleado:'+empleado_id });  
  dialog_horariodia
    .data( 'empleado_id', empleado_id ) 
    .data( 'dia', dia ) 
    .data( 'elemento', elemento ) 
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

$('.diasemana').on('click', function() {
    var elemento = $(this);
    var dia = elemento.data('dia');
    dialog_abierto = $( "#dialogAbierto-form" ).dialog({
      position: { my: "left center", at: "right top", of: elemento }, 
      autoOpen: false,
      height: 200,
      width: 300,
      modal: true,
      buttons: {
        "Modificar": cambiar_abierto,
        Cancelar: function() {
          dialog_abierto.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
      }
    });

    form = dialog_abierto.find( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    cambiar_abierto();
    });
    /*premarcar la opción actual*/
    var estado = $("#estadodia_"+dia).data('estadodia');
    var festivo = false;
    if (estado=='FA'|estado=='FC') {
      festivo = true;
    }
    $('#optAbierto').prop('checked',true);
    if(estado=='C'||estado=='FC'){
      $('#optCerrado').prop('checked',true);
    }
    $( "#dialogAbierto-form" ).dialog({ title: 'Modificar Abierto/Cerrado' });  
    dialog_abierto
    .data('festivo',festivo)
    .data('dia',dia)
    .data('estado',estado)
    .data('elemento',elemento)
    .dialog('open');
});

function cambiar_abierto(){
  var dia = $(this).data('dia');
  var estado = $(this).data('estado');
  var festivo = $(this).data('festivo');
  var elemento = $(this).data('elemento');
  if($('#optCerrado').is(':checked')){
    switch (estado){
      case 'C':
      case 'FC':
        alert('ya lo ponia como cerrado');
        break;
      case 'A':
        elemento.removeClass('A').addClass( "C" );
        $("#estadodia_"+dia).data('estadodia','C');
        $("#nuevoestadodia_"+dia).val("C");
        /*borrar todos los horarios de ese día*/       
        $('.horariosdia_'+dia).each(function(){
          $('.horariosdia_'+dia).val('');
        });
        alert('cambiado a '+$("#nuevoestadodia_"+dia).val());
        break;
      case 'FA':
        elemento.removeClass('FA').addClass( "FC" );
        $("#estadodia_"+dia).data('estadodia','FC');
        $("#nuevoestadodia_"+dia).val("FC");
        /*borrar todos los horarios de ese día*/
        $('.horariosdia_'+dia).each(function(){
          $('.horariosdia_'+dia).val('');
        });
        alert('cambiado a '+$("#nuevoestadodia_"+dia).val());
        break;
    }

  }
  if($('#optAbierto').is(':checked')){
    switch (estado){
      case 'A':
      case 'FA':
        alert('ya lo ponia como abierto');
        break;
      case 'C':
        elemento.removeClass('C').addClass( "A" );
        $("#estadodia_"+dia).data('estadodia','A');
        $("#nuevoestadodia_"+dia).val("A");
        alert('cambiado a '+$("#nuevoestadodia_"+dia).val());
        break;
      case 'FC':
        elemento.removeClass('FC').addClass( "FA" );
        $("#estadodia_"+dia).data('estadodia','FA');
        $("#nuevoestadodia_"+dia).val("FA");
        alert('cambiado a '+$("#nuevoestadodia_"+dia).val());
        break;
    }
  }
// si con switch case me apaño, borrar el if siguiente
  // if($('#optCerrado').is(':checked')){
    // if(estado=='C'|estado=='FC'){
    //   alert('ya lo ponia como Cerrado, no hacer nada');      
    // }else{
    //   if(festivo==true){
    //     alert('cambiar a FA');
    //   }
    //   alert('cambiar a A')
    // }
  // }

  dialog_abierto.dialog( "close" );

}


function modificar_ausencia(){
  var empleado_id = $(this).data('empleado_id');
  var dia = $(this).data('dia');
  var situacion = $(this).data('situacion');
  var elemento = $(this).data('elemento');
  /*si ha marcado Trabaja*/

  /*TO DO: si no hay horas trabajadas unchecked*/
  if($("#check_trabaja").is(":checked")) {
    switch (situacion) { 
      case 'V': 
        var entrada1 = $('#dialogAusencia-form input.predefinidos-entrada1').val();
        var salida1 = $('#dialogAusencia-form input.predefinidos-salida1').val();
        var entrada2 = $('#dialogAusencia-form input.predefinidos-entrada2').val();
        var salida2 = $('#dialogAusencia-form input.predefinidos-salida2').val();    
        $("#entrada1_"+dia+"_"+empleado_id).val(entrada1);
        $("#salida1_"+dia+"_"+empleado_id).val(salida1);
        $("#entrada2_"+dia+"_"+empleado_id).val(entrada2);
        $("#salida2_"+dia+"_"+empleado_id).val(salida2);
        $("#situacion_"+dia+"_"+empleado_id).val('VT');
        elemento.hide();
        break;
      case 'L': 
        var entrada1 = $('#dialogAusencia-form input.predefinidos-entrada1').val();
        var salida1 = $('#dialogAusencia-form input.predefinidos-salida1').val();
        var entrada2 = $('#dialogAusencia-form input.predefinidos-entrada2').val();
        var salida2 = $('#dialogAusencia-form input.predefinidos-salida2').val();    
        $("#entrada1_"+dia+"_"+empleado_id).val(entrada1);
        $("#salida1_"+dia+"_"+empleado_id).val(salida1);
        $("#entrada2_"+dia+"_"+empleado_id).val(entrada2);
        $("#salida2_"+dia+"_"+empleado_id).val(salida2);
        $("#situacion_"+dia+"_"+empleado_id).val('');
        elemento.hide();
        break;
      default:
        alert('Error codigo');
    }

  /*TO DO: grabar horarios, y poner VT o nada dependiendo si estaba en V o L respectivamente.
  La idea para L, es que si se trabaja, ya luego se ponga que se le debe, en su caso*/
  
  }
    dialog_ausencia.dialog( "close" );
}

function modificar_horariodia(){
  var empleado_id = $(this).data('empleado_id');
  var dia = $(this).data('dia');
  var elemento = $(this).data('elemento');

  if($("#check_libre").is(":checked")) {
    $("#entrada1_"+dia+"_"+empleado_id).val('');
    $("#salida1_"+dia+"_"+empleado_id).val('');
    $("#entrada2_"+dia+"_"+empleado_id).val('');
    $("#salida2_"+dia+"_"+empleado_id).val('');
    /*mostrar el button, y ponerle L como texto*/
    elemento.children().text('L');
    $('#situacion_'+dia+'_'+empleado_id).val('L');
    elemento.children().show();

  }
  /*Cojo el horario introducido y lo paso a la tabla*/
  var entrada1 = $('#dialogHorarioDia-form input.predefinidos-entrada1').val();
  var salida1 = $('#dialogHorarioDia-form input.predefinidos-salida1').val();
  var entrada2 = $('#dialogHorarioDia-form input.predefinidos-entrada2').val();
  var salida2 = $('#dialogHorarioDia-form input.predefinidos-salida2').val();    

  $("#entrada1_"+dia+"_"+empleado_id).val(entrada1);
  $("#salida1_"+dia+"_"+empleado_id).val(salida1);
  $("#entrada2_"+dia+"_"+empleado_id).val(entrada2);
  $("#salida2_"+dia+"_"+empleado_id).val(salida2);    

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
        $("#entrada1_"+index+"_"+empleado_id).val(entrada1);
        $("#salida1_"+index+"_"+empleado_id).val(salida1);
        $("#entrada2_"+index+"_"+empleado_id).val(entrada2);
        $("#salida2_"+index+"_"+empleado_id).val(salida2);
      }
    });
    /*pongo a 00:00 los inputs del dialogo (no hace falta porque creo que el reset borra
    todos los inputs) y cierro el dialogo*/

    dialog_horarios.dialog( "close" );
}

}); 

