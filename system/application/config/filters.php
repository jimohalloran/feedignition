<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Filters configuration
| -------------------------------------------------------------------
|
| Note: The filters will be applied in the order that they are defined
|
| Example configuration:
|
| $filter['auth'] = array('exclude', array('login/*', 'about/*'));
| $filter['cache'] = array('include', array('login/index', 'about/*', 'register/form,rules,privacy'));
|
*/
$filter['perfmon'] = array(
	'include', array('*'), array('warning_time' => 2.00)
);

$filter['auth'] = array(
	'exclude', 	// Apply login filter to all pages except login and support pages.
	array('user/login,logout,signup'),
);
?>