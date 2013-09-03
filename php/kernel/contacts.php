<?php
require_once('base.php');

class contacts extends base{
	function __construct(){
		parent::__construct();
	}
	function contacts(){
		$html = '<h1>'.$this->lng['contacts'].'</h1>'.$this->lng['contacts text'].'
			<a href="https://siteheart.com/webconsultation/28579?" target="siteheart_sitewindow_28579" onclick="o=window.open;o(\'https://siteheart.com/webconsultation/28579?\', \'siteheart_sitewindow_28579\', \'width=550,height=400,top=30,left=30,resizable=yes\'); return false;"><img src="http://webindicator.siteheart.com/webindicator/spec4?ent=28579&company=28579"	border="0" alt="SiteHeart" /></a>';
		return $html;
	}
}
?>