<?php

class M_Billing extends CI_Model
{
	public function get($table, $where = null, $isSingle = true)
	{
		if (isset($where)) {
			$this->db->where($where);

			if($isSingle) {
				return $this->db->get($table)->row();
			}else{
				return $this->db->get($table)->result();
			}
		}

		return $this->db->get($table)->result();
	}
}
