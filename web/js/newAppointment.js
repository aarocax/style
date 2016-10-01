function initializeMainForm() {
  $('.autocomplete').each(function(i, el) {
      var $this = $(el);
      $this.autocomplete({
          source: $this.data('url'),
          minLength: 1,
          select: function( event, ui ) {
              $('#' + $this.data('id')).val(ui.item.id);
              event.preventDefault();
              this.value = ui.item.label+ ' ' +ui.item.lastName;
          }
      });
      $this.autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<a>" + item.label + " " + item.lastName + "</a>" )
          .appendTo( ul );
      };
  });
}

function initializeSchedulesForm() {
  // Get the div that holds the collection of addresses
  var collectionHolder = $('div.schedules');

  // setup an "add a schedule" link
  //var $newLinkLi = $('');

  // add the "add a schedule" anchor and li to the addresses div
  //collectionHolder.parent().append($newLinkLi);
  //$(".boton-add").append($newLinkLi);

  // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)
  collectionHolder.data('index', collectionHolder.find(':input').length);

  // Cuando pulsamos nuevo schedule
  $("#botton-add").on('click', function(e) {
    // add a new form (see next code block)
    addNewScheduleForm(collectionHolder);

    // tomamos el índice del schedule que se ha creado para pasarlo a los autocomplete
    var index = collectionHolder.data('index');
    
    // Inicializa los campos autocomplete del nuevo schedule
    initializeAutocompleteFields(index-1, e);
    // cuando se ñade un servicio se deshabilita el botón de añadir más hasta que este no tiene un servicio
    //document.getElementById('botton-add').style.pointerEvents = 'none';
  });

  // Cuando eliminamos un formulario de schedule
  $(document).on('click', '.close', function(){
    $(this).closest('.schedule').fadeOut(0, function() {
        $(this).remove();
    });
  });
}

function addNewScheduleForm(collectionHolder) {
  

  // Get the data-prototype explained earlier
  var prototype = collectionHolder.data('prototype');
  // get the new index

  var index = collectionHolder.data('index');
  console.log('index en add: '+index);
  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have
  var newForm = prototype.replace(/__name__/g, index);
  // increase the index with one for the next item
  collectionHolder.data('index', index + 1);
  // Display the form in the page in an li, before the "Add a schedule" link li
  var $newFormLi = $('<div id="schedule_'+index+'_box"></div>').append(newForm);
  collectionHolder.append($newFormLi);

  // Ponemos el índice del schedule en el array
  $schedules.push(index);
  

  // Pone la fecha del appointment en cada schedule
  var fecha = document.getElementById('appbundle_appointment_appointmentDate').value;
  document.getElementById('appbundle_appointment_schedules_'+index+'_scheduleDate').value = fecha;

  //ponemos descuento a 0
  document.getElementById('appbundle_appointment_schedules_'+index+'_discount').value = 0;

  //ponemos precio a 0
  document.getElementById('appbundle_appointment_schedules_'+index+'_price').value = 0;

  

  // cuando se actualiza un descuento
  $('#appbundle_appointment_schedules_'+index+'_discount').on('blur', function(id) { 
      var price = document.getElementById("appbundle_appointment_schedules_"+index+"_precioInicial").innerHTML;
      var discount = document.getElementById("appbundle_appointment_schedules_"+index+"_discount").value;
      discount = (discount / 100) * price;
      price = price - discount;
      price = Math.round(price * 100) / 100
      document.getElementById("appbundle_appointment_schedules_"+index+"_price").value = price;
      // actualizamos el precio total
      $('#appbundle_appointment_schedules_'+index+'_price').trigger("change");
  });

  // cuando cambia un precio
  $('#appbundle_appointment_schedules_'+index+'_price').change(function(){
      var precioBeforeChange = document.getElementById("appbundle_appointment_schedules_"+index+"_precioBeforeChange").innerHTML;
      precioBeforeChange = parseFloat(precioBeforeChange);
      var totalPrice = document.getElementById("totalPrice").innerHTML;
      var price = document.getElementById("appbundle_appointment_schedules_"+index+"_price").value;
      totalPrice = parseFloat(totalPrice);
      price = parseFloat(price);
      totalPrice = (totalPrice - precioBeforeChange) + price;
      document.getElementById("totalPrice").innerHTML = totalPrice;
      document.getElementById("appbundle_appointment_schedules_"+index+"_precioBeforeChange").innerHTML = price;
  });

  // cuando se elimina un elemento
  $('#appbundle_appointment_schedules_'+index+'_index').on('click', function(id) {
      $pos = $schedules.indexOf(index);
      $schedules.splice($pos,1);
      // hay que restar al total el importe del servicio elimado
      var price = document.getElementById("appbundle_appointment_schedules_"+index+"_price").value;
      var totalPrice = document.getElementById("totalPrice").innerHTML;
      totalPrice = parseFloat(totalPrice);
      price = parseFloat(price);
      totalPrice = totalPrice - price;
      document.getElementById("totalPrice").innerHTML = totalPrice;
      recalcularTiempos(index, $schedules);
  });

  // cuando cambia la hora de inicio
  $('#appbundle_appointment_schedules_'+index+'_startingHour').on('click', function(id) {
      console.log('ha cambiado la hora de inicio');
  });

  // cuando cambia la hora de fin
  $('#appbundle_appointment_schedules_'+index+'_finishHour').on('click', function(id) {
      console.log('ha cambiado la hora de fin');
  });

  // when room change
  $('#appbundle_appointment_schedules_'+index+'_rooms').on('blur', function(id) {
      var room = document.getElementById("appbundle_appointment_schedules_"+index+"_room").value;
      document.getElementById("appointmentRoom").innerHTML = room;
  });
}

