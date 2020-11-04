<?php init_head(); ?>
<div id="wrapper">
 <div class="content">
  <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'article-form')); ?>
  <div class="row">
   <div class="col-md-8 col-md-offset-2">
    <div class="panel_s">
     <div class="panel-body">
      <h4 class="no-margin">
       <?php echo $title; ?>
       <?php if(isset($article)){ ?>
       <br />
       <small>
        <?php if($article->staff_article == 1){ ?>
        <a href="<?php echo admin_url('knowledge_base/view/'.$article->slug); ?>" target="_blank"><?php echo admin_url('knowledge_base/view/'.$article->slug); ?></a>
        <?php } else { ?>
        <a href="<?php echo site_url('clients/knowledge_base/'.$article->slug); ?>" target="_blank"><?php echo site_url('clients/knowledge_base/'.$article->slug); ?></a>
        <?php } ?>
      </small>
      <?php } ?>
    </h4>
    <?php if(isset($article)){ ?>
    <p>
      <small>
       <?php echo _l('article_total_views'); ?>: <?php echo total_rows('tblviewstracking',array('rel_type'=>'kb_article','rel_id'=>$article->articleid)); ?>
     </small>
     <?php if(has_permission('knowledge_base','','edit')){ ?>
     <a href="<?php echo admin_url('knowledge_base/add_knowledge'); ?>" class="btn btn-success pull-right"><?php echo 'add data'; ?></a>
     <?php } ?>
     <?php if(has_permission('knowledge_base','','delete')){ ?>
     <a href="<?php echo admin_url('knowledge_base/delete_article/'.$article->articleid); ?>" class="btn btn-danger _delete pull-right mright5"><?php echo _l('delete'); ?></a>
     <?php } ?>
     <div class="clearfix"></div>
   </p>
   <?php } ?>
   <hr class="hr-panel-heading" />

   <div class="clearfix"></div>
   <?php $value = (isset($article) ? $article->subject : ''); ?>
   <?php $attrs = (isset($article) ? array() : array('autofocus'=>true)); ?>
   <?php echo render_input('subject','Title',$value,'text',$attrs); ?>
  
  <div class="checkbox checkbox-primary hide">
   <input type="checkbox" id="staff_article" name="staff_article" <?php if(isset($article) && $article->staff_article == 1){echo 'checked';} ?>>
   <label for="staff_article"><?php echo _l('internal_article'); ?></label>
 </div>
 
 <?php $image = '';if(isset($article)) {$image=$article->image;} ?>
   <?php $attrs = (isset($article) ? array() : array('autofocus'=>true)); ?>
   
   <?php echo render_input('profile_image','Document',$attrs,'file',$image); ?>
 <p class="bold"><?php echo _l('kb_article_description'); ?></p>
 <?php $contents = ''; if(isset($article)){$contents = $article->description;} ?>
 <?php echo render_textarea('description','',$contents,array(),array(),'','tinymce'); ?>
<?php if((has_permission('knowledge_base','','create') && !isset($article)) || has_permission('knowledge_base','','edit') && isset($article)){ ?>

  <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>

<?php } ?>
</div>
</div>

</div>

</div>
<?php echo form_close(); ?>
</div>

<?php init_tail(); ?>
<script>
  $(function(){
    _validate_form($('#article-form'),{subject:'required',articlegroup:'required'});
  });
</script>
</body>
</html>
