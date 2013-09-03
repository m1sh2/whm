<?php
require_once('base.php');

class help extends base{
	function __construct(){
		parent::__construct();
	}
	function help(){
		$html = '';
		$html .= '<h1>'.$this->lng['Help'].'</h1>';
		$html .= ''.$this->lng['Help text'].'';
		return $html;
	}
}
?>