function initializeAutocompleteFields(index, el) {
  // este recoge el autocomplete de service
  $('.autocompleteservice').each(function(i, el) {
      var $this = $(el);
      $this.autocomplete({
          source: $this.data('url'),
          minLength: 1,
          select: function( event, ui ) {

              $('#' + $this.data('id')).val(ui.item.id);
              var id = getIdLine($this.data('id'));
              $hour = calculateHour(ui.item.time, id);
              document.getElementById("appbundle_appointment_schedules_"+index+"_price").value = ui.item.price;
              document.getElementById("appbundle_appointment_schedules_"+index+"_precioInicial").innerHTML = ui.item.price;
              document.getElementById("appbundle_appointment_schedules_"+index+"_precioBeforeChange").innerHTML = 0;
              document.getElementById("appbundle_appointment_schedules_"+index+"_scheduleTime").innerHTML = ui.item.time;
              
              // actualizamos el precio total
              $('#appbundle_appointment_schedules_'+id+'_price').trigger("change");

              // cuando se pone un servicio se habilita el botón para añadir más schedules
              //document.getElementById('botton-add').style.pointerEvents = 'auto';
          }
      });
  });

  // este recoge el autocomplete de rooms
  $('.autocompleteroom').each(function(i, el) {
      var $this = $(el);
      $this.autocomplete({
          source: $this.data('url'),
          minLength: 1,
          select: function( event, ui ) {
              $('#' + $this.data('id')).val(ui.item.id);
          },
          create: function(event, ui){
            // inicializamos el campo
            var room = document.getElementById("appointmentRoom").innerHTML;
            $("#appbundle_appointment_schedules_"+index+"_room").val(room);
            $("#appbundle_appointment_schedules_"+index+"_rooms").val('sala '+room);
          }
      });
  });

  // este recoge el autocomplete de staff
  $('.autocompletestaff').each(function(i, el) {
    var $this = $(el);
    $this.autocomplete({
        source: $this.data('url'),
        minLength: 1,
        select: function( event, ui ) {
            $('#' + $this.data('id')).val(ui.item.id);
            event.preventDefault();
            this.value = ui.item.label+ ' ' +ui.item.lastName;
        },
        create: function(event, ui){
          $("#appbundle_appointment_schedules_"+index+"_staff").val('2');
          $("#appbundle_appointment_schedules_"+index+"_staffs").val('ana aroca');
        }
    });
    $this.autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a>" + item.label + " " + item.lastName + "</a>" )
        .appendTo( ul );
    };
  });
}

