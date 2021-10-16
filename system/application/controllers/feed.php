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
 * Feed Controller
 *
 * Handles the display of feeds
 *
 * @package		FeedIgnition
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Jim O'Halloran
 */
class Feed extends Controller {
	/**
	 * The number of records per page in the view method.
	 */
	const ITEMS_PER_PAGE = 20;

	/**
	 * Controller constructor.
	 */
	function __construct() {
		parent::Controller();
	}

	
	/**
	 * Index function handles the generation of the home page when this is the 
	 * default controller.  Home page mimicks the feed/view page.
	 */
	function index() {
		$this->view();
	}
	
	/**
	 * Handles the update of feeds.  Retreives a list of feeds from the 
	 * database and stores their items back to the DB. 
	 */
	function update_all() {
		$this->load->library('simplepie');
		$this->simplepie->cache_location = BASEPATH .'cache';
		
		$this->load->library('HTMLPurifier');
		$purifier_config = HTMLPurifier_Config::createDefault();
		$purifier_config->set('Cache', 'SerializerPath', BASEPATH .'cache');
		
		$this->load->model('FeedModel');
		$this->load->model('FeedItemModel');
		
		$feeds = $this->FeedModel->get_feed_update_urls();
		foreach ($feeds as $feed_id => $feed_url) {
			$this->simplepie->set_feed_url($feed_url); 
			$result = $this->simplepie->init();
			if ($result === FALSE) {
				echo $this->simplepie->error();
			} else {
				$items = $this->simplepie->get_items();
				foreach ($items as $item) {
					$this->FeedItemModel->load($feed_id, md5($item->get_id()));

					// Validate the URL using parse_url to make sure XSS can't be 
					// introduced via the permalink element in the feed.
					$permalink = $item->get_permalink();
					$scheme = @parse_url($permalink, PHP_URL_SCHEME);
					// parse_url returns false for seriously malformed URLs
					if ($scheme === FALSE || !in_array($scheme, array('http', 'https', 'ftp'))) { 
						$permalink = '';
					}
					
					$this->FeedItemModel->link = $permalink;				
					$this->FeedItemModel->title = html_entity_decode($item->get_title());
					$this->FeedItemModel->text = $this->htmlpurifier->purify($item->get_content(), $purifier_config);;
					$this->FeedItemModel->save();			
				}
			}
		}
	}
	
	/**
	 * This function displays (with pagination) the feed items stored in the 
	 * database.  The third URL segment is used to supply the pagination 
	 * offset to this method.
	 * 
	 * @param integer The offset from the start of the table.
	 */
	function view($offset = 0) {
		if (is_numeric($offset)) {
			$offset = floor($offset);
		} else {
			$offset = 0;
		}
		$this->load->model('FeedItemModel');
		$data = $this->FeedItemModel->get_items($offset, Feed::ITEMS_PER_PAGE);
		$data['per_page'] = Feed::ITEMS_PER_PAGE;
		$this->load->view('feed/view', $data);
	}
	
}

?>