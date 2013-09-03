<?php
require_once('base.php');

class hme extends base{
	function __construct(){
		parent::__construct();
	}
	function hme(){
		$html = '';
		// print_r($this);
		// echo '<pre>';print_r($this);echo '</pre>';
		// $html .= '<p>'.$this->uid.'</p>';
		if((isset($this->uid)&&$this->uid>0)||($this->r('chrome')=='vk'&&$this->r('is_app_user')==1)){
			$pti = new pti();
			$html .= ''.$pti->projects().'';
			// $html .= '<h1>'.$this->lng['Tasks'].'</h1>';
			// $html .= ''.$pti->tasks(0).'';
			// $html .= ''.$this->finance().'';
			// $html .= '';
		}
		else{
			$usr = new usr();
			$html .= '<div class="home">
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
				</div>';
		}
		
		// $html .= '<h1>'.$this->lng['Tasks'].'</h1>';
		// $html .= ''.$pti->tasks('home').'';
		// $html .= ''.$this->finance().'';
		$html .= '';
		return $html;
	}
	function title($act=''){
		$html = '';
		// $titlearray = array(
			// 'home'=>$this->lng['home'],
			// 'domains'=>$this->lng['check domains'],
			// 'news'=>$this->lng['news'],
			// 'projects'=>$this->lng['projects'],
			// 'tasks'=>$this->lng['tasks'],
			// 'task'=>$this->lng['task'],
			// 'finance'=>$this->lng['finance'],
			// 'faq'=>$this->lng['faq'],
			// 'contacts'=>$this->lng['contacts'],
			// 'test'=>$this->lng['testing'],
			// 'users'=>$this->lng['users'],
			// 'designs'=>$this->lng['designs'],
			// 'sites'=>$this->lng['sites'],
			// 'portfolio'=>$this->lng['portfolio'],
			// 'orders'=>$this->lng['orders'],
		// );
		$html = isset($this->lng[$act])?$this->lng[$act].' - ':'Web - Help Me! - ';
		return $html;
	}
	function header(){
		$html = '';
		$html .= '
			<script language="JavaScript" type="text/javascript">
			var chrm = \''.$_SESSION['chrome'].'\';
			// if(top!=self){
				// window.location = \''.$this->site.'?chrome=0\';
				// alert(1);
			// }
			if(chrm==\'1\'){
				window.location = \''.$this->site.'?chrome=0\';
				// alert(2);
			}
			</script>
			<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<meta name="yandex-verification" content="6b8e18e6e8a78547" />
			<meta name="webmoney.attestation.label" content="webmoney attestation label#CC5A1D62-2ABF-402B-BC81-1EBCCC6E3821" />
			<meta http-equiv="PRAGMA" content="NO-CACHE" />
			<meta http-equiv="CACHE-CONTROL" content="NO-CACHE" />
			<title>'.$this->title($this->action).'WHM</title>
			<link href="'.$this->site.'img/logo2.ico" rel="shortcut icon" />
			<link href="'.$this->site.'css/main.css" type="text/css" rel="stylesheet" />
			<link href="'.$this->site.'css/style.css" type="text/css" rel="stylesheet" />
			<style>
			'.(
				$this->os=='vk'?
					'body{width:607px;overflow:hidden;}#page{overflow:auto;height:500px;}'
					:
					($this->os=='0'?
						'body{}#page{}'
						:
						''
					)
				).'
			</style>
			<link href="'.$this->site.'css/calendar.css" type="text/css" rel="stylesheet" />
			<link href="'.$this->site.'css/jquery.fancybox-1.3.4.css" type="text/css" rel="stylesheet" />
			<link href="'.$this->site.'css/ui/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet" />
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/jquery-2.0.1.min.js"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/jquery-migrate-1.2.1.min.js"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/jquery-ui-1.10.3.custom.min.js"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/jquery.fancybox-1.3.4.pack.js"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/jquery.countdown.js"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/jquery.countdown-ru.js"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/jquery.masonry.min.js"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/jquery.sparkline.min.js"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/ace/ace.js" charset="utf-8"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/ace/mode-javascript.js" charset="utf-8"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/ace/mode-css.js" charset="utf-8"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/ace/mode-html.js" charset="utf-8"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/ace/mode-php.js" charset="utf-8"></script>
			<script language="JavaScript" type="text/javascript">
			jQuery.noConflict();
			</script>
			<script src="http://vk.com/js/api/xd_connection.js?2" type="text/javascript"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/js.js" charset="utf-8"></script>
			<script language="JavaScript" type="text/javascript" src="'.$this->site.'js/md5.js" charset="utf-8"></script>
			<script language="JavaScript" type="text/javascript">
			window.siteurl = "'.$this->site.'";
			// setCookie(\'siteurl\',\''.$this->site.'\',14400);
			</script>
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript">
			// google.load("visualization", "1", {packages:["corechart"]});
			//google.setOnLoadCallback(drawChart);
			</script>';
		return $html;
	}
	function footer(){
		$html = '';
		$html .= '<div id="footer">';
		// $html .= $this->lng['projects'].'112';
		// print_r($this->lng);
		$html .= '&copy 2011-'.date('Y').'. WHM v.'.$this->version.'.';
		$html .= '
			<ul>
				<li><a href="'.$this->site.'news.html" class="'.($this->action=='news'?' active':'').'">'.$this->lng['News'].'</a></li>
				<li><a href="'.$this->site.'help.html" class="'.($this->action=='help'?' active':'').'">'.$this->lng['Help'].'</a></li>
				<li><a href="'.$this->site.'contacts.html" class="'.($this->action=='contacts'?' active':'').'">'.$this->lng['Contacts'].'</a></li>
				<li><a href="'.$this->site.'subscribe.html" class="'.($this->action=='subscribe'?' active':'').'">'.$this->lng['Subscribe'].'</a></li>
				<li><a href="'.$this->site.'terms.html" class="'.($this->action=='terms'?' active':'').'">'.$this->lng['Terms'].'</a></li>
			</ul>';
		$html .= '</div>';
		return $html;
	}
}
?>