function getIdLine(str) {
  var id = str.substring(32,34);
  var stringLength = id.length;
  var lastChar = id.charAt(stringLength - 1);
  if (lastChar === '_') {
      id = id.substring(0, stringLength-1);
  }
  return id;
}

// Esta función se ejecuta cuado se selecciona un servicio
// Time: tiempo en minutos que se tarda en ejecutar el schedule
// idSchedule: id del schedule
function calculateHour(time, idSchedule) {
  $position = $schedules.indexOf(parseInt(idSchedule));
  if ($position == 0) {
    // si es el primero tomamos la fecha y hora del schedule
    var scheduleDate = document.getElementById('appbundle_appointment_appointmentDate').value;
    var scheduleTime =  document.getElementById("appointmentHour").innerHTML;
  } else {
    // Si no es el primero, tomamos la fecha y la hora del schedule inmediatamente anterior
    var scheduleDate = document.getElementById("appbundle_appointment_schedules_"+$schedules[$position-1]+"_scheduleDate").value;
    var scheduleTime =  document.getElementById("appbundle_appointment_schedules_"+$schedules[$position-1]+"_finishHour").value;
  }

  // Creamos un objeto DateTime con la feha y hora del schedule
  var scheduleDateTime = new Date(scheduleDate+' '+scheduleTime);

  // Creamos un objeto DateTime con la feha y hora del schedule + tiempo del servicio
  var scheduleDateTimeService = new Date(scheduleDateTime.getTime() + time*60000);

  var formatTime = function(data) {
    if (data < 10) {data = '0'+data;}
    return data;
  }

  var startTimeHour = formatTime(scheduleDateTime.getHours());
  var startTimeMin  = formatTime(scheduleDateTime.getMinutes());
  var endTimeHour   = formatTime(scheduleDateTimeService.getHours());
  var endTimeMin    = formatTime(scheduleDateTimeService.getMinutes());

  var startTime = startTimeHour+':'+startTimeMin;
  var endTime   = endTimeHour+':'+endTimeMin;

  document.getElementById("appbundle_appointment_schedules_"+idSchedule+"_startingHour").value = startTime;
  document.getElementById("appbundle_appointment_schedules_"+idSchedule+"_finishHour").value = endTime;

  // ******* verificar si hay otra cita a la misma hora en la misma sala
  //tomo el valor de la sala
  var room = document.getElementById("appbundle_appointment_schedules_"+idSchedule+"_room").value;
  var datos = { st: startTime, et : endTime, room: room, scheduleDate: scheduleDate} 

  $.ajax({
    url: "http://localhost/style.local/web/app_dev.php/schedule/exist-schedule-inThisHour",
    type: 'GET',
    data: { st: startTime, et : endTime, room: room, scheduleDate: scheduleDate} ,
    context: document.body
  }).done(function( data ) {
      if (data.length == 0) {
        $('#schedule_'+idSchedule+'_box .well').css("background-color","#f5f5f5");
        $('#schedule_message_'+idSchedule).remove();
      } else {
        $('#schedule_'+idSchedule+'_box .well').css("background-color","#C90000");
        $('#schedule_'+idSchedule+'_box .well #message' ).append( "<span id='schedule_message_"+idSchedule+"'><h2>Esta hora cincide con ota cita en la start hour</h2></span>" );
        $( '#schedule_'+idSchedule+'_box .well #message h2' ).css("color","#FFF");
      }

    
  });
}

function recalcularTiempos(index, $schedules) {
  for (var i=0; i<$schedules.length; i++) {
    if ($schedules[i] > index) {
      // recalcular si todos están en la misma sala, no hace falta ver si hay hueco
      $time = document.getElementById("appbundle_appointment_schedules_"+$schedules[i]+"_scheduleTime").innerHTML;
      calculateHour($time, $schedules[i])
    };
  }
};

function AppLoad() {
  initializeMainForm();
  initializeSchedulesForm();

  // hacemos el primer clik en añadir servicio
  document.getElementById('botton-add').click();
}

var $schedules = new Array();
window.addEventListener('load', AppLoad, false);