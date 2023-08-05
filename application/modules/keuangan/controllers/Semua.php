<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property MAuth $modelAuth
 */
class Semua extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('auth/mauth', 'modelAuth');
	}

	public function index(){
		$data['page'] = array(
			'title'=>'Semua Iuran',
			'parent'=>3,
			'sub'=>1,
			'child'=>null,
			'grandchild'=> null
		);

		if(!$this->modelAuth->current_user()){
			return redirect('auth/login');
		}

		$this->load->view('VSemua',$data);
	}
}
