<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * InForme
 *
 * Forme Projex's project cost tracking and cashflow forecasting application. 
 *
 * @package		InForme
 * @author		Jim O'Halloran
 * @copyright	Copyright (c) ${year}, Tux IT Services
 * @link		http://www.tuxit.com.au
 * @since		Version 1.0
 * @filesource
 */

/**
 * Debugging Helper Module
 * 
 * Provides functions which assist debugging of the application.
 *
 * @package		InForme
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Jim O'Halloran
 */


	function debug($data, $force_dump = false) {
		if ($force_dump || is_array($data)) {
			log_message('debug', print_r($data, true));
		} else {
			log_message('debug', $data);
		}
	}
?>