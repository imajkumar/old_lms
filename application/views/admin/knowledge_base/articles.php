<?php init_head();
$has_permission_edit = has_permission('knowledge_base','','edit');
$has_permission_create = has_permission('knowledge_base','','create');
?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">

       <div class="panel_s mtop5">

         <div class="panel-body">
           <div class="_buttons">
             <?php if($has_permission_create){ ?>
             <a href="<?php echo admin_url('knowledge_base/article'); ?>" class="btn btn-default btn-icon pull-left display-block"><?php echo _l('kb_article_new_article'); ?></a>
             <?php } ?>
             
             <a href="#" class="btn btn-default toggle-articles-list btn-with-tooltip hide" data-title="<?php echo _l('switch_to_list_view'); ?>" onclick="initKnowledgeBaseTableArticles(); return false;"><i class="fa fa-th-list"></i></a>
             <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data hide" data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-filter" aria-hidden="true"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
               <li class="active">
     <a href="#" data-cview="all" onclick="dt_custom_view('','.table-articles',''); return false;"><?php echo _l('view_articles_list_all'); ?></a>
              </li>
              <?php foreach($groups as $group){ ?>
              <li><a href="#" data-cview="kb_group_<?php echo $group['groupid']; ?>" onclick="dt_custom_view('kb_group_<?php echo $group['groupid']; ?>','.table-articles','kb_group_<?php echo $group['groupid']; ?>'); return false;"><?php echo $group['name']; ?></a></li>
              <?php } ?>
            </ul>
          </div>
          <div class="_hidden_inputs _filters">
            <?php foreach($groups as $group){
             echo form_hidden('kb_group_'.$group['groupid']);
           } ?>
         </div>
       </div>
       <hr class="hr-panel-heading"/>
       <div class="row">
         <div class="tab-content">
           <div role="tabpanel" class="tab-pane active kb-kan-ban kan-ban-tab" id="kan-ban">
             <div class="container-fluid ">
              
              
                  <div class="container">
				   <table class="table table-articles dataTable center"  id="DataTables_Table">
					<thead>
						<tr>
							<th  style="text-align: center;">Title</th>
							<th  style="text-align: center;">Document</th>
							<th  style="text-align: center;">Description</th>
							<th  style="text-align: center;">Date</th>
							<th  style="text-align: center;">Action</th>
						</tr>
					</thead>
					
					<tbody>
						<?php 
						$this->load->helper('download');
						foreach($article_list as $article_data){
							?>
							<tr>
								<td><?php echo $article_data['subject']; ?></td>
								<td><a href="<?php echo base_url('uploads/knowledge_base/').$article_data['image']; ?>"><?php echo $article_data['image']; ?></a></td>
								<td><?php echo $article_data['description']; ?></td>
								<td><?php echo $article_data['datecreated']; ?></td>
								<td>
									<span class="dtr-data"><a target="_blank" class="btn btn-default btn-icon" href="<?php echo base_url('uploads/knowledge_base/').$article_data['image']; ?>" ><i class="fa fa-download"></i></a>
									</span>
									<?php if(has_permission('knowledge_base','','delete')){ ?>
                                        <a href="<?php echo admin_url('knowledge_base/delete_article/'.$article_data['articleid']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                        <?php } ?>
										<?php if(has_permission('knowledge_base','','delete')){ ?>
                                        <a href="<?php echo admin_url('knowledge_base/update/'.$article_data['articleid']); ?>" class="btn btn-danger btn-icon_edit hidden"><i class="fa fa-edit"></i></a>
                                        <?php } ?>
								</td>
							</tr>			
											
						<?php	
						}
						?>
					</tbody>
					
					</table>
				 </div>
               
            
            
           
          </div>
          <div role="tabpanel" class="tab-pane" id="list_tab">
            <div class="col-md-12">
              <?php render_datatable(
                array(
                  _l('kb_dt_article_name'),
                  _l('kb_dt_group_name'),
                  _l('options'),
                  ),'articles'); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
 $(function(){

   fix_kanban_height(290,360);
	
	
   $(".groups").sortable({
     connectWith: ".article-group",
     helper: 'clone',
     appendTo: '#kan-ban',
     placeholder: "ui-state-highlight-kan-ban-kb",
     revert: true,
     scroll: true,
     scrollSensitivity: 50,
     scrollSpeed: 70,
     start: function(event, ui) {
      $('body').css('overflow','hidden');
      },
     stop: function(event, ui) {
       $('body').removeAttr('style');
      },
      update: function(event, ui) {
       if (this === ui.item.parent()[0]) {
         var articles = $(ui.item).parents('.article-group').find('li');
         i = 1;
         var order = [];
         $.each(articles, function() {
           i++;
           order.push([$(this).data('article-id'), i]);
         });
         setTimeout(function() {
           $.post(admin_url + 'knowledge_base/update_kan_ban', {
             order: order,
             groupid: $(ui.item.parent()[0]).data('group-id')
           });
         }, 100);
       }
     }
   }).disableSelection();

   $('.groups').sortable({
     cancel: '.sortable-disabled'
   });

   setTimeout(function(){
     $('.kb-kan-ban').removeClass('show');
   },200);

   $(".container-fluid").sortable({
     helper: 'clone',
     item: '.kan-ban-col',
     cancel: '.sortable-disabled',
     update: function(event, ui) {
       var order = [];
       var status = $('.kan-ban-col');
       var i = 0;
       $.each(status, function() {
         order.push([$(this).data('col-group-id'), i]);
         i++;
       });
       var data = {}
       data.order = order;
       $.post(admin_url + 'knowledge_base/update_groups_order', data);
     }
   });
       // Status color change
       $('body').on('click', '.kb-kan-ban .cpicker', function() {
         var color = $(this).data('color');
         var group_id = $(this).parents('.panel-heading-bg').data('group-id');
         $.post(admin_url + 'knowledge_base/change_group_color', {
           color: color,
           group_id: group_id
         });
       });
       $('.toggle-articles-list').on('click', function() {
         var list_tab = $('#list_tab');
         if (list_tab.hasClass('toggled')) {
           list_tab.css('display', 'none').removeClass('toggled');
           $('.kan-ban-tab').css('display', 'block');
           $('input[name="search[]"]').removeClass('hide');
         } else {
           list_tab.css('display', 'block').addClass('toggled');
           $('.kan-ban-tab').css('display', 'none');
           $('input[name="search[]"]').addClass('hide');
         }
       });
     });
/*  function initKnowledgeBaseTableArticles(){
   var KB_Articles_ServerParams = {};
   $.each($('._hidden_inputs._filters input'),function(){
     KB_Articles_ServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
   });
   $('._filter_data').toggleClass('hide');
   initDataTable('.table-articles', window.location.href, [2], [2], KB_Articles_ServerParams);
 } */
 
 
 
 
</script>
</body>
</html>
