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
 * Feed Item Model Class
 *
 * This class handles loading/saving and fetching Feed Items.
 *
 * @package		FeedIgnition
 * @subpackage	Models
 * @category	Models
 * @author		Jim O'Halloran
 */
class FeedItemModel extends Model {

	private $_id = false;
	public $feed_id;
	public $remote_id;
	public $link;
	public $title;
	public $text; 	
	public $created_time;
	public $updated_time;
	
	/**
	 * Model constructor
	 */
	function __construct() {
		parent::Model();
	}

	
	/**
	 * Resets this instance ready to insert a new record.  Call reset before 
	 * assigning new field values to ensure a new record is inserted into the 
	 * database. 
	 */
	public function reset() {
		$this->_id = false;
		$this->feed_id = 0;
		$this->remote_id = '';
		$this->link = '';
		$this->title = '';
		$this->text = '';
		$this->created_time = localtime();
		$this->updated_time = localtime();		
	}
	
	
	/**
	 * Loads a feed item for editing.  The feed_id and remote_id are used to 
	 * identify the record to retreive.  If the record was not located, the 
	 * model will be reset ready to insert a new record.
	 * 
	 * @param integer The feed id if the record we wish to retreive.
	 * @param string The Unique identified of the feed we wish to retreive.
	 * @return boolean True if the record was found and loaded, false on failure.
	 */
	public function load($feed_id, $remote_id) {
		$rs = $this->db->query('SELECT * FROM items WHERE feed_id=? AND remote_id=?', array($feed_id, $remote_id));
		if ($rs->num_rows() > 0) {
			$row = $rs->row_array();
			$this->_id = $row['id'];
			$this->feed_id = $feed_id;
			$this->remote_id = $remote_id;
			$this->link = $row['link'];
			$this->title = $row['title'];
			$this->text = $row['text'];
			$this->created_time = strtotime($row['created_time']);
			$this->updated_time = strtotime($row['updated_time']);
			return true;		
		} else {
			$this->reset();
			$this->feed_id = $feed_id;
			$this->remote_id = $remote_id;
			return false;
		}	
	}
	
	
	/**
	 * Saves the current record to the database taking care of inserting or 
	 * updating as appropriate.
	 */
	function save() {
		if ($this->_id !== false) {
			$this->db->query('UPDATE items SET link=?, title=?, text=?, updated_time=NOW() WHERE id=?', array($this->link, $this->title, $this->text, $this->_id));
		} else {
			$this->db->query('INSERT INTO items(feed_id, remote_id, link, title, text, created_time, updated_time) VALUES(?, ?, ?, ?, ?, NOW(), NOW())', array($this->feed_id, $this->remote_id, $this->link, $this->title, $this->text));
			$this->_id = $this->db->insert_id();
		}
	}
	
	
	/**
	 * Returns an array containing the items to display.  The array contains 
	 * two elements 'total' indicating the total number of items in the database 
	 * and 'items' which is an array of item records.
	 * 
	 * @param integer The offset to retreive records from (used for pagination).
	 * @param integer The maximum number of records to return (used for pagination).
	 * @return array An array of records.
	 */
	function get_items($offset, $num_per_page) {
		$rs = $this->db->query('SELECT count(*) as total FROM items', array($num_per_page, $offset));
		if ($rs->num_rows() > 0) {
			$row = $rs->row_array();
			$total = $row['total'];
			$rs = $this->db->query('SELECT * FROM items ORDER BY updated_time DESC LIMIT ? OFFSET ?', array($num_per_page, $offset));
			return array('total' => $total, 'items' => $rs->result_array());			
		} else {
			return array('total' => 0, 'items' => array());
		}
	}
	
	
}

?>