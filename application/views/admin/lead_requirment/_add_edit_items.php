<div class="panel-body mtop10">
   <div class="row">
      <div class="col-md-4">
         <div class="form-group no-mbot items-wrapper select-placeholder">
            <select name="item_select" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?>" data-width="100%" id="item_select" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
               <option value=""></option>
               <?php foreach($items as $group_id=>$_items){ ?>
               <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                  <?php foreach($_items as $item){ ?>
                  <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>">(<?php echo _format_number($item['rate']); ; ?>) <?php echo $item['description']; ?></option>
                  <?php } ?>
               </optgroup>
               <?php } ?>
              
            </select>
         </div>
      </div>
     
   </div>
   <div class="table-responsive s_table mtop10">
      <table class="table estimate-items-table items table-main-estimate-edit">
         <thead>
            <tr>
               <th></th>
               <th width="20%" align="left"><i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-title="<?php echo _l('item_description_new_lines_notice'); ?>"></i> <?php echo _l('estimate_table_item_heading'); ?></th>
               <th width="25%" align="left"><?php echo _l('estimate_table_item_description'); ?></th>
               <?php
                  $custom_fields = get_custom_fields('items');
                  foreach($custom_fields as $cf){
                   echo '<th width="15%" align="left" class="custom_field">' . $cf['name'] . '</th>';
                  }

                  $qty_heading = _l('estimate_table_quantity_heading');
                  if(isset($estimate) && $estimate->show_quantity_as == 2){
                  $qty_heading = _l('estimate_table_hours_heading');
                  } else if(isset($estimate) && $estimate->show_quantity_as == 3){
                  $qty_heading = _l('estimate_table_quantity_heading') . '/' . _l('estimate_table_hours_heading');
                  }
                  ?>
               <th width="10%" class="qty" align="right"><?php echo $qty_heading; ?></th>
               <th width="15%" align="right"><?php echo 'Required Price'; ?></th>
               <th width="20%" align="right"><?php echo 'Document Required '; ?></th>
               <th width="10%" align="right"><?php echo 'Proposed Item'; ?></th>
               <th width="10%" align="right"><?php echo 'Proposed Item Price'; ?></th>
               <th align="center"><i class="fa fa-cog"></i></th>
            </tr>
         </thead>
         <tbody>
            <tr class="main">
               <td></td>
               <td>
                  <textarea name="description" rows="4" class="form-control" placeholder="<?php echo _l('item_description_placeholder'); ?>"></textarea>
               </td>
               <td>
                  <textarea name="long_description" rows="4" class="form-control" placeholder="<?php echo _l('item_long_description_placeholder'); ?>"></textarea>
               </td>
               
               <td>
                  <input type="number" name="quantity" min="0" value="1" class="form-control" placeholder="<?php echo _l('item_quantity_placeholder'); ?>">
                 
               </td>
               <td>
                  <input type="number" name="rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
               </td>
               <td>
                    <select class="selectpicker display-block tax main-tax" data-width="100%" name="document" data-none-selected-text="Select Document">
						<option value="TDS">TDS</option>
						<option value="LM79">LM79</option>
						<option value="LM80">LM80</option>
                    </select>                     
               </td>
			   <td>
			   <select name="proposed_item" class="selectpicker no-margin<?php if($ajaxItems == true){echo ' ajax-search';} ?>" data-width="100%" id="proposed_item" data-none-selected-text="<?php echo _l('add_item'); ?>" data-live-search="true">
               <option value=""></option>
               <?php foreach($items as $group_id=>$_items){ ?>
               <optgroup data-group-id="<?php echo $group_id; ?>" label="<?php echo $_items[0]['group_name']; ?>">
                  <?php foreach($_items as $item){ ?>
                  <option value="<?php echo $item['id']; ?>" data-subtext="<?php echo strip_tags(mb_substr($item['long_description'],0,200)).'...'; ?>">(<?php echo _format_number($item['rate']); ; ?>) <?php echo $item['description']; ?></option>
                  <?php } ?>
               </optgroup>
               <?php } ?>
              
            </select>
                 
               </td>
			   <td>
                  <input type="number" name="rate" class="form-control" placeholder="<?php echo _l('item_rate_placeholder'); ?>">
               </td>
               <td></td>
               <td>
                  <?php
                     $new_item = 'undefined';
                     if(isset($estimate)){
                       $new_item = true;
                     }
                     ?>
                  <button type="button" onclick="add_item_to_table('undefined','undefined',<?php echo $new_item; ?>); return false;" class="btn pull-right btn-info"><i class="fa fa-check"></i></button>
               </td>
            </tr>
            </tbody>
      </table>
   </div>
   
   <div id="removed-items"></div>
</div>
