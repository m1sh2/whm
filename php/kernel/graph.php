<?php
require_once('base.php');

class graph extends base{
	function __construct(){
		parent::__construct();
	}
	function graph($act='',$a=array()){
		$act = $act==''?$this->r('act'):$act;
		switch($act){
			case'financeanalytics':{
				$im = @ImageCreate (100, 200);
				$background_color = ImageColorAllocate ($im, 234, 234, 234);
				$text_color = ImageColorAllocate ($im, 233, 14, 91);
				imageline ($im,0,0,50,100,$text_color);
				header("Content-type: image/png");
				imagepng($im);
			}
		}
	}
}
?>