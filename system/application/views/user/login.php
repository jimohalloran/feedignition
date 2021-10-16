<?php $this->load->view('header', array('title'=>'Login')); ?>
<h1>Signup</h1>

<?php if ($this->validation->error_string != '') {  ?>
	<ul class="error"><?= $this->validation->error_string ?></ul>
<?php } ?>

<?php if ($auth_fail) {  ?>
	<p class="error">Incorrect username or password.</p>
<?php } else {?>
	<p>Please enter your username and password to login.</p>
<?php } ?>

<form action="?" method="POST">
	<input type="hidden" name="submitted" value="true">
	<p><label for="username">Username:</label><input type="text" name="username" size="10"></p>
	<p><label for="password">Password:</label><input type="password" name="password" size="10"></p>
	<p><input type="submit" value="Login"></p>
</form>

<?php $this->load->view('footer'); ?>