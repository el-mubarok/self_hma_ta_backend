<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Input $input
 * @property CI_Session $session
 * @property MAuth $modelAuth
 */
class Auth extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('MAuth', 'modelAuth');
	}

	public function index(){
		$data['page'] = array(
			'title'=>'Log-In Admin',
			'parent'=>null,
			'child'=>null
		);

		if($this->modelAuth->current_user()){
			redirect('dashboard');
		}
		$this->load->view('VAuth', $data);
	}

	public function login() {
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if($this->modelAuth->login($username, $password)){
			redirect('dashboard');
		} else {
			$this->session->set_flashdata(
				'message_login_error', 
				'Login Gagal, pastikan username dan passwrod benar!'
			);
		}

		redirect('auth');
	}

	public function logout()
	{
		$this->modelAuth->logout();
		redirect(site_url());
	}
}
