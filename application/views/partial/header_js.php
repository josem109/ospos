
<script type="text/javascript">
	function obtener_valor_dolar(url) {
    // Obtener el contenido de la página web
	
    fetch(url)
        .then(response => response.text())
        .then(html => {
            // Buscar el valor del campo utilizando una expresión regular
            const pattern = `/<div id="dolar".*?<strong>(.*?)<\/strong>/s`;
            const matches = html.match(pattern);
			alert('Continua');
            if (matches) {
                const valor = matches[1];
                console.log(valor);
				alert(valor);
            } else {
				
                console.log('No se encontró el campo en la página web.');
            }
        })
        .catch(error => console.log(error));
}
function actualizarPaginaBoton() {
	try{
	
	//obtener_valor_dolar('https://www.bcv.org.ve/');
	jQuery.ajax({
    type: "POST",
    url: 'http://localhost/ospos/public/dolarpost.php',
    dataType: 'json',
    data: {functionname: 'getDolar'},
    success: function (obj, textstatus) {
		//alert('Solicitud AJAX realizada.');
				  //alert('exito');
		          if( !('error' in obj) ) {
                      yourVariable = obj.result;
					  alert(obj.result);
					  $("#miAlerta").hide();
					  location.reload();
					  window.location.href = 'http://localhost/ospos/public/sales';
                  }
                  else {
                      console.log(obj.error);
					  alert(obj.error);
                  }
            },
    error: function (jqXHR, textStatus, errorThrown) {
		//alert('Solicitud AJAX error.');
        // Este bloque de código se ejecutará si la solicitud AJAX falla
        console.log('Error: ' + textStatus + ' ' + errorThrown);
        //alert('Ha ocurrido un error al realizar la solicitud.');
    }
   });
    
   //alert('Se ha actualizado la tasa de cambio!');
     //location.reload();
 
}//end try
 catch (error) {
        console.log('Ha ocurrido un error: ', error);
		alert('error');
        // Maneja el error aquí...
    }
}
		//Refresh Page at 7AM
function actualizarPagina() {
	try{
		
  			let ahora = new Date();
  			let hora = ahora.getHours();
  			let minuto = ahora.getMinutes();
  			let segundo = ahora.getSeconds();
		
  if ((hora == 16 && minuto == 15 && segundo == 00) || (hora == 13 && minuto == 05 && segundo == 00)) {
	$("#miAlerta").show();
	//obtener_valor_dolar('https://www.bcv.org.ve/');
	jQuery.ajax({
    type: "POST",
    url: 'http://localhost/ospos/public/dolarpost.php',
    dataType: 'json',
    data: {functionname: 'getDolar'},
    success: function (obj, textstatus) {
		//alert('Solicitud AJAX realizada.');
				  //alert('exito');
		          if( !('error' in obj) ) {
                      yourVariable = obj.result;
					  $("#miAlerta").hide();
					  location.reload();
					  window.location.href = 'http://localhost/ospos/public/sales';
					  
                  }
                  else {
                      console.log(obj.error);
					  alert(obj.error);
                  }
            },
    error: function (jqXHR, textStatus, errorThrown) {
		//alert('Solicitud AJAX error.');
        // Este bloque de código se ejecutará si la solicitud AJAX falla
        console.log('Error: ' + textStatus + ' ' + errorThrown);
        //alert('Ha ocurrido un error al realizar la solicitud.');
    }
   });
    //alert('Se ha actualizado la tasa de cambio!');

     location.reload();
  } else {
    setTimeout(actualizarPagina, 1000);
  }
}//end try
 catch (error) {
        console.log('Ha ocurrido un error: ', error);
		alert('error');
        // Maneja el error aquí...
    }
}
	// live clock
	var clock_tick = function clock_tick() {
		setInterval('update_clock();', 1000);
		actualizarPagina();
	}

	// start the clock immediatly
	clock_tick();

	var update_clock = function update_clock() {
		document.getElementById('liveclock').innerHTML = moment().format("<?php echo dateformat_momentjs($this->config->item('dateformat').' '.$this->config->item('timeformat'))?>");
	}

	$.notifyDefaults({ placement: {
		align: "<?php echo $this->config->item('notify_horizontal_position'); ?>",
		from: "<?php echo $this->config->item('notify_vertical_position'); ?>"
	}});

	var cookie_name = "<?php echo $this->config->item('cookie_prefix').$this->config->item('csrf_cookie_name'); ?>";

	var csrf_token = function() {
		return Cookies.get(cookie_name);
	};

	var csrf_form_base = function() {
		return { <?php echo $this->security->get_csrf_token_name(); ?> : function () { return csrf_token();  } };
	};

	var setup_csrf_token = function() {
		$('input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]').val(csrf_token());
	};

	var ajax = $.ajax;

	$.ajax = function() {
		var args = arguments[0];
		if (args['type'] && args['type'].toLowerCase() == 'post' && csrf_token()) {
			if (typeof args['data'] === 'string')
			{
				args['data'] += '&' + $.param(csrf_form_base());
			}
			else
			{
				args['data'] = $.extend(args['data'], csrf_form_base());
			}
		}

		return ajax.apply(this, arguments);
	};

	$(document).ajaxComplete(setup_csrf_token);

	var submit = $.fn.submit;

	$.fn.submit = function() {
		setup_csrf_token();
		submit.apply(this, arguments);
	};

	
</script>
