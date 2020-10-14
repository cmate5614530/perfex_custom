<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="list_import_goods" class="hide">
   <div class="row">
      <div class="col-md-4">
         <div class="form-group">
            <label for="products_services"><?php echo _l('products_services'); ?></label>
            <select name="products_services" class="selectpicker" multiple data-width="100%" data-none-selected-text="<?php echo _l('invoice_status_report_all'); ?>">
               <?php foreach($items as $item){ ?>
                <option value="<?php echo html_entity_decode($item['id']); ?>"><?php echo html_entity_decode($item['label']); ?></option>
            <?php } ?>
         </select>
      </div>
   </div>
   <div class="clearfix"></div>
</div>
<table class="table table-import-goods-report scroll-responsive">
   <thead>
      <tr>
         <th><?php echo _l('product_code'); ?></th>
         <th><?php echo _l('product_name'); ?></th>
         <th><?php echo _l('pur_order_number'); ?></th>
         <th><?php echo _l('subtotal'); ?></th>
      </tr>
   </thead>
   <tbody></tbody>
   <tfoot>
      <tr>
         <td></td>
         <td></td>
         <td></td>
         <td class="total"></td>
      </tr>
   </tfoot>
</table>
</div>
