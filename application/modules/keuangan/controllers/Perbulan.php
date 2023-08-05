<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property MAuth $modelAuth
 */
class Perbulan extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('auth/mauth', 'modelAuth');
	}

	public function index(){
		$data['page'] = array(
			'title'=>'Iuran Perbulan',
			'parent'=>3,
			'sub'=>2,
			'child'=>null,
			'grandchild'=> null
		);

		if(!$this->modelAuth->current_user()){
			return redirect('auth/login');
		}

		if(isset($_GET['id'])) {
			$this->load->view('VPerbulanDetail',$data);
		}else{
			$this->load->view('VPerbulan',$data);
		}
	}
}
