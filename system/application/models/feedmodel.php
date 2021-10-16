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
 * Feed Model
 *
 * Model class handling all operations on feeds
 *
 * @package		Feedignition
 * @subpackage	Models
 * @category	Models
 * @author		Jim O'Halloran
 */
class FeedModel extends Model {

	function __construct()
	{
		parent::Model();
	}

	
	/**
	 * Returns an array of feed URLs which should be updated.  The array is 
	 * indexed using the feed's database ID number.
	 * 
	 * @return array An empty array if no ffeds are available, otherwise an id indexed array of urls.
	 */
	function get_feed_update_urls() {
		$rs = $this->db->query('SELECT id, feed_url FROM feeds');
		$feeds = array();
		if ($rs->num_rows() > 0) {
			foreach ($rs->result_array() as $row) {
				$feed[$row['id']] = $row['feed_url'];
			}
		}
		return $feed;
	}
	
	
}

?>