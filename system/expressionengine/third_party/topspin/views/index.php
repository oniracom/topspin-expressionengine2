<p class="notice"><?=$msg;?></p>
<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin'.AMP.'method=update_general_config')?>
<?php 
	$this->table->set_heading(
                '',''
        );
        
    $this->table->add_row(lang('api_key'),form_input('api_key', $api_key));
    $this->table->add_row(lang('username'),form_input('username', $username));
    
	if(count($artists_data) > 0) {
		//artist_id 
		$artist_select = form_radio(array('name' => 'artist_id','value' => '0', 'checked' => ($artist_id == '0' || $artist_id == '' ? 1 : 0),'id' => 'artist_0')).NBS.'<label for="artist_0">'.lang('all_artists').'</label>'.BR;
		foreach($artists_data as $artist) 
		{
			if(preg_match('/\/(\d+)$/', $artist['url'], $matches)) {
				$artist_option_id = $matches[1];
            	$artist_select .= form_radio(array('name' => 'artist_id','value' => $artist_option_id, 'checked' => ($artist_id == $artist_option_id ? 1 : 0),'id' => 'artist_'.$artist_option_id)).NBS.
            		'<label for="artist_'.$artist_option_id.'">'.$artist['name'].'</label>'.BR;
            }
        }
		$this->table->add_row(lang('choose_artist'),$artist_select);
		
	}
	
	$this->table->add_row(lang('update_offers_automatically'),form_checkbox('update_offers', '1',$update_offers));

    $this->table->add_row(lang('twitter_username'),form_input('twitter_username', $twitter_username));
    $this->table->add_row(lang('twitter_message'),form_textarea(array('name'=>'twitter_message',
    		 'value'=>$twitter_message,'rows'=>5,'cols'=>60)).BR.lang('twitter_tokens'));
	echo $this->table->generate();
?>
<div><?=form_submit(array('name' => 'submit', 'value' => lang('save_config'), 'class' => 'submit'));?></div>
<?=form_close()?>
<?php 
if($api_key != '' && $username != '') { ?>
<div style="margin-top:20px">
	<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin'.AMP.'method=update_artist_list')?>
	<div><?=form_submit(array('name' => 'submit', 'value' => lang('reload_artist_list'), 'class' => 'submit'));?></div>
	<?=form_close()?>
</div>
<?php
}
?>
