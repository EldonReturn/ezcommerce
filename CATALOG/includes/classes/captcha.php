<?php
/*
 * Captcha generation code
*/
define('CPT', 'cpt');

class captcha{
	// Image object, width, height and the lenght of the captcha
	private $im;
	private $im_width;
	private $im_height;
	private $len;
	// Random number, y axis and random color
	private $randnum;
	private $y;
	private $randcolor;
	// Background red/grean/blue color，default color is light gray
	public $red = 238;
	public $green = 238;
	public $blue = 238;
	
	// Number+letters by default
	// 1 2 3 represent lower case, upper case and numbers only
	public $ext_num_type='';
	public $ext_pixel = false; // Disturbance spots
	public $ext_line = false; // Disturbance lines
	public $ext_rand_y = true; // Random y axis
	
	function __construct($len = 4, $im_width = '', $im_height = 25){
		$this->len = $len; $im_width = $len * 15;
		$this->im_width = $im_width;
		$this->im_height= $im_height;
		$this->im = imagecreate($im_width, $im_height);
	}
	// Set the background color, light gray by default
	function set_bgcolor(){
		imagecolorallocate($this->im,$this->red,$this->green,$this->blue);
	}
	// Get a random number
	function get_randnum(){
		$an1 = 'abcdefghijklmnopqrstuvwxyz';
		$an2 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$an3 = '0123456789';
		if($this->ext_num_type == '') $str = $an1.$an2.$an3;
		if($this->ext_num_type == 1) $str = $an1;
		if($this->ext_num_type == 2) $str = $an2;
		if($this->ext_num_type == 3) $str = $an3;
		for($i = 0; $i < $this->len; $i++){
			$start = rand(1,strlen($str) - 1);
			$randnum .= substr($str,$start,1);
		}
		$this->randnum = $randnum;
		$_SESSION[CPT] = strtolower($this->randnum); // Case insensitive
	}
	// Get the y axis of the captcha
	function get_y(){
		if($this->ext_rand_y)
			$this->y = rand(5, $this->im_height / 5);
		else
			$this->y = $this->im_height / 4 ;
	}
	// Get a random number
	function get_randcolor() {
		$this->randcolor = imagecolorallocate($this->im,rand(0,100),rand(0,150),rand(0,200));
	}
	// Add disturbance spots
	function set_ext_pixel(){
		if($this->ext_pixel){
			for($i = 0; $i < 100; $i++){
				$this->get_randcolor();
				imagesetpixel($this->im, rand()%100, rand()%100, $this->randcolor);
			}
		}
	}
	// Add disturbance lines
	function set_ext_line(){
		if($this->ext_line){
			for($j = 0; $j < 2; $j++){
				$rand_x = rand(2, $this->im_width);
				$rand_y = rand(2, $this->im_height);
				$rand_x2 = rand(2, $this->im_width);
				$rand_y2 = rand(2, $this->im_height);
				$this->get_randcolor();
				imageline($this->im, $rand_x, $rand_y, $rand_x2, $rand_y2, $this->randcolor);
			}
		}
	}

	function create(){
		$this->set_bgcolor();
		$this->get_randnum();
		for($i = 0; $i < $this->len; $i++){
			$font = rand(4, 6);
			$x = $i/$this->len * $this->im_width + rand(1, $this->len);
			$this->get_y();
			$this->get_randcolor();
			imagestring($this->im, $font, $x, $this->y, substr($this->randnum, $i ,1), $this->randcolor);
		}
		$this->set_ext_line();
		$this->set_ext_pixel();
		header("content-type:image/png");
		imagepng($this->im);
		imagedestroy($this->im); // Release the image resource
	}
}
?>