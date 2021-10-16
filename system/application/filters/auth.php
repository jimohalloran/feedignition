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
 * Authentication filter
 * 
 * Ensures user is logged in when required.
 *
 * @package		InForme
 * @subpackage	Filters
 * @category	Filters
 * @author		Jim O'Halloran
 */
class Auth_filter extends Filter {
    
		/**
     * Ensures a valid user session exists, or redirects to the login page.
     */
		function before() {
      if (!function_exists('get_instance')) return "Can't get CI instance";
      $CI= &get_instance();

      $success = false;
      
	    $user= $CI->session->userdata('user');
	    if ($user === false) {
	    	debug('Redirecting to login, no user');
	    	redirect("/user/login");
	    }
    }
    
    /**
     * Not used for authentication.
     */
    function after() {
    	
    }
}
?>