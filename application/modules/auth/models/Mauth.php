<?php

define('SESSION_AUTH', 'user_id');
define('SESSION_ROLE', 'user_role');
define('ROLE_ADMIN', 'admin');
define('ROLE_DIRECTOR', 'director');

class Mauth extends CI_Model {
  public function login($username, $password) {
    $this->db->where('username', $username);
		$query = $this->db->get(TBL_ADMIN);
		$user = $query->row();

    if (!$user) {
			return FALSE;
		}

    if (!password_verify($password, $user->password)) {
			return FALSE;
		}

    $this->session->set_userdata([SESSION_AUTH => $user->id]);
		$this->session->set_userdata([SESSION_ROLE => $user->role]);

		return $this->session->has_userdata(SESSION_AUTH);
  }

  public function current_user()
	{
		if (!$this->session->has_userdata(SESSION_AUTH)) {
			return null;
		}

		$user_id = $this->session->userdata(SESSION_AUTH);
		$query = $this->db->get_where(TBL_ADMIN, ['id' => $user_id]);
		return $query->row();
	}

  public function logout()
	{
		$this->session->unset_userdata(SESSION_AUTH);
		return !$this->session->has_userdata(SESSION_AUTH);
	}
}