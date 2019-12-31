<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kamus_model extends CI_Model {

	function get_where($table, $where){		
		return $this->db->get_where($table, $where);
	}
}

/* End of file Kamus.php */
/* Location: ./application/models/Kamus.php */