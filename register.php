<?php 
include 'core/init.php';

//Reused the newer captcha function from https://github.com/grohsfabian/minecraft-servers-list-lite/
$captcha = new Captcha();

logged_in_redirect();

if($settings['register'] == false && logged_in() == false){
	protect_page();
}
include 'includes/overall/header.php'; 
	
if(empty($_POST) == false) {
	$fields = array('username', 'password', 'password_again', 'email', 'name');
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $fields) == true){
			$errors[] = 'All fields are required';
			break 1;
		}
	}

	$_POST['name']     = htmlspecialchars($_POST['name'], ENT_QUOTES);
	$_POST['email']    = htmlspecialchars($_POST['email'], ENT_QUOTES);
	$_POST['username'] = htmlspecialchars($_POST['username'], ENT_QUOTES);
	
	if(!$captcha->is_valid()) {
		$errors[] = 'Captcha is incorrect! Try again.';
	}
	
	if(empty($errors) == true) {
		//Just for fun ^_^
			if($_POST['username'] == "ChuckNorris"){
				$errors[] = "You tried to be Chuck Norris, now you will die !";
			}
		//END OF FUN!
		if(user_exists($_POST['username']) == true) {
			$errors[] = 'Sorry, the user name \'' . $_POST['username'] . '\' is already taken.';
		}
		if(preg_match("/\\s/", $_POST['username']) == true) {
			$errors[] = 'Your user name must not contain any spaces';
		}
		if(strlen($_POST['password']) < 6) {
			$errors[] = 'Password too short, it must be at least 6 characters!';
		}
		
		if($_POST['password'] !== $_POST['password_again']) {
			$errors[] = 'Your passwords need to match!';
		}
		
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
			$errors[] = 'A valid email address is required';
		}
		if(email_exists($_POST['email']) == true) {
			$errors[] = 'Sorry, the email \'' . $_POST['email'] . '\' is already in use';
		}
	}
}
?>
<h2>Register</h2>
<?php
if(isset($_GET['success']) && empty($_GET['success'])) {
	echo '<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>Congratulations!</strong> You\'ve been registered successfully!';
	if($settings['email_confirmation'] == '0') echo 'Please check your email account for activation!';
	echo '</div>';
} else {
	if(empty($_POST) == false && empty($errors) == true){
		if($settings['email_confirmation'] == '1') $active = '0'; else $active = '1';
		$register_data = array(
			'username'     => $_POST['username'],
			'password'     => $_POST['password'],
			'email'        => $_POST['email'],
			'name'         => $_POST['name'],
			'ip'           => $_SERVER['REMOTE_ADDR'],
			'date'         => date('Y.m.d'),
			'email_code'   => md5($_POST['username'] + microtime()),
			'active'	   => $active
		);
		register_user($register_data);
		header('Location: register.php?success');
		exit();
	} elseif(empty($errors) == false) {
		echo output_errors($errors);
	}

?>
<form action="" method="post">
	<label>Username</label>
	<input class="span3" type="text" name="username" />
	
	<label>Password</label>
	<input class="span3" type="password" name="password" />

	<label>Password Again</label>
	<input class="span3" type="password" name="password_again" /> 

	<label>Email</label>
	<input class="span3" type="text" name="email" />

	<label>Nickname</label>
	<input class="span3" type="text" name="name" /><br />
	
	<?php $captcha->display(); ?>
	<input class="btn btn-primary span3" type="submit" value="Register" />

</form>		
<?php
}
include 'includes/overall/footer.php'; 
?>