<div id="required_fields_message"><?php echo $this->lang->line('common_fields_required_message'); ?></div>

<ul id="error_message_box" class="error_message_box"></ul>

<?php echo form_open("sales/save/".$sale_info['sale_id'], array('id'=>'sales_edit_form', 'class'=>'form-horizontal')); ?>
	<fieldset id="sale_basic_info">
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('sales_receipt_number'), 'receipt_number', array('class'=>'control-label col-xs-3')); ?>
			<?php echo anchor('sales/receipt/'.$sale_info['sale_id'], 'POS ' . $sale_info['sale_id'], array('target'=>'_blank', 'class'=>'control-label col-xs-8', "style"=>"text-align:left"));?>
		</div>
		
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('sales_date'), 'date', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array('name'=>'date','value'=>to_datetime(strtotime($sale_info['sale_time'])), 'class'=>'datetime form-control input-sm'));?>
			</div>
		</div>

		<?php
		if($this->config->item('invoice_enable') == TRUE)
		{
		?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('sales_invoice_number'), 'invoice_number', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-8'>
					<?php if(!empty($sale_info["invoice_number"]) && isset($sale_info['customer_id']) && !empty($sale_info['email'])): ?>
						<?php echo form_input(array('name'=>'invoice_number', 'size'=>10, 'value'=>$sale_info['invoice_number'], 'id'=>'invoice_number', 'class'=>'form-control input-sm'));?>
						<a id="send_invoice" href="javascript:void(0);"><?php echo $this->lang->line('sales_send_invoice');?></a>
					<?php else: ?>
						<?php echo form_input(array('name'=>'invoice_number', 'value'=>$sale_info['invoice_number'], 'id'=>'invoice_number', 'class'=>'form-control input-sm'));?>
					<?php endif; ?>
				</div>
			</div>
		<?php
		}
		?>

		<?php
		if($balance_due)
		{
		?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('sales_payment'), 'payment_new', array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php echo form_dropdown('payment_type_new', $new_payment_options, $payment_type_new, array('id'=>'payment_types_new', 'class'=>'form-control')); ?>
				</div>
				<div class='col-xs-4'>
					<div class="input-group input-group-sm">
						<?php if(!currency_side()): ?>
							<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
						<?php endif; ?>
						<?php echo form_input(array('name'=>'payment_amount_new', 'value'=>$payment_amount_new, 'id'=>'payment_amount_new', 'class'=>'form-control input-sm'));?>
						<?php if(currency_side()): ?>
							<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		}
		?>

		<?php 
		$i = 0;
		foreach($payments as $row)
		{
		?>
			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('sales_payment'), 'payment_'.$i, array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php // no editing of Gift Card payments as it's a complex change ?>
					<?php echo form_hidden('payment_id_'.$i, $row->payment_id); ?>
					<?php if( !empty(strstr($row->payment_type, $this->lang->line('sales_giftcard'))) ): ?>
						<?php echo form_input(array('name'=>'payment_type_'.$i, 'value'=>$row->payment_type, 'id'=>'payment_type_'.$i, 'class'=>'form-control input-sm', 'readonly'=>'true'));?>
					<?php else: ?>
						<?php echo form_dropdown('payment_type_'.$i, $payment_options, $row->payment_type, array('id'=>'payment_types_'.$i, 'class'=>'form-control')); ?>
					<?php endif; ?>
				</div>
				<div class='col-xs-4'>
					<div class="input-group input-group-sm">
						<?php if(!currency_side()): ?>
							<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
						<?php endif; ?>
						<?php echo form_input(array('name'=>'payment_amount_'.$i, 'value'=>round($row->payment_amount, 2), 'id'=>'payment_amount_'.$i, 'class'=>'form-control input-sm', 'readonly'=>'true'));?>
						<?php if(currency_side()): ?>
							<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="form-group form-group-sm">
				<?php echo form_label($this->lang->line('sales_refund'), 'refund_'.$i, array('class'=>'control-label col-xs-3')); ?>
				<div class='col-xs-4'>
					<?php // no editing of Gift Card payments as it's a complex change ?>
					<?php if( !empty(strstr($row->payment_type, $this->lang->line('sales_giftcard'))) ): ?>
						<?php echo form_input(array('name'=>'refund_type_'.$i, 'value'=>$this->lang->line('sales_cash'), 'id'=>'refund_type_'.$i, 'class'=>'form-control input-sm', 'readonly'=>'true'));?>
					<?php else: ?>
						<?php echo form_dropdown('refund_type_'.$i, $payment_options, $this->lang->line('sales_cash'), array('id'=>'refund_types_'.$i, 'class'=>'form-control')); ?>
					<?php endif; ?>
				</div>
				<div class='col-xs-4'>
					<div class="input-group input-group-sm">
						<?php if(!currency_side()): ?>
							<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
						<?php endif; ?>
						<?php echo form_input(array('name'=>'refund_amount_'.$i, 'value'=>$row->cash_refund, 'id'=>'refund_amount_'.$i, 'class'=>'form-control input-sm', 'readonly'=>'true'));?>
						<?php if(currency_side()): ?>
							<span class="input-group-addon input-sm"><b><?php echo $this->config->item('currency_symbol'); ?></b></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php 
			++$i;
		}
		echo form_hidden('number_of_payments', $i);			
		?>
		
		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('sales_customer'), 'customer', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array('name'=>'customer_name', 'value'=>$selected_customer_name, 'id'=>'customer_name', 'class'=>'form-control input-sm'));?>
				<?php echo form_hidden('customer_id', $selected_customer_id);?>
			</div>
		</div>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('sales_employee'), 'employee', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_input(array('name'=>'employee_name', 'value'=>$selected_employee_name, 'id'=>'employee_name', 'class'=>'form-control input-sm'));?>
				<?php echo form_hidden('employee_id', $selected_employee_id);?>
			</div>
		</div>

		<?php
		$has_adeudado = false;
		if(isset($payments)) {
			foreach($payments as $payment) {
				if($payment->payment_type === "Adeudado") {
					$has_adeudado = true;
					break;
				}
			}
		}
		?>

		<?php if($show_payment_section): ?>
			<?php if($has_adeudado): ?>
				<div class="form-group form-group-sm">
					<?php echo form_label($this->lang->line('sales_payment_debt_amount'), 'employee', array('class'=>'control-label col-xs-3')); ?>
					<div class='col-xs-4'>
						<?php 
						// Obtener el monto adeudado actual
						$adeudado_amount = 0;
						foreach($payments as $payment) {
							if($payment->payment_type === "Adeudado") {
								$adeudado_amount = $payment->payment_amount;
								break;
							}
						}
						?>
						<?php echo form_input(array(
							'name'=>'sales_payment_amount', 
							'value'=>$payment_amount, 
							'id'=>'payment_amount', 
							'class'=>'form-control input-sm', 
							'size'=>'8',
							'width'=>'30'
						));?>
						<?php echo form_hidden('payment_amount', $adeudado_amount);?>
					</div>
					<div class='col-xs-3'>
						<?php echo form_dropdown('payment_type_abono', $payment_options, '', array('id'=>'payment_type_abono', 'class'=>'form-control input-sm')); ?>
					</div>
					<div class='col-xs-2'>
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" type="button" id="add_payment_button" style="background: transparent; border: none;">
								<span class="glyphicon glyphicon-plus" style="color: #18bc9c;"></span>
							</button>
						</span>
					</div>  
				</div>
			<?php endif; ?>

			<table class="table table-bordered table-striped table-condensed" id="payment_abonos_table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Fecha Abono</th>
						<th>Monto</th>
						<th>Tipo de Pago</th>
						<th>Reportado por</th>
						<?php if($this->session->userdata('role') == 'admin'): ?>
						<th><span class="glyphicon glyphicon-cog"></span></th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php if(isset($payment_history) && !empty($payment_history)): ?>
						<?php foreach($payment_history as $payment): ?>
							<tr>
								<td><?php echo $payment['payment_id']; ?></td>
								<td><?php echo date('d/m/Y', strtotime($payment['payment_date'])); ?></td>
								<td><?php echo to_currency($payment['payment_amount']); ?></td>
								<td><?php echo $payment['payment_type']; ?></td>
								<td><?php echo $payment['employee_name']; ?></td>
								<?php if($this->session->userdata('role') == 'admin'): ?>
								<td>
									<a href="javascript:void(0);" class="delete-payment" data-payment-id="<?php echo $payment['payment_id']; ?>">
										<span class="glyphicon glyphicon-trash" style="color: #e74c3c;"></span>
									</a>
								</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					<tr id="no_payments_row" class="<?php echo (isset($payment_history) && !empty($payment_history)) ? 'hidden' : ''; ?>">
						<td colspan="<?php echo ($this->session->userdata('role') == 'admin') ? '6' : '5'; ?>" style="text-align: center;">No hay pagos registrados</td>
					</tr>
				</tbody>
			</table>
		<?php endif; ?>

		<div class="form-group form-group-sm">
			<?php echo form_label($this->lang->line('sales_comment'), 'comment', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-8'>
				<?php echo form_textarea(array('name'=>'comment', 'value'=>$sale_info['comment'], 'id'=>'comment', 'class'=>'form-control input-sm'));?>
			</div>
		</div>
	</fieldset>
<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function()
{	
	<?php if(!empty($sale_info['email'])): ?>
		$('#send_invoice').click(function(event) {
			if (confirm("<?php echo $this->lang->line('sales_invoice_confirm') . ' ' . $sale_info['email'] ?>")) {
				$.get("<?php echo site_url($controller_name . '/send_pdf/' . $sale_info['sale_id']); ?>",
					function(response) {
						BootstrapDialog.closeAll();
						$.notify( { message: response.message }, { type: response.success ? 'success' : 'danger'} )
					}, 'json'
				);	
			}
		});
	<?php endif; ?>
	
	<?php $this->load->view('partial/datepicker_locale'); ?>

	var fill_value_customer = function(event, ui) {
		event.preventDefault();
		$("input[name='customer_id']").val(ui.item.value);
		$("input[name='customer_name']").val(ui.item.label);
	};

	$('#customer_name').autocomplete( {
		source: "<?php echo site_url('customers/suggest'); ?>",
		minChars: 0,
		delay: 15, 
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value_customer,
		focus: fill_value_customer
	});

	var fill_value_employee = function(event, ui) {
		event.preventDefault();
		$("input[name='employee_id']").val(ui.item.value);
		$("input[name='employee_name']").val(ui.item.label);
	};

	$('#employee_name').autocomplete( {
		source: "<?php echo site_url('employees/suggest'); ?>",
		minChars: 0,
		delay: 15, 
		cacheLength: 1,
		appendTo: '.modal-content',
		select: fill_value_employee,
		focus: fill_value_employee
	});

	$('button#delete').click(function() {
		dialog_support.hide();
		table_support.do_delete("<?php echo site_url($controller_name); ?>", <?php echo $sale_info['sale_id']; ?>);
	});

	$('button#restore').click(function() {
		dialog_support.hide();
		table_support.do_restore("<?php echo site_url($controller_name); ?>", <?php echo $sale_info['sale_id']; ?>);
	});

	$('#sales_edit_form').validate($.extend( {
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				success: function(response)
				{
					dialog_support.hide();
					table_support.handle_submit("<?php echo site_url($controller_name); ?>", response);

					const params = $.param(table_support.query_params());
					$.get("<?php echo site_url($controller_name); ?>/search?" + params, function(response) {
						$("#payment_summary").html(response.payment_summary);
					}, 'json');
				},
				dataType: 'json'
			});
		},

		errorLabelContainer: '#error_message_box',

		rules:
		{
			invoice_number:
			{
				remote:
				{
					url: "<?php echo site_url($controller_name . '/check_invoice_number')?>",
					type: 'POST',
					data: {
						'sale_id': <?php echo $sale_info['sale_id']; ?>,
						'invoice_number': function() {
							return $('#invoice_number').val();
						}
					}
				}
			}
		},

		messages: 
		{
			invoice_number: "<?php echo $this->lang->line("sales_invoice_number_duplicate"); ?>"
		}
	}, form_support.error));

	// Definir el patrón regex para validar el formato de moneda
	var currencyRegex = /^\d+(\.\d{1,2})?$/;

	// Agregar esta función justo antes del código de add_payment_button
	function forceCloseModal() {
		// 1. Eliminar completamente el modal y el backdrop
		$('.modal').remove();
		$('.modal-backdrop').remove();
		
		// 2. Restaurar el estado del body
		$('body')
			.removeClass('modal-open')
			.css({
				'padding-right': '',
				'overflow': ''
			});
		
		// 3. Forzar cualquier otro cambio en el documento
		$(document).off('focusin.modal'); // Quitar cualquier evento modal
	}

	$('#add_payment_button').click(function() {
		var pAmount = $('#payment_amount').val().trim();
		var pType = $('#payment_type_abono').val();
		
		// Actualizar las validaciones iniciales
		if (pAmount === '') {
			showNotification('Debe ingresar un monto para abonar a esta factura');
			return;
		}

		if (!currencyRegex.test(pAmount)) {
			showNotification('Debe ingresar un abono con el formato correcto, el formato correcto debe ser ##.##');
			return;
		}

		if (pType === '') {
			showNotification('Debe seleccionar un tipo de pago');
			return;
		}

		// Validación del monto adeudado...
		var adeudadoAmount = parseFloat($('input[name="payment_amount"]').val());
		var paymentAmount = parseFloat(pAmount);

		if (paymentAmount > adeudadoAmount.toFixed(2)) {
			showNotification('1El monto del pago no puede ser mayor que el monto adeudado (' + adeudadoAmount.toFixed(2) + ')');
			return;
		}

		// Llamada AJAX
		$.ajax({
			url: '<?php echo site_url("sales/add_payment_to_sale"); ?>',
			type: 'POST',
			dataType: 'json',
			data: {
				sale_id: <?php echo $sale_info['sale_id']; ?>,
				payment_amount: pAmount,
				payment_type: pType
			},
			success: function(response) {
				if (response.success) {
					// Mostrar mensaje de éxito
					showNotification(response.message, 'success');

					// Actualizar el monto adeudado restante
					var newAdeudado = adeudadoAmount - paymentAmount;

					// Si el adeudado llega a 0
					if (newAdeudado <= 0) {
						// Actualizar la tabla primero
						table_support.handle_submit('<?php echo site_url($controller_name); ?>', response);
						
						// Usar nuestra función personalizada para cerrar con fuerza el modal
						forceCloseModal();
						
						// Actualizar el resumen de pagos
						const params = $.param(table_support.query_params());
						$.get('<?php echo site_url($controller_name); ?>/search?' + params, function(response) {
							$('#payment_summary').html(response.payment_summary);
						}, 'json');
					} else {
						// Pago parcial - Aquí está ocurriendo el problema con el overlay
						
						// Primero ocultamos el modal y el overlay actual
						$('.modal').modal('hide');
						$('.modal-backdrop').remove();
						$('body').removeClass('modal-open').css('padding-right', '');
						
						// Actualizar la tabla principal primero (igual que en el caso de pago completo)
						table_support.handle_submit('<?php echo site_url($controller_name); ?>', response);
						
						// Breve retraso para permitir que el DOM se actualice
						setTimeout(function() {
							// Actualizar el resumen de pagos en la vista principal
							const params = $.param(table_support.query_params());
							$.get('<?php echo site_url($controller_name); ?>/search?' + params, function(response) {
								$('#payment_summary').html(response.payment_summary);
								
								// Luego recargamos el contenido en un nuevo modal
								dialog_support.fetch('<?php echo site_url("sales/view"); ?>/' + <?php echo $sale_info['sale_id']; ?>);
							}, 'json');
						}, 300);
					}
				} else {
					// Mostrar mensaje de error
					showNotification(response.message);
				}
			},
			error: function() {
				// Aplicar la misma configuración para errores de AJAX
				showNotification('Error al procesar el pago');
			}
		});
	});

	// Agregar esta función al inicio del script (después de $(document).ready)
	function showNotification(message, type) {
		$.notify(message, {
			type: type || 'danger',
			placement: {
				from: 'top',
				align: 'center'
			},
			z_index: 9999,
			animate: {
				enter: 'animated fadeInDown',
				exit: 'animated fadeOutUp'
			},
			delay: 3000,
			offset: {
				y: 80
			},
			template: 
				'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" style="max-width: 400px; text-align: center;">' +
				'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">&times;</button>' +
				'<span data-notify="message">{2}</span>' +
				'</div>'
		});
	}

	// Manejador para eliminar pagos
	$('#payment_abonos_table').on('click', '.delete-payment', function(e) {
		e.preventDefault();
		e.stopPropagation();
		
		var paymentId = $(this).data('payment-id');
		var row = $(this).closest('tr');
		
		// Mostrar diálogo de confirmación
		if(confirm('¿Está seguro que desea eliminar este pago?')) {
			$.ajax({
				url: '<?php echo site_url("sales/delete_payment_history"); ?>/' + paymentId,
				type: 'POST',
				dataType: 'json',
				success: function(response) {
					if(response.success) {
						// Eliminar la fila de la tabla
						row.remove();
						
						// Verificar si quedan filas en la tabla
						if($('#payment_abonos_table tbody tr').length <= 1) { // Solo queda la fila de "No hay pagos"
							$('#no_payments_row').removeClass('hidden');
						}
						
						// Mostrar mensaje de éxito
						showNotification(response.message, 'success');
						
						// Recargar la página para actualizar los montos
						setTimeout(function() {
							window.location.reload();
						}, 1500);
					} else {
						// Mostrar mensaje de error
						showNotification(response.message);
					}
				},
				error: function() {
					showNotification('Error al procesar la solicitud');
				}
			});
		}
	});

	function formatDate(dateString) {
		var date = new Date(dateString);
		var day = date.getDate().toString().padStart(2, '0');
		var month = (date.getMonth() + 1).toString().padStart(2, '0');
		var year = date.getFullYear();
		return day + '/' + month + '/' + year;
	}

});
</script>
