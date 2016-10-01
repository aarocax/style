
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

          var idCustomer = document.getElementsByName('appbundle_appointment[customer]')[0].value;
          $.getJSON('/style.local/web/app_dev.php/customer/get-customers-by-id/'+idCustomer, function(resp) {
              document.getElementsByName('select_customer[customer]')[0].value = resp['name'] + ' ' + resp['lastName'];
          });
      };

      function initializeSchedulesForm(index) {

        // Get the div that holds the collection of schedules
        var collectionHolder = $('div.schedules');

        // setup an "add a schedule" link
        var $newLinkLi = $('<a href="#" id="botton-add" class="btn btn-xs btn-info add_schedule_link"><i class="glyphicon glyphicon-plus glyphicon-white"></i> Añadir un schedule</a>');

        // add the "add a schedule" anchor and li to the addresses div
        //collectionHolder.parent().append($newLinkLi);
        $(".boton-add").append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        collectionHolder.data('index', index);

        // Cuando pulsamos nuevo schedule
        $newLinkLi.on('click', function(e) {
          // add a new form (see next code block)
          addNewScheduleForm(collectionHolder, index);
          // Inicializa los campos autocomplete del nuevo schedule
          initializeAutocompleteFields(e);
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

      function initializeAutocompleteFields(el) {
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
                    document.getElementById("appbundle_appointment_schedules_"+id+"_price").value = ui.item.price;
                    document.getElementById("appbundle_appointment_schedules_"+id+"_precioInicial").innerHTML = ui.item.price;
                    document.getElementById("appbundle_appointment_schedules_"+id+"_precioBeforeChange").innerHTML = 0;
                    document.getElementById("appbundle_appointment_schedules_"+id+"_scheduleTime").innerHTML = ui.item.time;
                    
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
                  $("#appbundle_appointment_schedules_"+i+"_room").val(room);
                  $("#appbundle_appointment_schedules_"+i+"_rooms").val('sala '+room);
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
                $("#appbundle_appointment_schedules_"+i+"_staff").val('2');
                $("#appbundle_appointment_schedules_"+i+"_staffs").val('ana aroca');
              }
          });
          $this.autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
              .append( "<a>" + item.label + " " + item.lastName + "</a>" )
              .appendTo( ul );
          };
        });
      }

      function addNewScheduleForm(collectionHolder, index) {
      
      
        // Get the data-prototype explained earlier
        var prototype = collectionHolder.data('prototype');
        // get the new index
        //var index = collectionHolder.data('index');
        console.log('index: '+index);
        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);
        // increase the index with one for the next item
        collectionHolder.data('index', index + 1);
        // Display the form in the page in an li, before the "Add a schedule" link li
        var $newFormLi = $('<div id="schedule_'+index+'_box"></div>').append(newForm);
        collectionHolder.append($newFormLi);
        // Pone la fecha del appointment en cada schedule
        var fecha = document.getElementById('appbundle_appointment_appointmentDate').value;
        document.getElementById('appbundle_appointment_schedules_'+index+'_scheduleDate').value = fecha;

        //ponemos descuento a 0
        document.getElementById('appbundle_appointment_schedules_'+index+'_discount').value = 0;

        //ponemos precio a 0
        document.getElementById('appbundle_appointment_schedules_'+index+'_price').value = 0;

        console.log('Este es el array índices: '+indices);
        indices.push(index);
        console.log('añado el índice '+index+' al array índices: '+indices);

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
            console.log('se ha eliminado el schedule (index): '+index);
            console.log('se ha eliminado el schedule (id): '+id);
            //delete indices[index];
            //indices.removeItem(index);
            //$pos = indices.indexOf(parseInt(index));
            $pos = indices.indexOf(index);
            indices.splice($pos,1);
            console.log('índices despues de borrar: '+indices);
            // hay que restar al total el importe del servicio elimado
            var price = document.getElementById("appbundle_appointment_schedules_"+index+"_price").value;
            var totalPrice = document.getElementById("totalPrice").innerHTML;
            totalPrice = parseFloat(totalPrice);
            price = parseFloat(price);
            totalPrice = totalPrice - price;
            document.getElementById("totalPrice").innerHTML = totalPrice;
            console.log('recalcular tiempos actualizar todo');
            recalcularTiempos(index, indices);
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
            console.log('ha cambiado la sala');
            var room = document.getElementById("appbundle_appointment_schedules_"+index+"_room").value;
            document.getElementById("appointmentRoom").innerHTML = room;
        });
      }

      function initSchedules() {
        $schedules = document.getElementsByClassName('col-md-12 well schedule');
        for (var i = $schedules.length - 1; i >= 0; i--) {
          console.log($schedules[i]);
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
                    var roomId = document.getElementById("appbundle_appointment_schedules_"+i+"_room").value;
                    $.getJSON('/style.local/web/app_dev.php/room/get-room-by-id/'+roomId, function(resp) {
                      $("#appbundle_appointment_schedules_"+i+"_rooms").val(resp['name']);        
                    });
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
                  // inicializamos el campo
                  var roomId = document.getElementById("appbundle_appointment_schedules_"+i+"_staff").value;
                  $.getJSON('/style.local/web/app_dev.php/staff/get-staff-by-id/'+roomId, function(resp) {
                    $("#appbundle_appointment_schedules_"+i+"_staffs").val(resp['name']+' '+resp['lastName']);        
                  });
                }
            });
            $this.autocomplete( "instance" )._renderItem = function( ul, item ) {
              return $( "<li>" )
                .append( "<a>" + item.label + " " + item.lastName + "</a>" )
                .appendTo( ul );
            };
          });

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
                      document.getElementById("appbundle_appointment_schedules_"+id+"_price").value = ui.item.price;
                      document.getElementById("appbundle_appointment_schedules_"+id+"_precioInicial").innerHTML = ui.item.price;
                      document.getElementById("appbundle_appointment_schedules_"+id+"_precioBeforeChange").innerHTML = 0;
                      document.getElementById("appbundle_appointment_schedules_"+id+"_scheduleTime").innerHTML = ui.item.time;
                      
                      // actualizamos el precio total
                      $('#appbundle_appointment_schedules_'+id+'_price').trigger("change");

                      // cuando se pone un servicio se habilita el botón para añadir más schedules
                      //document.getElementById('botton-add').style.pointerEvents = 'auto';
                  },
                  create: function(event, ui){
                    // inicializamos el campo
                    var roomId = document.getElementById("appbundle_appointment_schedules_"+i+"_service").value;
                    $.getJSON('/style.local/web/app_dev.php/service/get-service-by-id/'+roomId, function(resp) {
                      $("#appbundle_appointment_schedules_"+i+"_services").val(resp['name']);        
                    });
                  }
              });
          });
        }
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

      function calculateHour(time, id) {
        console.log('cuando calculo la hora: '+indices+' id: '+id);
        $position = indices.indexOf(parseInt(id));
        console.log('position: '+$position);
        console.log('valor de position: '+indices[$position-1]);
        if (id == 0) {
            //tomamos la fecha del schedule
            var scheduleDate = document.getElementById('appbundle_appointment_appointmentDate').value;
            //tomamos la hora del schedule
            var scheduleTime =  document.getElementById("appointmentHour").innerHTML;
        } else {
            ids = id - 1;
            ids = indices[$position-1];
            console.log(ids);
            //  ugdgids = indices - 1;
            var scheduleDate = document.getElementById("appbundle_appointment_schedules_"+ids+"_scheduleDate").value;
            var scheduleTime =  document.getElementById("appbundle_appointment_schedules_"+ids+"_finishHour").value;
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

        document.getElementById("appbundle_appointment_schedules_"+id+"_startingHour").value = startTime;
        document.getElementById("appbundle_appointment_schedules_"+id+"_finishHour").value = endTime;

        // verificar sihay otra cita a la misma hora en la misma sala
        //tomo el valor de la sala
        var room = document.getElementById("appbundle_appointment_schedules_"+id+"_room").value;
        // tomo la fecha
        //var date = document.getElementById("appbundle_appointment_schedules_"+id+"_scheduleDate").value;

        var datos = { st: startTime, et : endTime, room: room, scheduleDate: scheduleDate} 

        $.ajax({
          url: "http://localhost/style.local/web/app_dev.php/schedule/exist-schedule-startHour",
          type: 'GET',
          data: { st: startTime, et : endTime, room: room, scheduleDate: scheduleDate} ,
          context: document.body
        }).done(function( data ) {
            if (data.length == 0) {

            } else {
                $('#schedule_'+id+'_box .well').css("background-color","#C90000");
                $( '#schedule_'+id+'_box .well #message' ).append( "<span><h2>Esta hora cincide con ota cita</h2></span>" );
                $( '#schedule_'+id+'_box .well #message h2' ).css("color","#FFF");
            }

          
        });

        $.ajax({
          url: "http://localhost/style.local/web/app_dev.php/schedule/exist-schedule-finishHour",
          type: 'GET',
          data: { st: startTime, et : endTime, room: room, scheduleDate: scheduleDate} ,
          context: document.body
        }).done(function( data ) {
            if (data.length == 0) {

            } else {
                $('#schedule_'+id+'_box .well').css("background-color","#C90000");
                $( '#schedule_'+id+'_box .well #message' ).append( "<span><h2>Esta hora cincide con ota cita</h2></span>" );
                $( '#schedule_'+id+'_box .well #message h2' ).css("color","#FFF");
            }

          
        });
      }

      function recalcularTiempos(index, indices) {

        console.log('*********recalcular tiempos***************');
        console.log('index que borramos: '+index);
        console.log('array de schedules: '+indices);
        for (var i=0; i<indices.length; i++) {
          if (indices[i] > index) {
            console.log(index+' es menor que: '+indices[i]);
            // recalcular si todos están en la misma sala, no hace falta ver si hay hueco

            $time = document.getElementById("appbundle_appointment_schedules_"+indices[i]+"_scheduleTime").innerHTML;
            console.log('Time: '+$time);
            calculateHour($time, indices[i])
          };
        }
      };

      function countschedules() {
        $schedules = document.getElementsByClassName('col-md-12 well schedule');
        return $schedules.length;
      }

      function AppLoad() {
        initializeMainForm();
        initializeSchedulesForm(countschedules());
        initSchedules();
        
      }

      var indices = new Array();
      var $sch = countschedules();
      console.log($sch);
      var a = 0;
      for (var i = $sch - 1; i >= 0; i--) {
        console.log(a);
        indices.push(a);
        a++;
      }
      console.log(indices);
      window.addEventListener('load', AppLoad, false);
