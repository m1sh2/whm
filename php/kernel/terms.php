<?php
require_once('base.php');

class terms extends base{
	function __construct(){
		parent::__construct();
	}
	function terms(){
		$html = '';
		$html .= '<h1>'.$this->lng['Terms'].'</h1>';
		$html .= ''.$this->lng['Terms text'].'';
		return $html;
	}
}
?>