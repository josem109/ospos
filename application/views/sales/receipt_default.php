
<div id="receipt_wrapper" style="font-size:<?php echo $this->config->item('receipt_font_size');?>px">
	<div id="receipt_header">
		<?php
		if($this->config->item('company_logo') != '')
		{
		?>
			<div id="company_name">
				<img id="image" src="<?php echo base_url('uploads/' . $this->config->item('company_logo')); ?>" alt="company_logo" />
			</div>
		<?php
		}
		?>

		<?php
		if($this->config->item('receipt_show_company_name'))
		{
		?>
			<div id="company_name"><?php echo $this->config->item('company'); ?></div>
		<?php
		}
		?>

		<div id="company_address"><?php echo nl2br($this->config->item('address')); ?></div>
		<div id="company_phone"><?php echo $this->config->item('phone'); ?></div>
		<div id="sale_receipt"><?php echo $this->lang->line('sales_receipt'); ?></div>
		<div id="sale_time"><?php echo $transaction_time ?></div>
	</div>

	<div id="receipt_general_info">
		<?php
		if(isset($customer))
		{
		?>
			<div id="customer"><?php echo $this->lang->line('customers_customer').": ".$customer; ?></div>
		<?php
		}
		?>

		<div id="sale_id"><?php echo $this->lang->line('sales_id').": ".$sale_id; ?></div>

		<?php
		if(!empty($invoice_number))
		{
		?>
			<div id="invoice_number"><?php echo $this->lang->line('sales_invoice_number').": ".$invoice_number; ?></div>
		<?php
		}
		?>

		<div id="employee"><?php echo $this->lang->line('employees_employee').": ".$employee; ?></div>
	</div>

	<table id="receipt_items">
		<tr>
			<th style="width:40%;"><?php echo $this->lang->line('sales_description_abbrv'); ?></th>
			<th style="width:20%;"><?php echo $this->lang->line('sales_price'); ?></th>
			<th style="width:10%;"><?php echo $this->lang->line('sales_quantity_receipt'); ?></th>
			<th style="width:30%;" class="total-value"><?php echo $this->lang->line('sales_total'); ?></th>
			<?php
			if($this->config->item('receipt_show_tax_ind'))
			{
			?>
				<th style="width:20%;"></th>
			<?php
			}
			?>
		</tr>
		<?php
		foreach($cart as $line=>$item)
		{
			if($item['print_option'] == PRINT_YES)
			{
				$currency_rate = floatval($this->config->item('currency_rate'));
				$currency_rate_alternative = floatval($this->config->item('currency_rate_alternative'));
			?>
				<tr>
					<td><?php echo ucfirst($item['name'] . ' ' . $item['attribute_values']); ?></td>
					<td><?php echo to_currency_bcv($item['price'] * $currency_rate); ?></td>
					<td><?php echo to_quantity_decimals($item['quantity']); ?></td>
					<td class="total-value">
					<?php
						 $calculated_total = $item['price'] * $item['quantity'] * $currency_rate;
						 $prediscount_subtotal_calculated = $prediscount_subtotal_calculated + $calculated_total;
						 if ($item["discount"] == 0) 
						 {
							$display_value = $calculated_total;
						 }else {
							$display_value = $this->config->item('receipt_show_total_discount') ? $calculated_total : round($item['discounted_total'] * $currency_rate,2);
						 }
						 
						 echo to_currency_bcv($display_value);
					 ?>
					 </td>
					<?php
					if($this->config->item('receipt_show_tax_ind'))
					{
					?>
						<td><?php echo $item['taxed_flag'] ?></td>
					<?php
					}
					?>
				</tr>
				<tr>
					<?php
					if($this->config->item('receipt_show_description'))
					{
					?>
						<td colspan="2"><?php echo $item['description']; ?></td>
					<?php
					}

					if($this->config->item('receipt_show_serialnumber'))
					{
					?>
						<td><?php echo $item['serialnumber']; ?></td>
					<?php
					}
					?>
				</tr>
				<?php
				if($item['discount'] > 0)
				{
				?>
					<tr>
						<?php
						if($item['discount_type'] == FIXED)
						{
						?>
							<td colspan="3" class="discount"><?php echo to_currency_bcv($item['discount'] * $currency_rate) . " " . $this->lang->line("sales_discount") ?></td>
						<?php
						}
						elseif($item['discount_type'] == PERCENT)
						{
						?>
							<td colspan="3" class="discount"><?php echo to_decimals($item['discount'] * $currency_rate) . " " . $this->lang->line("sales_discount_included") ?></td>
						<?php
						}	
						?>
						<td class="total-value"><?php echo to_currency_bcv($item['discounted_total'] * $currency_rate); ?></td>
					</tr>
				<?php
				}
			}
		}
		?>

		<?php
		if($this->config->item('receipt_show_total_discount') && $discount > 0)
		{
		?>
			<tr>
				<td colspan="3" style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('sales_sub_total'); ?></td>
				<td style='text-align:left;border-top:2px solid #000000;'>
					<?php
				 		//echo to_currency_bcv($prediscount_subtotal * $currency_rate);
						 echo to_currency_bcv($prediscount_subtotal_calculated);
				 	?>
				</td>
			</tr>
			<tr>
				<td colspan="3" style='text-align:right;'><?php echo $this->lang->line('sales_customer_discount'); ?>:</td>
				<td class="total-value"><?php echo to_currency_bcv($discount * $currency_rate * -1); ?></td>
			</tr>
		<?php
		}
		?>

		<?php
		if($this->config->item('receipt_show_taxes'))
		{
		?>
			<tr>
				<td colspan="3" style='text-align:right;border-top:2px solid #000000;'><?php echo $this->lang->line('sales_sub_total'); ?></td>
				<td style='text-align:right;border-top:2px solid #000000;'><?php echo to_currency_bcv($total * $currency_rate); ?></td>
			</tr>
			<?php
			foreach($taxes as $tax_group_index=>$tax)
			{
			?>
				<tr>
					<td colspan="3" style='text-align:right;'><?php echo (float)$tax['tax_rate'] . '% ' . $tax['tax_group']; ?>:</td>
					<td class="total-value"><?php echo to_currency_tax($tax['sale_tax_amount']); ?></td>
				</tr>
			<?php
			}
			?>
		<?php
		}
		?>

		<tr>
		</tr>

		<?php $border = (!$this->config->item('receipt_show_taxes') && !($this->config->item('receipt_show_total_discount') && $discount > 0)); ?>
		<tr>
			<td colspan="3" style="text-align:right;<?php echo $border? 'border-top: 2px solid black;' :''; ?>"><?php echo $this->lang->line('sales_total'); ?></td>
			<!--<td style="text-align:left;
				<?php 
					//echo $border? 'border-top: 2px solid black;' :''; 
				?>">
				<?php 
					//echo to_currency_bcv($total * $currency_rate); 
				?></td>-->
			<td style="text-align:left;<?php echo $border? 'border-top: 2px solid black;' :''; ?>"><?php echo to_currency_bcv($total2); ?></td>
		</tr>

		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<!--<tr>
			<td colspan="3" style="text-align:right;"><?php echo "Total Pago" ; ?> </td>
			<td class="total-value">
				<?php
					/* if(isset($payments_total2))
					{
						$payments_total2 = $payments_total2;	
					}
					else
					{
						if($payments_cover_total == true)
						{
							$payments_total2 = $total2;
						}
						else
						{
							$payments_total2 = round($payments_total * $currency_rate,2);
						}
					}
					echo to_currency_bcv( $payments_total2 * -1); */
			  	?>
			</td>
		</tr>-->
		<?php
		$only_sale_check = FALSE;
		$show_giftcard_remainder = FALSE;

		foreach($payments as $payment_id=>$payment)
		{
			$only_sale_check |= $payment['payment_type'] == $this->lang->line('sales_check');
			$splitpayment = explode(':', $payment['payment_type']);
			$show_giftcard_remainder |= $splitpayment[0] == $this->lang->line('sales_giftcard');
		?>
			<tr>
				<td colspan="3" style="text-align:right;">
					<?php echo $splitpayment[0]; ?> 
				</td>
				<td class="total-value">
					<?php
						 //pago total
						 //echo to_currency_bcv( $payments_total2 * -1 );
						 echo to_currency_bcv( $payment['payment_amount'] * $currency_rate * -1 );
					?>
					
				</td>
			</tr>-->
		<?php
		}
		?>

		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>

		<?php
		if(isset($cur_giftcard_value) && $show_giftcard_remainder)
		{
		?>
			<tr>
				<td colspan="3" style="text-align:right;"><?php echo $this->lang->line('sales_giftcard_balance'); ?></td>
				<td class="total-value"><?php echo to_currency($cur_giftcard_value); ?></td>
			</tr>
		<?php
		}
		?>
		<tr>
			<td colspan="3" style="text-align:right;"> <?php echo $this->lang->line($amount_change >= 0 ? ($only_sale_check ? 'sales_check_balance' : 'sales_change_due') : 'sales_amount_due') ; ?> </td>
			<td class="total-value">
				<?php
					 //echo to_currency_bcv($amount_change * $currency_rate);
					 echo to_currency_bcv($amount_change * $currency_rate);
				?>
			</td>
		</tr>
	</table>

	<!-- <div id="sale_return_policy">
		//?php echo nl2br($this->config->item('return_policy')); ?>
	</div> -->

	<!-- <div id="barcode">
		<img src='data:image/png;base64,<?php echo $barcode; ?>' /><br>
		<?php echo $sale_id; ?>
	</div> -->
</div>
