<?php $this->load->view('header', array('title'=>'New User Signup')); ?>
<h1>Signup</h1>

<?php if ($this->validation->error_string != '') {  ?>
	<ul class="error"><?= $this->validation->error_string ?></ul>
<?php } ?>

<form action="?" method="POST">
	<input type="hidden" name="submitted" value="true">
	<p><label for="username">Username:</label><input type="text" name="username" size="10" value="<?=htmlspecialchars($this->validation->username);?>"></p>
	<p><label for="password1">Password:</label><input type="password" name="password1" size="10" value=""></p>
	<p><label for="password2">Confirm Password:</label><input type="password" name="password2" size="10" value=""></p>
	<p><label for="email">Email Address:</label><input type="email" name="email" size="20" value="<?=htmlspecialchars($this->validation->email);?>"></p>
	<p><label for="first_name">First Name:</label><input type="text" name="first_name" size="20" value="<?=htmlspecialchars($this->validation->first_name);?>"></p>
	<p><label for="last_name">Last Name:</label><input type="text" name="last_name" size="20" value="<?=htmlspecialchars($this->validation->last_name);?>"></p>
	<p><input type="submit" value="Register"></p>
</form>

<?php $this->load->view('footer'); ?>