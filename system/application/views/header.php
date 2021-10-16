<html>
	<head>
		<title>FeedIgnition<?php 
			if (isset($title)) {
				echo " :: ".$title;
			} ?></title>
	</head>
	<body>
		<?
			$CI = &get_instance();
			$user = $CI->session->userdata('user');
			if ($user !== false) {
				?><div class="loggedin">Logged in as <?=htmlentities($user['first_name'].' '.$user['last_name']); ?> (<a href="<?=site_url('user/logout'); ?>">logout</a>)</div>
			<? }
		 ?>