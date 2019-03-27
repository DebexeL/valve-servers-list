<?php 
	include 'core/init.php';
	include 'includes/overall/header.php'; 
	
	//Reused the newer captcha function from https://github.com/grohsfabian/minecraft-servers-list-lite/
	$captcha = new Captcha();
	
if(empty($_POST) == false) {
	$fields = array('subject', 'name', 'email', 'message');
	foreach($_POST as $key=>$value) {
		if(empty($value) && in_array($key, $fields) == true){
			$errors[] = 'All fields are required';
			break 1;
		}
	}

	$_POST['subject']  = htmlspecialchars($_POST['subject'], ENT_QUOTES);
	$_POST['name']     = htmlspecialchars($_POST['name'], ENT_QUOTES);
	$_POST['email']    = htmlspecialchars($_POST['email'], ENT_QUOTES);
	$_POST['message']  = htmlspecialchars($_POST['message'], ENT_QUOTES);
	
	
	if(!$captcha->is_valid()) {
		$errors[] = 'Captcha is incorrect! Try again.';
	}
	
	
	if(empty($errors) == true) {
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
			$errors[] = 'A valid email address is required';
		}
	}
}
?>
<h2>Contact</h2>
<?php
if(isset($_GET['success']) && empty($_GET['success'])) {
	echo '<font color=\'green\'>Your message has been sent successfully!</font><br>Please allow 24 to 48 hours for a response!';
} else {
	if(empty($_POST) == false && empty($errors) == true){
		mail($settings['contact_email'],  $_POST['subject'], $_POST['message'], 'From: ' . $_POST['name']);
		header('Location:contact.php?success');
	} elseif(empty($errors) == false) {
		echo output_errors($errors);
	}
}
?>
<form action="" method="post">

	<input class="span4" type="text" name="subject" placeholder="Title"/><br />
	<input class="span4" type="text" name="name" placeholder="Your Name"/><br />
	<input class="span4" type="text" name="email" placeholder="Your Email"/><br />
	<textarea class="span4" rows="6" name="message" placeholder="Message"></textarea><br />
	
	<?php $captcha->display(); ?>
		
	<input class="btn btn-primary btn-large span4" type="submit" value="Send mail"/>
</form>
<?php include 'includes/overall/footer.php'; ?>