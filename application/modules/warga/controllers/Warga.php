<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property MAuth $modelAuth
 */
class Warga extends CI_Controller {
	function __construct(){
		parent::__construct();

		$this->load->model('auth/mauth', 'modelAuth');
	}

	public function index(){
		// $t = $_GET['t'];
		// $data['page'] = array(
		// 	'title'=>($t == 'tetap' ? 'Warga Tetap' : ($t == 'kontrak' ? 'Warga Kontrak' : '')),
		// 	'parent'=>4,
		// 	'child'=>($t == 'tetap' ? 1 : ($t == 'kontrak' ? 2 : ''))
		// );
		$data['page'] = array(
			'title'=> 'Warga',
			'parent'=> 2,
			'child'=> 1
		);

		if(!$this->modelAuth->current_user()){
			return redirect('auth/login');
		}

		$this->load->view('VWarga', $data);
	}
}
