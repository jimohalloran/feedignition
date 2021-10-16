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
 * User Model
 *
 * Model class handling operations on users, including Authentication, Access Control and User Maintenance
 *
 * @package		Feedignition
 * @subpackage	Models
 * @category	Models
 * @author		Jim O'Halloran
 */
class UserModel extends Model {

	private $_id = false;
	public $username = '';
	public $password_hash = '';
	public $email = '';
	public $first_name = '';
	public $last_name = '';
	
	function __construct() {
		parent::Model();
	}

	
	/**
	 * Implements read only properties for id and password_salt.
	 *
	 * @param string The name of the member variable to retreive.
	 * @return mixed The value of the member variable.
	 */
	function __get($var) {
		switch ($var) {
			case 'id': 
				return $this->_id;
			case 'password_salt': 
				return $this->extract_salt();
		}
	}
	
	/**
	 * Assigns a value to mamber variables.  Used to implement "write only" member variable "password".
	 *
	 * @param string The name of the member variable to assign to.
	 * @param mixed The value to assign.
	 */
	function __set($var, $value) {
		switch ($var) {
			case 'password':
				$this->password_hash = $this->hash_password($value);
			default:
				$this->$var = $value;
				break;
		}
	}
	
	/**
	 * Loads a user record from the database.
	 * 
	 * @param mixed The id of the record to load, or an array containing the entire record.
	 * @return booleam True on success, false of the record with the nominated id could not be loaded.  
	 */
	function load($id_or_row) {
		if (is_array($id_or_row)) {
			$row = $id_or_row;
		} elseif (is_numeric($id_or_row)) {
			$rs = $this->db->query('SELECT * FROM users WHERE id = ?', array($id_or_row));
			if ($rs->num_rows() > 0) {
				$row = $rs->row_array();
			} else {
				return false;
			}
		} else {
			$rs = $this->db->query('SELECT * FROM users WHERE username = ?', array($id_or_row));
			if ($rs->num_rows() > 0) {
				$row = $rs->row_array();
			} else {
				return false;
			}
		}
		
		$this->_id = $row['id'];
		$this->username = $row['username'];
		$this->password_hash = $row['password'];
		$this->email = $row['email'];
		$this->first_name = $row['first_name'];
		$this->last_name = $row['last_name'];
		return true;
	}
	
	/**
	 * Saves the current record to the database, insering or updating as required.
	 */
	function save() {
		if ($this->_id === false) {
			$this->db->query("INSERT INTO users(username, password, email, first_name, last_name) VALUES(?, ?, ?, ?, ?)", 
					array($this->username, $this->password_hash, $this->email, $this->first_name, $this->last_name));
			$this->_id = $this->db->insert_id();
		} else {
			$this->db->query("UPDATE users SET username=?, password=?, email=?, first_name=?, last_name=? WHERE id=?",
					array($this->username, $this->password_hash, $this->email, $this->first_name, $this->last_name, $this->_id));
		}
	}
	
	/**
	 * Resets the model object ready to create a new record.
	 */
	function reset() {
		$this->_id = false;
		$this->username = '';
		$this->password_hash = '';
		$this->email = '';
		$this->first_name = '';
		$this->last_name = '';
	}
	
	/**
	 * Extracts key fields from the user object and stores them in the session 
	 * for later recall.  Presence of this array in the session indicates that 
	 * the user is logged in.
	 */
	public function set_session() {
		$this->session->set_userdata('user', array(
					'id' => $this->id, 
					'username' => $this->username, 
					'first_name' => $this->first_name, 
					'last_name' => $this->last_name
				));
	}
	
	public function authenticate($username, $password) {
		$found = $this->load($username);
		if ($found) {
			if ($this->hash_password($password) == $this->password_hash) {
				$this->set_session();
				return true;
			}
		}
		return false;
	}
	
	private function extract_salt() {
		if (strlen($this->password_hash) == 0) {
			return $this->generate_salt();
		} else {
			$bits = explode('$', $this->password_hash);
			if (count($bits) < 2) {
				return $this->generate_salt();
			} else {
				return $bits[0]; 
			}
		}
	}
	
	private function generate_salt() {
		return sha1(uniqid());
	}
	
	private function hash_password($plaintext) {
		$salt = $this->extract_salt();
		return $salt . '$' . sha1($salt.$plaintext);
	}
}

?>