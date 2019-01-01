<?php

class Captcha {

	/* Configuration Variables */
	public $image_width = 120;
	public $image_height = 30;
	public $text_length = 6;
	public $lines = 6;
	public $background_color = [255, 255, 255];
	public $text_color = [0, 0, 0];
	public $lines_color = [63, 63, 63];
	private $captcha_location = 'pages/captcha.php';

	function __construct() {
		// :)
	}


	function is_valid() {
		return ($_POST['captcha'] == $_SESSION['captcha']);
	}

	function display() {
		echo '<img src="' . $this->captcha_location . '" id="captcha" /><input type="text" name="captcha" class="form-control" />';
	}

	/* Generating the captcha image */
	function process() {

		/* Initialize the image */
		session_start();
		header('Content-type: image/png');

		/* Generate the text */
		$text = null;

		for($i = 1; $i <= $this->text_length; $i++) $text .= mt_rand(1, 9) . ' ';

		/* Store the generated text in Sessions */
		$_SESSION['captcha'] = str_replace(' ', '', $text);

		/* Create the image */
 		$image = imagecreate($this->image_width, $this->image_height);

 		/* Define the background color */
 		imagecolorallocate($image, $this->background_color[0], $this->background_color[1], $this->background_color[2]);

 		/* Start writing the text */
 		imagestring($image, 5, 7, 7, $text, imagecolorallocate($image, $this->text_color[0], $this->text_color[1], $this->text_color[2]));

 		/* Generate lines */
 		for($i = 1; $i <= $this->lines; $i++) imageline($image, mt_rand(1, $this->image_width), mt_rand(1, $this->image_height), mt_rand(1, $this->image_width), mt_rand(1, $this->image_height), imagecolorallocate($image, $this->lines_color[0], $this->lines_color[1], $this->lines_color[2]));

 		/* Output the image */
		imagepng($image, null, 9);

	}


}


?>
