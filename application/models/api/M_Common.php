<?php

class M_Common extends CI_Model
{
	public function get($table, $where = null, $isSingle = true, $order = null)
	{
		if (isset($where)) {
			$this->db->where($where);

			if($order) {
				$this->db->order_by($order);
			}

			if($isSingle) {
				return $this->db->get($table)->row();
			}else{
				return $this->db->get($table)->result();
			}
		}

		if($order) {
			$this->db->order_by($order);
		}

		// if($isSingle) {
		// 	return $this->db->get($table)->row();
		// }else{
		// 	return $this->db->get($table)->result();
		// }

		return $this->db->get($table)->result();
	}

	public function insert($table, $data)
	{
		$this->db->insert($table, $data);

		return $this->db->insert_id();
	}

	public function update($table, $where, $data)
	{
		foreach ($data as $k => $v) {
			$this->db->set($k, $v, true);
		}

		$this->db->where($where);
		return $this->db->update($table);
	}

	public function fullDelete($table, $id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($table);
	}
}
