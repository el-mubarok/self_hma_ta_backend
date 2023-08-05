<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property MAuth $modelAuth
 */
class Dashboard extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('auth/mauth', 'modelAuth');
	}

	public function index(){
		$data['page'] = array(
			'title'=>'Dashboard',
			'parent'=>1,
			'child'=>1
		);

		if(!$this->modelAuth->current_user()){
			return redirect('auth/login');
		}

		// print_r($this->encryption);
		// echo $this->encryption->encrypt("923yusuf01");
		// echo $this->encryption->decrypt("e7268fcde0c328f33baf483bc694588107a5f4ab004814262db0b6eefb4aac16841cd3af2d86bb1e84d8cea0a3f65388ed5670f2da90efed916bb31b2a092c31ux2Yvg8vAikZq4f3qhFYfATO1uIbEbd9JAszSLpVA7E=");
		$this->load->view('VDashboard', $data);
	}
}
