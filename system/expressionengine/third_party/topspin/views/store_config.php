<p class="notice"><?=$msg;?></p>
<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin'.AMP.'method=update_store'.($store_id > 0 ? AMP.'num='.$store_id : ''))?>
<?php 
	$this->table->set_heading(
                '',''
        );
 	 
    $this->table->add_row(lang('store_name'),form_input('store_name', $store_name));
    $this->table->add_row(lang('store_uri'),form_input('store_uri', $store_uri));
	$this->table->add_row(lang('choose_template'),form_dropdown('template', $store_template_options, $template));    
	$this->table->add_row(lang('rows_per_page'),form_dropdown('rows_pp', $row_options, $rows_pp)); 
	
	$offer_type_checkboxes ='';
	foreach($offer_type_options as $ot) {
		$offer_type_checkboxes .= form_checkbox(array('name' => 'offer_types[]', 'value' => $ot, 'checked' => (array_search($ot,$offer_types) !== false ? 1 : 0), 'id' => 'offer_types_'.$ot)).NBS.'<label for="'.$ot.'">'.lang($ot).'</label>'.BR;
	}
	$this->table->add_row(lang('offer_types'),$offer_type_checkboxes);
	
	$tag_checkboxes = '';
	foreach($tag_list as $t) {
		$tag_checkboxes .= form_checkbox(array('name' => 'tags[]', 'value' => $t, 'checked' => (array_search($t,$tags) !== false ? 1 : 0), 'id' => 'tags_'.$t)).'<label for="tags_'.$t.'">'.ucwords(str_replace('_',' ',$t)).'</label>'.BR;
	}
	$this->table->add_row(lang('tags'),$tag_checkboxes.BR.lang('tags_note'));

	$this->table->add_row(lang('create_detail_pages'),form_checkbox('detail_pages', '1', ($detail_pages == '1' ? 1 : 0))); 

	echo $this->table->generate();
?>
<div><?=form_submit(array('name' => 'submit', 'value' => lang($store_id > 0 ? 'save_store' : 'create_new_store'), 'class' => 'submit'));?></div>
<?=form_close()?>
<?php
if($store_id > 0) {
?>
<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin'.AMP.'method=delete_store'.($store_id > 0 ? AMP.'num='.$store_id : ''), array('id'=>'delete_form'))?>
<div style="margin-top: 20px">
	<?=form_submit(array('name' => 'submit', 'value' => lang('delete_store'), 'id' => "delete_store_btn", 'class'=> "submit", 'onclick' => "return confirm_delete();"));?>
</div>
<?=form_close()?>
<script type="text/javascript"> 
   <!--
   $(function() {
/*   	$('#delete_store_btn').click(function(e) {
   		e.preventDefault();
   		if(confirm("Delete <?=$store_name?>?")) {
   			console.log($(this).closest('form'));
   			console.log($('#delete_form').submit());
   		}
   	});*/
   });

	function confirm_delete() {
		return confirm("Delete <?=$store_name?>?");
	}
-->
</script>
<?php
}
?>