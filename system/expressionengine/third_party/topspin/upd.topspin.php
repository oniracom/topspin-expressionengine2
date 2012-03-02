<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Topspin_upd {

	var $version = '0.1';
	
	function Topspin_upd()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}
	
	
	// --------------------------------------------------------------------

	/**
	 * Module Installer
	 *
	 * @access	public
	 * @return	bool
	 */	
	function install()
	{
		$this->EE->load->dbforge();

		$data = array(
			'module_name' => 'Topspin' ,
			'module_version' => $this->version,
			'has_cp_backend' => 'y',
			'has_publish_fields' => 'n'
		);

		$this->EE->db->insert('modules', $data);

		$fields = array(
			'id' 				=> array('type' 		=> 'int',
									'constraint' 		=> 11,
									'unsigned'			=> TRUE,
									'auto_increment' 	=> TRUE),
			'api_key'			=> array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'username'			=> array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'artist_id' 		=> array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'artists_data' 		=> array('type' => 'text'),
			'offers_data' 		=> array('type' => 'mediumtext'),
			'offers_timestamp' 	=> array('type' => 'int', 'constraint' => '11', 'null' => FALSE, 'default' => 0),
			'update_offers' 	=> array('type' => 'tinyint', 'constraint' => '1', 'null' => FALSE, 'default' => 0),
			'twitter_username'  => array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'twitter_message'	=> array('type' => 'mediumtext')//,
			//'store_data'		=> array('type' => 'longtext'),
			//'store_data_timestamp' => array('type' => 'int', 'constraint' => '11', 'null' => FALSE, 'default' => 0)
			);
		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('topspin');

		unset($fields);
		
	/*	$fields = array(
			'id' 				=> array('type' 		=> 'int',
									'constraint' 		=> 11,
									'unsigned'			=> TRUE,
									'auto_increment' 	=> TRUE),
			'name' 				=> array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'uri'				=> array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'channel_id' 		=> array('type' => 'int', 'constraint' => '11', 'null' => FALSE, 'default' => 0),
			'template_id' 		=> array('type' => 'int', 'constraint' => '11', 'null' => FALSE, 'default' => 0),
			'entry_id' 			=> array('type' => 'int', 'constraint' => '11', 'null' => FALSE, 'default' => 0),
			'store_data'		=> array('type' => 'longtext'),
			'store_data_timestamp' => array('type' => 'int', 'constraint' => '11', 'null' => FALSE, 'default' => 0),
			'template'			=> array('type' => 'varchar', 'constraint' => '50', 'null' => TRUE, 'default' => NULL),
			'rows_pp'			=> array('type' => 'int', 'constraint' => '3', 'null' => FALSE, 'default' => 1), 
			'offer_types'		=> array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'tags'				=> array('type' => 'varchar', 'constraint' => '250', 'null' => TRUE, 'default' => NULL),
			'sort_direction' 	=> array('type' => 'varchar', 'constraint' => '25', 'null' => TRUE, 'default' => NULL),
			'detail_pages' 		=> array('type' => 'tinyint', 'constraint' => '1', 'null' => FALSE, 'default' => 0));
		$this->EE->dbforge->add_field($fields);
		$this->EE->dbforge->add_key('id', TRUE);
		$this->EE->dbforge->create_table('topspin_stores');	
		
		unset($fields);*/
	
		return TRUE;
	}
	
	
	// --------------------------------------------------------------------

	/**
	 * Module Uninstaller
	 *
	 * @access	public
	 * @return	bool
	 */
	function uninstall()
	{
		$this->EE->load->dbforge();

		$this->EE->db->select('module_id');
		$query = $this->EE->db->get_where('modules', array('module_name' => 'Topspin'));

		$this->EE->db->where('module_id', $query->row('module_id'));
		$this->EE->db->delete('module_member_groups');

		$this->EE->db->where('module_name', 'Topspin');
		$this->EE->db->delete('modules');

		$this->EE->db->where('class', 'Topspin');
		$this->EE->db->delete('actions');

		$this->EE->dbforge->drop_table('topspin');
		$this->EE->dbforge->drop_table('topspin_stores');		

		return TRUE;
	}



	// --------------------------------------------------------------------

	/**
	 * Module Updater
	 *
	 * @access	public
	 * @return	bool
	 */	
	
	function update($current='')
	{
		return TRUE;
	}
	
}
/* END Class */
