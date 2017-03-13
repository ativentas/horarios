$(document).ready(function () {
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var estadocuadrante = $('#tabla_plantilla').data('estadocuadrante');
var isadmin = $('#tabla_plantilla').data('isadmin');
var cambios = $('#tabla_plantilla').data('cambios');




//poner en rojo los bordes de donde haya cambios y crear los tooltips
$.each(cambios, function(index,obj){
    var empl = obj['empleado_id'];
    var dia = obj['dia'];
    var situacion = '';
    if(obj['situacion']!=null){
      situacion = obj['situacion'];
    }
    var html = obj['entrada1'].substr(0,5)+' '+obj['salida1'].substr(0,5)+'</br>'+
          obj['entrada2'].substr(0,5)+' '+obj['salida2'].substr(0,5)+'</br>'+
          situacion;
    $('#wrapper-'+empl+'-'+dia).css({"border-style":"solid","border-color":"red"});

    $('#wrapper-'+empl+'-'+dia).tooltip({title: html, html: true});

});





// TO DO: según el caso, habilitar las funciones para modificar la plantilla
switch(estadocuadrante) {
  case '':    
    if(!isadmin){
      $('.btn_modify').show();
      $('.diasemana').css({pointerEvents: "auto"});
      $('#btn_guardar').show();
      $('#div_verificar').show();
    }
    break;    
  case 'Pendiente':
    if(isadmin){
      $('#div_aceptar').show();
    }
    $('.btn_modify').show();
    $('.diasemana').css({pointerEvents: "auto"});
    $('#btn_guardar').show();
    break;
  case 'Aceptado':
    if(!isadmin){
        $('#btn_guardar').show();
    }
    break;  
  case 'AceptadoCambios':
    if(isadmin){
      $('#div_aceptar').show();
    }
    if(!isadmin){
      $('#btn_guardar').show();
    }
    break;
  case 'Archivado':
    alert('Archivado');
    break;
  default:
    alert('Error, el estado de la plantilla no es correcto');
    break;
}

var empleados = [];
$('.datos_empleado').each(function(){
  var id = $(this).data('empleado_id');
  var nombre = $(this).data('empleado_nombre');
  empleados[id] = nombre;
});
var diassemana = [];
diassemana[0] = 'Domingo';
diassemana[1] = 'Lunes';
diassemana[2] = 'Martes';
diassemana[3] = 'Miercoles';
diassemana[4] = 'Jueves';
diassemana[5] = 'Viernes';
diassemana[6] = 'Sabado';

/*TO DO: crear un array con los dias de la semana, para mostrarlos en los diálogo*/
/*TO DO: recibri un array con los empleados, para mostrarlos en los diálogo*/
$( "#dialogHorarioDia-form" ).dialog({
    autoOpen: false});
$( "#dialogAusencia-form" ).dialog({
    autoOpen: false});
$( "#dialogEmpleado-form" ).dialog({
    autoOpen: false});
$( "#dialogAbierto-form" ).dialog({
    autoOpen: false});

$('#btn_guardar').on("click", function(e){
    e.preventDefault();

    var form = $('#form_guardar');
    var url = form.attr('action');   
    var data = form.serialize();
    // console.log(jQuery.type(data));
    $.post(url, data).done(function(data){
            console.log(data);
            alert(data);
            location.reload();
    }).fail(function(data){
        console.log(data);
        alert(data);
    });    
});

$('#boton_solicitarverificacion').click(function(e){
    e.preventDefault();
    if ($('#check_preparado').prop('checked') == false) {
        alert('Tienes que marcar primero para enviarlo');
        //poner el checkbox en rojo
        $('#check_preparado').css('outline','2px solid red');
        return;
    }
    //guardar los datos poniendo la plantilla como pendiente
    $('#cambio_estado').val('Pendiente');
    // $('#btn_guardar').trigger('click');

var deferred = $.Deferred();
deferred.then(function() {
    $('#btn_guardar').trigger('click');
    return;
}).then(function(){
    alert('se va a enviar el horario a la oficina');
    $(location).attr("href", '/cuadrantes');
});

// console.log(deferred)

deferred.resolve();






    //TO DO: lo ideal sería salir a la ruta /cuadrantes, 
    // pero el problema es que el trigger se está ejecutando todavía, 
    // creo que hay que utilizar deferred y promises
    // $(location).attr("href", '/cuadrantes');
});

$('#boton_aceptar').click(function(e){
    e.preventDefault();
    if ($('#check_aceptar').prop('checked') == false) {
        alert('Tienes que marcar primero para aceptarlo');
        //poner el checkbox en rojo
        $('#check_aceptar').css('outline','2px solid red');
        return;
    }
    //lanzar post para modificar estado

    var form = $('#form_aceptar');
    var url = form.attr('action');   
    var data = form.serialize();
    $.post(url, data).done(function(data){
            alert(data);
            $(location).attr("href", '/home'); 
            // location.reload();
    }).fail(function(data){
        alert(data);
    });   


});

$('#btn_añadir_empleado').click(function(e){
    e.preventDefault();
    if(!$('#select_añadir').val()){
        alert('no hay');
        return;
    }
    var empleado = $('#select_añadir').val();
    //lanzar post para añadir empleado al cuadrante. 
    // Luego pedir que guarde datos para ver los cambios
    var form = $('#form_añadir_empleado');
    var url = form.attr('action').replace(':EMPLEADO_ID',empleado);
    var data = form.serialize();

    // por si acaso hay que especificar mas
    // alert('Se va a añadir a '+$('#select_añadir option:selected').val());
    $.post(url, data).done(function(data){
        alert(data);
    }).fail(function(data){
        alert(data);
    });   


});





$('.ausencia').on( "click", function(event) {
  event.stopPropagation();
  var elemento = $(this);
  var situacion = $(this).html();
  if (estadocuadrante=='Archivado'||isadmin==true)
  {
    if (isadmin == true && estadocuadrante !='Archivado'){
      alert('solo el encargado puede proponer modificaciones de datos');
    }
    return;
  }
  $('#container_horarioVT').hide();  
  /*si no es V o L o B o F, no mostrar checkbox*/
  $('#check_trabaja').hide();
  myarray=['V','L','B','F'];
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

  $( "#dialogAusencia-form" ).dialog({ title: 'Horario: '+diassemana[dia]+' - '+empleados[empleado_id] });  
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
  var situacion = $(this).children().first().html();
  // alert('situacion wrapper html: '+situacion);
  if(isadmin||estadocuadrante=='Archivado'){
    return;
  }
  if($.inArray(situacion,['V','B','AJ','AN','L','BP'])){    

    $('#container_horarioL').show();
  } 
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
  // var empleado_nombre = empleados[empleado_id];
  // alert(empleado_nombre);
  var dia = $(this).parent().data('dia');

  /*coger el horario y ponerlo en el diálogo para poder modificarlo*/
  $('#dialogHorarioDia-form input.predefinidos-entrada1').val($("#entrada1_"+dia+"_"+empleado_id).val());
  $('#dialogHorarioDia-form input.predefinidos-salida1').val($("#salida1_"+dia+"_"+empleado_id).val());
  $('#dialogHorarioDia-form input.predefinidos-entrada2').val($("#entrada2_"+dia+"_"+empleado_id).val());
  $('#dialogHorarioDia-form input.predefinidos-salida2').val($("#salida2_"+dia+"_"+empleado_id).val());         

  $( "#dialogHorarioDia-form" ).dialog({ title: 'Horarios: '+diassemana[dia]+' - '+empleados[empleado_id] });  
  dialog_horariodia
    .data( 'empleado_id', empleado_id ) 
    .data( 'dia', dia ) 
    .data( 'elemento', elemento ) 
    .dialog('open');
    form[0].reset();

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

    $( "#dialogEmpleado-form" ).dialog({ title: 'Horario habitual '+empleados[empleado_id] });  
    /*si la situacion es VT, el checkbox tiene que ser otro*/
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
        //TO DO: cambiar la situación de ese día de todos los trabajadores a '' 
        // y quitar el botón de L
        break;
      case 'FC':
        elemento.removeClass('FC').addClass( "FA" );
        $("#estadodia_"+dia).data('estadodia','FA');
        $("#nuevoestadodia_"+dia).val("FA");
        alert('cambiado a '+$("#nuevoestadodia_"+dia).val());
        $('#cambio_apertura_dia').val(dia+'-FA');
        //TO DO: cambiar la situación de ese día de todos los trabajadores a '' 
        // y quitar el botón de F
        break;
    }
  }

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
      case 'F': 
        var entrada1 = $('#dialogAusencia-form input.predefinidos-entrada1').val();
        var salida1 = $('#dialogAusencia-form input.predefinidos-salida1').val();
        var entrada2 = $('#dialogAusencia-form input.predefinidos-entrada2').val();
        var salida2 = $('#dialogAusencia-form input.predefinidos-salida2').val();    
        $("#entrada1_"+dia+"_"+empleado_id).val(entrada1);
        $("#salida1_"+dia+"_"+empleado_id).val(salida1);
        $("#entrada2_"+dia+"_"+empleado_id).val(entrada2);
        $("#salida2_"+dia+"_"+empleado_id).val(salida2);
        if($("#entrada1_"+dia+"_"+empleado_id).val(entrada1) != NULL)
        {$("#situacion_"+dia+"_"+empleado_id).val('FT');}
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
        /*TO DO: hay que cambiar tambien el data('situacion') a ''*/
        elemento.html('');
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
  }else{
    /*Cojo el horario introducido y lo paso a la tabla*/
    var entrada1 = $('#dialogHorarioDia-form input.predefinidos-entrada1').val();
    var salida1 = $('#dialogHorarioDia-form input.predefinidos-salida1').val();
    var entrada2 = $('#dialogHorarioDia-form input.predefinidos-entrada2').val();
    var salida2 = $('#dialogHorarioDia-form input.predefinidos-salida2').val();    
    $("#entrada1_"+dia+"_"+empleado_id).val(entrada1);
    $("#salida1_"+dia+"_"+empleado_id).val(salida1);
    $("#entrada2_"+dia+"_"+empleado_id).val(entrada2);
    $("#salida2_"+dia+"_"+empleado_id).val(salida2);    
  }
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
    $('.predefinidos').val('');
    dialog_horarios.dialog( "close" );
}

}); 

