<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-5">
				<div class="panel_s">
					<div class="col-md-12 no-padding">
						<div class="panel_s">
							<?php echo form_open($this->uri->uri_string()); ?>
							<div class="panel-body">
								<!-- <h4 class="no-margin"><?php echo _l('payment_edit_for_invoice'); ?> <a href="<?php echo admin_url('invoices/list_invoices/'.$receipt->invoiceid); ?>"><?php echo format_invoice_number($receipt->invoice->id); ?></a></h4> -->
								<hr class="hr-panel-heading" />
								<?php $value = (isset($receipt) ? $receipt->list_commission_id : '') ?>
								<?php echo render_select('list_commission[]', $list_commission, array('id','commission_info', 'amount'), 'commission', $value, array('multiple' => true, 'data-actions-box' => true), array(), '', '', false);
								?>
								<?php $value = (isset($receipt) ? $receipt->amount : '') ?>
								<?php echo render_input('amount','commission_payment_amount',$value,'number', array('readonly' => true)); ?>
								<?php $value = (isset($receipt) ? $receipt->date : '') ?>
								<?php echo render_date_input('date','payment_edit_date',_d($value)); ?>
								<?php $value = (isset($receipt) ? $receipt->paymentmode : '') ?>
								<?php echo render_select('paymentmode',$payment_modes,array('id','name'),'payment_mode',$value); ?>
								<i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('payment_method_info'); ?>"></i>
								<?php $value = (isset($receipt) ? $receipt->paymentmethod : '') ?>
								<?php echo render_input('paymentmethod','payment_method',$value); ?>
								<?php $value = (isset($receipt) ? $receipt->transactionid : '') ?>
								<?php echo render_input('transactionid','payment_transaction_id',$value); ?>
								<?php $value = (isset($receipt) ? $receipt->note : '') ?>
								<?php echo render_textarea('note','note',$value,array('rows'=>7)); ?>
								<div class="btn-bottom-toolbar text-right">
									<button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
								</div>
							</div>
							<?php echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
			<?php if(isset($receipt)){ ?>
			<div class="col-md-7">
				<div class="panel_s">
					<div class="panel-body">
						<h4 class="pull-left "><?php echo _l('payment_view_heading'); ?></h4>
						<div class="pull-right">
							<div class="btn-group">
								<a href="#" data-toggle="modal" data-target="#receipt_send_to_salesperson"
								class="payment-send-to-client btn-with-tooltip btn btn-default">
									<i class="fa fa-envelope"></i></span>
								</a>
								<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="fa fa-file-pdf-o"></i>
									<?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span>
								</a>
								<ul class="dropdown-menu dropdown-menu-right">
									<li class="hidden-xs">
										<a href="<?php echo admin_url('commission/pdf/'.$receipt->id.'?output_type=I'); ?>">
											<?php echo _l('view_pdf'); ?>
										</a>
									</li>
									<li class="hidden-xs">
										<a href="<?php echo admin_url('commission/pdf/'.$receipt->id.'?output_type=I'); ?>" target="_blank">
											<?php echo _l('view_pdf_in_new_window'); ?>
										</a>
									</li>
									<li>
										<a href="<?php echo admin_url('commission/pdf/'.$receipt->id); ?>">
											<?php echo _l('download'); ?>
										</a>
									</li>
									<li>
										<a href="<?php echo admin_url('commission/pdf/'.$receipt->id.'?print=true'); ?>" target="_blank">
											<?php echo _l('print'); ?>
										</a>
									</li>
								</ul>
							</div>
							<?php if(has_permission('commission_receipt','','delete')){ ?>
								<a href="<?php echo admin_url('commission/delete_receipt/'.$receipt->id); ?>" class="btn btn-danger _delete">
									<i class="fa fa-remove"></i>
								</a>
							<?php } ?>
						</div>
						<div class="clearfix"></div>
						<hr class="hr-panel-heading" />
						<div class="row">
							<div class="col-md-6 col-sm-6">
								<address>
									<?php echo format_organization_info(); ?>
								</address>
							</div>
							<div class="col-sm-6 text-right">
								<address>
									<span class="bold">
										<?php echo get_staff_full_name($receipt->addedfrom); ?>
									</address>
								</div>
							</div>
							<div class="col-md-12 text-center">
								<h3 class="text-uppercase"><?php echo _l('commission_payment_receipt'); ?></h3>
							</div>
							<div class="col-md-12 mtop30">
								<div class="row">
									<div class="col-md-6">
										<p><?php echo _l('payment_date'); ?> <span class="pull-right bold"><?php echo _d($receipt->date); ?></span></p>
										<hr />
										<p><?php echo _l('payment_view_mode'); ?>
										<span class="pull-right bold">
											<?php echo html_entity_decode($receipt->paymentmode_name); ?>
											<?php if(!empty($receipt->paymentmethod)){
												echo ' - ' . html_entity_decode($receipt->paymentmethod);
											}
											?>
										</span></p>
										<?php if(!empty($receipt->transactionid)) { ?>
											<hr />
											<p><?php echo _l('payment_transaction_id'); ?>: <span class="pull-right bold"><?php echo html_entity_decode($receipt->transactionid); ?></span></p>
										<?php } ?>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-6">
										<div class="payment-preview-wrapper">
											<?php echo _l('payment_total_amount'); ?><br />
											<?php echo app_format_money($receipt->amount, $currency->name); ?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12 mtop30">
								<h4><?php echo _l('payment_for_string'); ?></h4>
								<div class="table-responsive">
									<table class="table table-borderd table-hover">
										<thead>
											<tr>
												<th><?php echo _l('invoice_dt_table_heading_number'); ?></th>
										        <th><?php echo _l('date_sold'); ?></th>
										        <th><?php echo _l('client'); ?></th>
										        <th><?php echo _l('sale_agent_string'); ?></th>
										        <th><?php echo _l('sale_amount'); ?></th>
										        <th><?php echo _l('commission'); ?></th>
											</tr>
											</thead>
											<tbody>
												<?php foreach ($receipt->list_commission as $key => $value) {
												 ?>
												<tr>
													<td><?php echo format_invoice_number($value['invoice_id']); ?></td>
													<td><?php echo _d($value['date']); ?></td>
													<td><?php echo html_entity_decode($value['company']); ?></td>
													<td><?php echo html_entity_decode($value['sale_name']); ?></td>
													<td><?php echo app_format_money($value['total'], $currency->name); ?></td>
													<td><?php echo app_format_money($value['amount'], $currency->name); ?></td>
													
												</tr>
												<?php } ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="btn-bottom-pusher"></div>
				</div>
				<?php $this->load->view('commission/receipts/send_to_salesperson'); ?>
			</div>
			<?php } ?>
			<?php init_tail(); ?>
			
		</body>
		</html>
		<?php require 'modules/commission/assets/js/receipt_js.php'; ?>
