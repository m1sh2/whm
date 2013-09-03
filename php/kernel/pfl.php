<?php
require_once('base.php');

class pfl extends base{
	function __construct(){
		parent::__construct();
	}
	function pfl(){
			$html = '<h1>Портфолио</h1>
				<ul class="sites">';
			$all = $this->q("SELECT * FROM jos_whm WHERE type='portfolio' AND user='".$this->uid."' ORDER BY id DESC");
			while($a = mysql_fetch_assoc($all)){
			$html .='
				<li class="">
					<table cellpadding="10" cellspacing="0" border="0">
						<tr>
							<td width="250">
								<a href="http://'.$a['id'].'.web-help-me.com" target="_blank">'.$a['id'].'.web-help-me.com</a>
							</td>
							<td width="200" class="fs-12">
								<span class="js" onclick="jQuery(\'#data'.$a['id'].'\').toggle(250)">доступы</span>
								<div id="data'.$a['id'].'" style="display:none;">'.$a['contacts'].'</div>
							</td>
							
						</tr>
					</table>
				</li>';
			}
			$html .= '</ul>';
		return $html;
	}
}
?>