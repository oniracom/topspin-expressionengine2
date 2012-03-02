<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topspin_mcp {
	var $version = '0.1.0';
	
	function __construct( $switch = TRUE ) {
		//global $IN, $DB;
		$this->EE =& get_instance();
		
		//check that topspin module is installed
		//$module_query = $DB->query('SELECT module_id FROM exp_modules WHERE module_name = "Topspin"');
		$module_query = $this->EE->db->query('SELECT module_id FROM exp_modules WHERE module_name = "Topspin"');
		if(!$module_query->num_rows()) {
			return;
		}
		
		if(!$this->_get_module_config()) return $this->general_config();
	
	}
	
	function _get_module_config() {
		//check that config has been entered
		$sql = "SELECT `id`, `api_key`, `username`, `artist_id`, `artists_data`, `update_offers`, `twitter_username`, `twitter_message`, `offers_data`, `offers_timestamp` FROM exp_topspin ORDER BY id ASC LIMIT 1";
		$config_vars = $this->EE->db->query($sql);
		$this->module_config = $config_vars;
		if($config_vars->num_rows() == 0 && $this->EE->input->get_post('P') != 'update_general_config') return $this->index();
		if(($config_vars->row('api_key') == '' || $config_vars->row('username') == '' || $config_vars->row('artist_id') == '') && $this->EE->input->get_post('method') != 'update_general_config') return false;
		return true;
	}
	
	function content_wrapper( $highlight = null ) {
			//global $IN, $DB, $DSP, $LANG;
			
			//$DSP->title = $LANG->line('topspin_module_name');
			$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin', $this->EE->lang->line('topspin_module_name'));
			$nav_array = array(	'general_config' => array('title'=>$this->EE->lang->line('general_config')), 'docs' => array('title'=>$this->EE->lang->line('docs'), 'method' => 'docs'));
			
			$nav = $this->nav($nav_array, $highlight);
			
			return $nav;
		
		}
		
		
		 /** -----------------------------------
		 /**  Navigation Tabs
		 /** -----------------------------------*/
		
		function nav($nav_array, $highlight = null)
		{
			//global $IN, $DSP, $PREFS, $REGX, $FNS, $LANG;
		                
			/** -------------------------------
			/**  Build the menus
			/** -------------------------------*/
			// Equalize the text length.
			// We do this so that the tabs will all be the same length.
				
			$temp = array();
			foreach ($nav_array as $k => $v)
			{
				//$temp[$k] = $LANG->line($k);
				$temp[$k] = $v['title'];
			}
			//$temp = $DSP->equalize_text($temp);
		
			//-------------------------------
			$page = ($highlight == null ? $this->EE->input->get_post('method') : $highlight);
		  
		  
			$nav = array();
			foreach ($nav_array as $key => $val)
			{
				$url = '';
				
				if (is_array($val))
				{
					$url = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin';		
					
					foreach ($val as $k => $v)
					{
						if($k != 'title' && $v != null) $url .= AMP.$k.'='.$v;
					}					
					$title = $val['title'];
					
				}
		
				$url = ($url == '') ? $val : $url;
		
				$link = '<li class="content_tab'.(($page == $key) ? ' current': '').'">'.
					'<a href="'.$url.'">'.$title.'</a>'.
				'</li>';
				//$nav[] = array('text' => $DSP->anchor($url, $linko));
				$nav[] = $link;
			}
		
//			$r .= $DSP->table_row($nav);		
//			$r .= $DSP->table_close();
			$vars = array('links'=>$nav);
			return $this->EE->load->view('nav', $vars, TRUE);
			$r .= join('',$nav).'</ul>';
			
			return $r;          

	    }
	    /* END */
	
	function index($msg = '') {
		//global $IN, $DSP, $DB, $LANG, $PREFS;
		
		$r = $this->content_wrapper('general_config');
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('general_config'));
		
		//$DSP->crumb .= $DSP->crumb_item($LANG->line('general_config'));    
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin', $this->EE->lang->line('topspin_module_name'));
//		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin', 'config2');
		
		$config_vars = $this->module_config;
		if($config_vars->num_rows() == 0) {
			$api_key = '';
			$username = '';
			$artist_id = '';
			$artists_data = array();
			$update_offers = '';
			$twitter_username ='';
			$twitter_message ='';
		} else {
			$api_key = $config_vars->row('api_key');
			$username = $config_vars->row('username');
			$artist_id = $config_vars->row('artist_id');
			$artists_data = unserialize(stripslashes($config_vars->row('artists_data')));
			$update_offers = $config_vars->row('update_offers');
			$twitter_username = $config_vars->row('twitter_username');
			$twitter_message = $config_vars->row('twitter_message');
		}
		
		$this->EE->load->helper('form');
		$this->EE->load->library('table');
		
		$vars = array('api_key' => $api_key, 'username' => $username,'update_offers' => ($update_offers == 1 ? 1 : 0), 'twitter_username' => $twitter_username, 'twitter_message' => $twitter_message, 'artists_data'=>$artists_data, 'artist_id'=>$artist_id, 'msg' => $msg);
		return $r.$this->EE->load->view('index', $vars, TRUE);
	}
	
	function docs() {
		
		$r = $this->content_wrapper('docs');
		$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('docs'));
		
		//$DSP->crumb .= $DSP->crumb_item($LANG->line('general_config'));    
		$this->EE->cp->set_breadcrumb(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=topspin', $this->EE->lang->line('topspin_module_name'));
		return $r.$this->EE->load->view('docs',array(),TRUE);
	}
	
	function update_general_config() 
	{
		//global $DB, $IN, $LANG;
		
		$username = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->post('username')));
		$api_key = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->post('api_key')));
		$artist_id = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->post('artist_id')));
		$update_offers = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->post('update_offers')));
		$update_offers = ($update_offers == '1' ? '1' : '0');
		$twitter_username = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->post('twitter_username')));
		$twitter_message = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->post('twitter_message')));
		
		//check for empty post?
		if(count($_POST) == 0) return $this->general_config(); 
		
		$data = array('username'		=>	$username,
					'api_key'			=>	$api_key, 
					'artist_id'			=>	$artist_id, 
					'update_offers'		=>	$update_offers,
					'twitter_username'	=>	$twitter_username,
					'twitter_message'	=>	$twitter_message,
					'offers_timestamp'	=>  0);
		
		$config_vars  = $this->module_config;
	
		if($config_vars->num_rows() == 0) {
			$this->EE->db->query($this->EE->db->insert_string('exp_topspin', $data));
		} else {
			$this->EE->db->query($this->EE->db->update_string('exp_topspin', $data, "id = ".$config_vars->row('id')));
		}
		
		$this->_get_module_config();
		
		$this->update_artist_list();
		
		//$this->EE->db->query($this->EE->db->update_string('exp_topspin', array('store_data_timestamp' => 0), "id = ".$config_vars->row('id')));
		//$this->EE->db->query('UPDATE exp_topspin_stores SET store_data_timestamp = 0');
		
		return $this->index($this->EE->lang->line('config_saved'));	
	
	}
	/*
	function store_config($msg = '', $error = '', $store_id = 0) 
	{
		//global $DSP, $DB, $IN, $LANG, $PREFS, $FNS;
		
		if($store_id == 0) {
			$store_id = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('num')));
			$store_id = (is_numeric($store_id) ? $store_id : '0');
		}
		
		$store_config = $this->EE->db->query("SELECT name, uri, store_data, `template`, `rows_pp`, `offer_types`, `tags`, `sort_direction`, `detail_pages` 
				FROM exp_topspin_stores WHERE id = ".$store_id);
		
		//$DSP->extra_css = PATH.'modules/topspin/topspin_cp.css';		
		$r = '';
		if($store_config->num_rows() ==0) {
			
			$r .= $this->content_wrapper('add_new_store');
			
			$this->EE->cp->set_variable('cp_page_title', $this->EE->lang->line('add_new_store'));
						
			$store_name = '';
			$store_uri = '';
			$template = '';
			$rows_pp = 1;
			$offer_types = array();
			$tags = array();
			$sort = 'asc';
			$detail_pages = '0';
			
		} else {
		
			$r .= $this->content_wrapper('store_'.$store_id);
			
			$this->EE->cp->set_variable('cp_page_title', $store_config->row('name'));
		
			$store_name = $store_config->row('name');
			$store_uri = $store_config->row('uri');
			$template = $store_config->row('template');
			$rows_pp = $store_config->row('rows_pp');
			$offer_types = (!unserialize($store_config->row('offer_types')) ? array() : unserialize($store_config->row('offer_types')));
			$tags = (!unserialize($store_config->row('tags')) ? array() : unserialize($store_config->row('tags')));
			$sort = $store_config->row('sort_direction');
			$detail_pages = $store_config->row('detail_pages');
				
		}
		
		
		$offers = $this->get_offers();
		$tag_list = $this->get_tags($offers);
		//$offer_type_options = array('buy_button','email_for_media','bundle_widget','single_track_player_widget','fb_for_media');
		$offer_type_options = array('buy_button','email_for_media','single_track_player_widget','fb_for_media');
		
		$this->EE->load->helper('form');
		$this->EE->load->library('table');
		
		if($store_id > 0) {
			$this->EE->cp->set_right_nav(array(
		        $this->EE->lang->line('visit_store') => $store_config->row('uri')
			));
		} else {
			
			
		}
		
		$store_template_options = array('light' => 'Light', 'dark' => 'Dark', 'custom' => 'Custom');
		
		$row_options = array();
		for($i = 1; $i<=15; $i++) {
			$row_options[$i] = $i;
		}
		
		$sort_directions = array('asc' => 'Largest', 'desc' => 'Smallest');
		
		$vars = array('store_id' => $store_id, 'store_name' => $store_name, 'store_uri' => $store_uri, 'store_template_options' => $store_template_options, 'template' => $template, 'row_options' => $row_options, 'rows_pp' => $rows_pp, 'offer_type_options' => $offer_type_options, 'offer_types' => $offer_types, 'tag_list' => $tag_list, 'tags' => $tags, 'sort_directions' => $sort_directions, 'sort' => $sort, 'detail_pages' => $detail_pages, 'msg' => $msg);
		
		return $r.$this->EE->load->view('store_config', $vars, TRUE);
	}
	
	function update_store() 
	{
		//global $DB, $IN, $PREFS, $LOC, $SESS, $FNS, $REGX, $LANG;
		$this->EE->load->helper('url');
			
		$store_id		= $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('num')));
		$store_id		= (is_numeric($store_id) ? $store_id : '0');
		$store_name 	= $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('store_name')));
		$store_uri 		= $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('store_uri')));
		if(substr($store_uri,-1) != '/') $store_uri .= '/';
		$template 		= $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('template')));
		$rows_pp  		= $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('rows_pp')));
		$offer_types  	= $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('offer_types')));
		$tags  			= $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('tags')));
		$sort_direction = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('sort')));
		$detail_pages  	= $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('detail_pages')));
		$detail_pages	= ($detail_pages == '1' ? '1' : '0');
		
		if($store_name == '' || $store_uri == '') {
			return $this->store_config('','Name and URI are required');
		} 
		
		$site_id = 1; //$this->EE->config->site_id;
		
		$pages_var = $this->get_pages_vars();
		
		$store_data = array('name' => $store_name, 
							'uri' => $store_uri, 
							'channel_id' => $pages_var['channel_id'], 
							'template_id' => ($template == 'dark' ? $pages_var['dark_template_id'] : $pages_var['light_template_id']), 
							'template'=>$template, 
							'rows_pp'=>$rows_pp, 
							'offer_types'=>serialize($offer_types), 
							'tags'=>serialize($tags),
							'sort_direction'=>$sort_direction,
							'detail_pages' => $detail_pages,
							'store_data_timestamp' => '0'
							);
		
		
		$store_config = $this->EE->db->query("SELECT name, uri, store_data, `channel_id`, `template_id`, `entry_id`, `template`, `rows_pp`, `offer_types`, `tags`, `sort_direction`, `detail_pages` 
				FROM exp_topspin_stores WHERE id = ".$store_id);
		
		$this->EE->load->library('api'); 
		$this->EE->api->instantiate('channel_entries');
		$this->EE->api->instantiate('channel_fields');
				
		if($store_config->num_rows() == 0) {
			//create pages entries
			$channel_data = array('title' => $store_name, 'entry_date' => $this->EE->localize->now);
			$this->EE->api_channel_fields->setup_entry_settings($pages_var['channel_id'], $channel_data);
			$this->EE->api_channel_entries->submit_new_entry($pages_var['channel_id'], $channel_data);
			//$channel_data = array('site_id'=>$site_id, 'channel_id'=>$pages_var['channel_id'], 'author_id' => $this->EE->session->userdata['member_id'], 'ip_address' => $this->EE->input->ip_address(), 'title'=> $store_name, 'url_title' => url_title($store_name), 'status' => 'open', 'entry_date' => $this->EE->localize->now, 'year' => gmdate('Y', $this->EE->localize->now), 'month' => gmdate('m', $this->EE->localize->now), 'day' => gmdate('d', $this->EE->localize->now), 'edit_date' => gmdate('YmdHis', $this->EE->localize->now));
			//$this->EE->db->query($this->EE->db->insert_string('exp_channel_titles',$channel_data));
			//$entry_id = $this->EE->db->insert_id();
			
			//$channel_data = array('entry_id' => $entry_id, 'site_id' => $site_id, 'channel_id'=> $pages_var['channel_id']);
			//$this->EE->db->query($this->EE->db->insert_string('exp_channel_data',$channel_data));
			
			$store_data['entry_id'] = $this->EE->api_channel_entries->entry_id;
			$entry_id = $store_data['entry_id'];
			$this->EE->db->query($this->EE->db->insert_string('exp_topspin_stores',$store_data));
			$store_id = $this->EE->db->insert_id();
			
		} else {
		
			$entry_id = $store_config->row('entry_id');
			
			$channel_data = array('channel_id' => $pages_var['channel_id'], 'entry_date' => $this->EE->localize->now);
			$this->EE->api_channel_entries->update_entry($entry_id, $channel_data);
			//$channel_data = array('channel_id'=>$pages_var['channel_id'],
			//			'title' => $store_name,
			//			'url_title'=>url_title($store_name),
			//			'edit_date' => gmdate('YmdHis', $this->EE->localize->now)
			//			);
		//	$this->EE->db->query($this->EE->db->update_string('exp_channel_titles',$channel_data,'entry_id = '.$entry_id));
			
			$store_data['entry_id'] = $entry_id;
			$this->EE->db->query($this->EE->db->update_string('exp_topspin_stores',$store_data,'id = '.$store_id));
		}
				
		$this->save_pages_config($store_uri,$store_data['template_id'],$entry_id);
		
		return $this->store_config($this->EE->lang->line('config_saved'),'',$store_id);
	}
	
	function delete_store() {
		//global $DB, $IN, $PREFS;
		
		$store_id = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('num')));
		$store_id = (is_numeric($store_id) ? $store_id : '0');
		
		$store_config = $this->EE->db->query("SELECT name, uri, store_data, `channel_id`, `template_id`, `entry_id`, `template`, `rows_pp`, `offer_types`, `tags`, `sort_direction`, `detail_pages` FROM exp_topspin_stores WHERE id = ".$this->EE->db->escape_str($store_id));
				
		if($store_config->num_rows()) {
		 	if($store_config->row('entry_id') > 0) {
				$this->EE->load->library('api'); 
				$this->EE->api->instantiate('channel_entries');

				//$this->EE->api_channel_entries->delete_entry($store_config->row('entry_id'));
				$this->EE->db->query('DELETE FROM exp_channel_titles WHERE entry_id = '.$this->EE->db->escape_str($store_config->row('entry_id')));
				$this->EE->db->query('DELETE FROM exp_channel_data WHERE entry_id = '.$this->EE->db->escape_str($store_config->row('entry_id')));
				
				$this->EE->db->query('DELETE FROM exp_topspin_stores WHERE entry_id = '.$this->EE->db->escape_str($store_config->row('entry_id')));
				
				$site_id = 1; //$this->EE->config->site_id;
				
				//update pages config
				$pages_query = $this->EE->db->query('SELECT site_pages FROM exp_sites WHERE site_id = '.$this->EE->db->escape_str($site_id));
				if($pages_query->num_rows == 0) {
					//error == bad
				} else {
					$site_pages_raw = $pages_query->row('site_pages');
					if($site_pages_raw == '' || (unserialize(base64_decode($site_pages_raw)) == null)) {
						//no pages???
					} else {
						//update pages object
						$site_pages = unserialize(base64_decode($site_pages_raw));
						if(isset($site_pages[$site_id])) {
							//update existisg pages object
							$pages_config = $site_pages[$site_id];
							unset($pages_config['uris'][$store_config->row('entry_id')]);
							unset($pages_config['templates'][$store_config->row('entry_id')]);
							$site_pages[$site_id] = $pages_config;
							$this->EE->db->query($this->EE->db->update_string('exp_sites',array('site_pages'=>base64_encode(serialize($site_pages))),'site_id = '.$site_id));
						}
					}
					
				}
			}
		}
		return $this->index($this->EE->lang->line('store_deleted'));
	}
	*/
	function get_offers() {
		//global $DB, $PREFS;
		$topspin_config = $this->module_config;
		//get topspin data from db or topspin
		if($topspin_config->row('offers_data') == '' || (unserialize($topspin_config->row('offers_data')) == false) || $topspin_config->row('offers_timestamp') < date('U')-80000) {
			require_once 'Topspin.php';
			
			$artist_ids = array();
			if($topspin_config->row('artist_id') > 0) {
				$artist_ids[] = $topspin_config->row('artist_id');
			} else {
				$artists_data = unserialize(stripslashes($topspin_config->row('artists_data')));
				if(count($artists_data) > 0) {
					foreach($artists_data as $artist) {
						if(preg_match('/\/(\d+)$/', $artist['url'], $matches)) {
							$artist_ids[] = $matches[1];
						}
					}
				}
			}
			$offers_final = array();
			
			foreach($artist_ids as $artist_id) {
				$t = new Topspin_curl($topspin_config->row('api_key'), $topspin_config->row('username'), $artist_id);
			
				$offers_obj = $t->getOffers(1);
				$offers = $offers_obj->offers;
				while($offers_obj->current_page < $offers_obj->total_pages) {
					$offers_obj = $t->getOffers($offers_obj->current_page+1);
					$offers = array_merge($offers,$offers_obj->offers);
				}
			
				foreach($offers as $o) {
					if($o->status == "active") {
						$offers_final[] = $o;	
					}
				}			
			}
			$this->EE->db->query($this->EE->db->update_string('exp_topspin',array('offers_data'=>serialize($offers_final), 'offers_timestamp'=>date('U')),'id = '.$topspin_config->row('id')));
			$offers = $offers_final;
		} else {
			$offers = unserialize($topspin_config->row('offers_data'));
		}
		return $offers;
	}
	
	function get_tags($offers) {
		$tags = array();
		foreach($offers as $o) {
			if(isset($o->tags)) {
				foreach($o->tags as $t) {
					if(array_search($t, $tags) === false) {
						$tags[] = $t;
					}
				}
			}
		}
		return $tags;
	}
	
	function clear_cached_offers() {
		//global $DB, $IN, $LANG;
		$store_id = $this->EE->db->escape_str($this->EE->security->xss_clean($this->EE->input->get_post('num')));
		
		if($store_id != '') {
			$topspin_config = $this->module_config;
			$this->EE->db->query($this->EE->db->update_string('exp_topspin',array('offers_data'=>''),'id = '.$topspin_config->row('id')));
		}
		return $this->store_config($this->EE->lang->line('config_saved'));
	}
		/*
	function get_pages_vars() {
		//global $DB, $PREFS, $LOC, $FNS;
		
		$site_id = 1; //$this->EE->config->site_id;
		$site_url = $this->EE->functions->fetch_site_index();
		$pages_vars = array();
		//check that template group exists
		$template_group_query = $this->EE->db->query('SELECT group_id FROM exp_template_groups WHERE group_name = "_topspin_stores" AND site_id = '.$site_id);
		//file_put_contents('/tmp/template_group_query', mixed data, int flags, [resource context])
		if($template_group_query->num_rows() == 0) {
			$this->EE->load->library('api'); 
			$this->EE->api->instantiate('template_structure');
			
			$max_group_order_query = $this->EE->db->query('SELECT max(group_order) as max_group_order FROM exp_template_groups WHERE site_id = '.$site_id);
			if($max_group_order_query->num_rows() == 0) {
				$max_group_order = 0;
			} else {
				$max_group_order = $max_group_order_query->row('max_group_order');
			}
			
			$data = array( 'group_name'  => '_topspin_stores',
			        'group_order'       => $max_group_order+1,
			        'is_site_default'   => 'n',
			        'site_id'              => $site_id
			);
			
			//$data = array('site_id' => $site_id, 'group_name'=>'_topspin_stores','group_order'=>($max_group_order+1), 'is_site_default'=>'n');
			//$this->EE->db->query($this->EE->db->insert_string('exp_template_groups',$data));
			//$template_group_id = $this->EE->db->insert_id();
			$template_group_id = $this->EE->api_template_structure->create_template_group($data);
		} else {
			$template_group_id = $template_group_query->row('group_id');
		}
		$pages_vars['template_group_id'] = $template_group_id;
		
		//make default templates if they don't exist
		//index
		$template_query = $this->EE->db->query('SELECT template_id FROM exp_templates WHERE site_id = '.$site_id.' AND template_name = "index" AND group_id = '.$template_group_id);
		if($template_query->num_rows() == 0) {
			$data = array('site_id'=>$site_id, 'group_id'=>$template_group_id, 'template_name'=>'index', 'save_template_file'=>'n', 'template_type'=>'webpage', 'template_data'=>'This page left intentionally blank. =)', 'edit_date'=>$this->EE->localize->now);
			$this->EE->db->query($this->EE->db->insert_string('exp_templates',$data));
			$template_id = $this->EE->db->insert_id();
		} else {
			$template_id = $template_query->row('template_id');
		}
		
		//light
		$template_query = $this->EE->db->query('SELECT template_id FROM exp_templates WHERE site_id = '.$site_id.' AND template_name = "light" AND group_id = '.$template_group_id);
		if($template_query->num_rows() == 0) {
			$data = array('site_id'=>$site_id, 'group_id'=>$template_group_id, 'template_name'=>'light', 'save_template_file'=>'n', 'template_type'=>'webpage', 'template_data'=>file_get_contents(PATH_THIRD.'topspin/default_light.html'), 'edit_date'=>$this->EE->localize->now);
			$this->EE->db->query($this->EE->db->insert_string('exp_templates',$data));
			$light_template_id = $this->EE->db->insert_id();
		} else {
			$light_template_id = $template_query->row('template_id');
		}
		$pages_vars['light_template_id'] = $light_template_id;
		//dark
		$template_query = $this->EE->db->query('SELECT template_id FROM exp_templates WHERE site_id = '.$site_id.' AND template_name = "dark" AND group_id = '.$template_group_id);
		if($template_query->num_rows() == 0) {
			$data = array('site_id'=>$site_id, 'group_id'=>$template_group_id, 'template_name'=>'dark', 'save_template_file'=>'n', 'template_type'=>'webpage', 'template_data'=>file_get_contents(PATH_THIRD.'topspin/default_dark.html'), 'edit_date'=>$this->EE->localize->now);
			$this->EE->db->query($this->EE->db->insert_string('exp_templates',$data));
			$dark_template_id = $this->EE->db->insert_id();
		} else {
			$dark_template_id = $template_query->row('template_id');
		}
		$pages_vars['dark_template_id'] = $dark_template_id;
		
		$channel_query = $this->EE->db->query('SELECT channel_id FROM exp_channels WHERE site_id = '.$site_id.' AND channel_name like "topspin_stores"');
		if($channel_query->num_rows() == 0) {
			//add new channel
			//$data = array('site_id' => $site_id, 'channel_name' => 'topspin_stores', 'channel_title' => 'Topspin Stores - Do not edit', 'channel_url' => $site_url, 'channel_lang' => 'en');
			//$this->EE->db->query($this->EE->db->insert_string('exp_channels', $data)); 
			//$channel_id = $this->EE->db->insert_id();
			$data = array('channel_name' => 'topspin_stores', 'channel_title' => 'Topspin Stores - Do not edit');
			$channel_id = $this->EE->api_channel_structure->create_channel($data);
		} else {
			$channel_id = $channel_query->row('channel_id');
		}
		$pages_vars['channel_id'] = $channel_id;
		
		return $pages_vars;
	}
	
	function save_pages_config($store_uri,$template_id,$entry_id) {
		//global $DB, $PREFS, $FNS;
		
		$site_id = 1; //$this->EE->config->site_id;
		$site_url = $this->EE->functions->fetch_site_index();
		
		//update pages config
		$pages_query = $this->EE->db->query('SELECT site_pages FROM exp_sites WHERE site_id = '.$site_id);
		if($pages_query->num_rows == 0) {
			//error == bad
		} else {
			$site_pages_raw = $pages_query->row('site_pages');
			if($site_pages_raw == '' || (unserialize(base64_decode($site_pages_raw)) == null)) {
				//build new pages object
				$pages_config = array('uris' => array($entry_id=>$store_uri),
										'templates' => array($entry_id=>(string)$template_id),
										'url' => $site_url);
				$site_pages = array($site_id => $pages_config);
			} else {
				//update pages object
				$site_pages = unserialize(base64_decode($site_pages_raw));
				if(isset($site_pages[$site_id])) {
					//update existisg pages object
					$pages_config = $site_pages[$site_id];
					$pages_config['uris'][$entry_id] = $store_uri;
					$pages_config['templates'][$entry_id] = (string)$template_id;
					$site_pages[$site_id] = $pages_config;
				} else {
					//add new site to existing pages object
					$pages_config = array('uris' => array($entry_id=>$store_uri),
											'templates' => array($entry_id=>(string)$template_id),
											'url' => $site_url);
					$site_pages[$site_id] = $pages_config;
				}
			}
			$this->EE->db->query($this->EE->db->update_string('exp_sites',array('site_pages'=>base64_encode(serialize($site_pages))),'site_id = '.$site_id));
		}
	}
	*/
	function update_artist_list() 
	{
		//global $DB, $PREFS;
		
		$site_id = $this->EE->config->item('site_id');
	/*	$sql = "SELECT `id`, `api_key`, `username`, `artist_id` FROM exp_topspin WHERE id = ".$this->EE->db->escape_str($site_id)." ORDER BY id ASC LIMIT 1";
		$config_vars = $this->EE->db->query($sql);
		if($config_vars->num_rows() == 0) return array();
	*/	$config_vars = $this->module_config;
		$artists = array();
		
		$ch = curl_init();
		$currentPage = 0;
		$totalPages = 1;
		do {
			curl_setopt($ch, CURLOPT_URL,'http://app.topspin.net/api/v1/artist?page='.($currentPage+1));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERPWD, $config_vars->row('username') . ':' . $config_vars->row('api_key'));
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			$response = curl_exec($ch);
			$responseInfo = curl_getinfo($ch);
			$responseError = curl_error($ch);
			if(!$responseError) {
				$artist_data = json_decode($response, true);
				$currentPage = $artist_data['current_page'];
				$totalPages = $artist_data['total_pages'];
				foreach($artist_data['artists'] as $artist) {
					$artists[] = $artist;
				}
			} else {
				//needs more intelligent error handling
				return array();
			}
		} while ($currentPage < $totalPages);
		curl_close($ch);
		
		$data = array('artists_data' => addslashes(serialize(array_reverse($artists))), 'offers_timestamp'=>0);
		
		$this->EE->db->query($this->EE->db->update_string('exp_topspin', $data, "id = ".$config_vars->row('id')));
		
		$this->_get_module_config();
	
	}

}
?>