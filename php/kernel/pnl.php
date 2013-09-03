<?php
require_once('base.php');

class pnl extends base{
	function __construct(){
		parent::__construct();
	}
	function pnl(){
		// print_r($this);
		$html = '';
		$html .= '<div id="top">';
		$fin = new fin();
		if($this->userid>0){
			$html .= '<div class="hired"><span onclick="if(jQuery(this).parent().find(\'div#hiredcontent\').size()>0){jQuery(this).parent().find(\'div#hiredcontent\').remove();jQuery(this).removeClass(\'active\');}else{jQuery(this).parent().append(\'<div></div>\').find(\'div\').attr(\'id\',\'hiredcontent\');Page(\'users&act=hired\',e(\'hiredcontent\'));jQuery(this).addClass(\'active\');}">hired</span></div>';
		}
		$html .= '
				'.(isset($this->uid)&&$this->uid>0?'
				<div class="add">
					<a href="javascript:void(0)" class="btn f-l bg-green" onclick="Page(\'add&act='.strtolower($this->r('action')).'\',\'0\',\'\');"><span class="icon0 icon-plusthick" title="'.$this->lng['tasks'].'"></span><i>'.$this->lng['Add'].'</i></a>
					<span class="version">v.'.$this->version.'</span>
				</div>
				':'
				').'
				<ul class="top">
					<li class="logo"><a href="'.$this->site.'home.html" class="btn'.($this->action=='home'?' active':'').'" title="Web - Help Me!"><span></span></a></li>
					'.(isset($this->uid)&&$this->uid>0?'
					<li class="projects"><a href="'.$this->site.'projects.html" class="btn'.($this->action=='projects'?' active':'').'"><span class="iconblue icon-folder-collapsed" title="'.$this->lng['Projects'].'"></span><i>'.$this->lng['Projects'].'</i></a></li>
					<li class="clients"><a href="'.$this->site.'clients.html" class="btn'.($this->action=='clients'?' active':'').'"><span class="icongreen icon-person" title="'.$this->lng['clients and contractors'].'"></span><i>'.$this->lng['Clients and contractors'].'</i></a></li>
					<li class="finance"><a href="'.$this->site.'finance.html" class="btn'.($this->action=='finance'?' active':'').'"><span class="icon0 icon-dollar" title="'.$this->lng['finance'].'"></span><i>'.$this->lng['Finance'].'</i></a></li>
					<li class="makeup new d-n"><a href="'.$this->site.'makeup.html" class="btn'.($this->action=='makeup'?' active':'').'"><span class="iconred icon-scissors" title="'.$this->lng['Makeup'].'"></span><i>'.$this->lng['Makeup'].'</i></a></li>
					'.(isset($this->uuser)&&$this->uuser==0?'
					<li class="users new"><a href="'.$this->site.'users.html" class="btn'.($this->action=='users'?' active':'').'"><span class="icon0 icon-contact" title="'.$this->lng['Users'].'"></span><i>'.$this->lng['Users'].'</i></a></li>
					':'').'
					':'
					<li class="login d-n"><a href="javascript:void(0)" class="btn bc-blue brc-blue" onclick="jQuery(\'.home\').slideToggle(500);"><span class="iconf icon-power" title="'.$this->lng['Login'].'"></span><i>'.$this->lng['Login'].'</i></a></li>
					').'
					'.(isset($this->uid)&&$this->uid>0?'
					<li class="settings"><a href="'.$this->site.'settings.html" class="btn'.($this->action=='settings'?' active':'').'"><span class="icon5b icon-gear" title="'.$this->lng['Settings'].'"></span><i>'.$this->lng['Settings'].'</i></a></li>
					'.($this->os=='vk'?'':'
					'.(isset($this->uid)&&$this->uid>0?'
					<li class="logout"><a href="'.$this->site.'logout.html" class="btn bc-red brc-lred"><span class="iconf icon-power" title="'.$this->lng['Logout'].'"></span><i>'.$this->lng['Logout'].'</i></a></li>
					':'').'
					').'
					'.($this->uid==818?'
					<li class="d-n"><a href="'.$this->site.'archive.html" class="btn'.($this->action=='archive'?' active':'').'"><span class="icon0 icon-folder-collapsed" title="'.$this->lng['Archive'].'"></span><i>'.$this->lng['Archive'].'</i></a></li>
					<li class="sites d-n"><a href="'.$this->site.'sites.html" class="btn'.($this->action=='sites'?' active':'').'"><span class="iconrose icon-script" title="'.$this->lng['Sites'].'"></span><i>'.$this->lng['Sites'].'</i></a></li>
					':'')
					:'').'
				</ul>
				'.(isset($this->uid)&&$this->uid>0?'
				<div class="balance">'.$fin->finance('balance').'</div>
				
				'.($this->st==1?$this->info('info','<strong>Account blocked!</strong> Limited use of system resources. If you think your account has been blocked for no reason, please contact the system administration <a href="'.$this->site.'contacts.html">any way</a> to resolve the issue.'):''):'
					').'';
		if(isset($this->uid)&&$this->uid>0&&($this->action=='home'||$this->action=='projects'||$this->action=='')){
			$html .= '<div id="itemstimed"></div>';
		}
		$html .= '<div class="files"></div>';
		$html .= '</div>';
		if($this->uid>0){}
		else{
			/* $usr = new usr();
			$html .= '<div class="home" style="display:none;">
					<div class="welcome">';
			// $w = 50;
			// $color = array('f00','0f0','00f','fff','ff0','f0f','0ff');
			// for($i=0;$i<(10000/$w);$i++){
				// $html .= '<div style="display:block;float:left;width:'.$w.'px;height:'.$w.'px;background:#'.$color[rand(0,count($color)-1)].';z-index:0;border-radius:'.($w/10).'px;margin:0 1px 1px 0;"></div>';
			// }
			$html .= '<img class="logo" src="'.$this->site.'img/dlogo.png" />
				<div class="free"></div>
				<div class="hmelogin">
					'.$usr->login().'
				</div>';
			$html .= '</div>
				</div>'; */
		}
		return $html;
	}
}
?>