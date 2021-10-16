<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * FeedIgnition
 *
 * A multiuser Feed reader for CodeIgniter
 *
 * @package		FeedIgnition
 * @author		Jim O'Halloran
 * @copyright	Copyright (c) 2007, Tux IT Services
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.tuxit.com.au
 * @since		Version 1.0
 * @filesource
 */

/**
 * User Controller
 *
 * Handles signup, login and logout.
 *
 * @package		FeedIgnition
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Jim O'Halloran
 */
class User extends Controller {

	function __construct() {
		parent::Controller();
		$this->load->model('UserModel');
	}

	function signup() {
		$this->load->library('validation');
		
		$this->validation->set_rules(array(
				'username' => 'trim|required|min_length[3]|max_length[20]',
				'password1' => 'trim|required|matches[password2]',
				'password2' => 'trim|required',
				'email' => 'trim|min_length[2]|max_length[100]|valid_email',
				'first_name' => 'trim|min_length[2]|max_length[50]',
				'last_name' => 'trim|min_length[2]|max_length[50]',
		));
		$this->validation->set_fields(array(
				'username' => 'Username',
				'password1' => 'Password',
				'password2' => 'Confirm Password',
				'email' => 'Email Address',
				'first_name' => 'Firat Name',
				'last_name' => 'Last Name',
			)); 
		$this->validation->set_error_delimiters('<li>', '</li>');
		
		
		if ($this->input->post('submitted') === false) {
			// Not submitted
			$this->load->view('user/signup');
		} else {
			// Submitted
			if ($this->validation->run()) {
				$this->UserModel->username = $this->validation->username;
				$this->UserModel->password = $this->validation->password1;
				$this->UserModel->email = $this->validation->email;
				$this->UserModel->first_name = $this->validation->first_name;
				$this->UserModel->last_name = $this->validation->last_name;
				$this->UserModel->save();
				
				$this->UserModel->set_session();
				
				redirect('');
			} else {
				$this->load->view('user/signup');
			}
		}
	}
	
	function login() {
		$this->load->library('validation');
		
		$this->validation->set_rules(array(
				'username' => 'trim|required',
				'password' => 'trim|required',
		));
		$this->validation->set_fields(array(
				'username' => 'Username',
				'password' => 'Password',
			)); 
		$this->validation->set_error_delimiters('<li>', '</li>');
		
		
		if ($this->input->post('submitted') === false) {
			// Not submitted
			$this->load->view('user/login', array('auth_fail' => false));
		} else {
			// Submitted
			if ($this->validation->run()) {
				$logged_in = $this->UserModel->authenticate($this->validation->username, $this->validation->password);
				if ($logged_in) {
					redirect('');
				} else {
					$this->load->view('user/login', array('auth_fail' => true));
				}
			} else {
				$this->load->view('user/login', array('auth_fail' => false));
			}
		}
	}
	
	
	function logout() {
		$this->session->unset_userdata('user');
		redirect('user/login');
	}
	
	
}

?>