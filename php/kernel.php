<?php
header('Content-Type: text/html; charset=utf-8');
// ini_set('session.gc_maxlifetime',8*60*60);
// ini_set('session.gc_probability',1);
// ini_set('session.gc_divisor',1);
if(!isset($_SESSION)){
	session_start();
}

require_once('fpdf.php');
// require_once('kernel/base.php');
$kerneldir = scandir(__DIR__.'/kernel');
foreach($kerneldir as $k=>$v){
	if($v!='.'&&$v!='..'){
		$class = substr($v,0,-4);
		$not = array('init');
		if(!in_array($class,$not)){
			// echo 'kernel -> '.$v.' '.$class.'<br />';
			require_once('kernel/'.$v);
		}
	}
}
// echo '<hr />';
// Config
// require_once('config/db.php');

// $kerneldir = scandir(__DIR__.'/kernel');
// foreach($kerneldir as $k=>$v){
	// if($v!='.'&&$v!='..'){
		// include_once('kernel/'.$v);
	// }
// }

//require_once('fontdump.php');
// require_once('kernel/psdreader.php');

class kernel extends base{
	function __construct(){
		// parent::__construct();
		// $this->base->db;
		// $kerneldir = scandir(__DIR__.'/kernel');
		// foreach($kerneldir as $k=>$v){
			// if($v!='.'&&$v!='..'){
				// $class = substr($v,0,-4);
				// if($class!='init'&&$class!='base'){
					// $this->$class = new $class();
				// }
			// }
		// }
	}
}
function f($f='none'){
	// echo $f;
	$k = new kernel;
	$k->start();
	$st = $k->st;
	// if($k->r('home')=='home'){
		// switch($f){
			// case'ftp':
			// case'sites':
			// case'deletedir':
			// case'rchmod':
			// case'ftp_copy':
			// case'ftpsave':
			// case'ftplost':{
				// $n = new ftp();
				// $h = $n->$f();
				// break;
			// }
			// case'clients':
			// case'users':
			// case'posts':{
				// $n = new usr();
				// $h = $n->$f();
				// break;
			// }
			// case'delete':
			// case'addfinanceone':
			// case'edit':
			// case'add':{
				// $n = new add();
				// $h = $n->$f();
				// break;
			// }
			// case'finance':{
				// $n = new fin();
				// $h = $n->$f();
				// break;
			// }
			// case'makeup':{
				// $n = new mkp();
				// $h = $n->mkp();
				// break;
			// }
			// case'settings':{
				// $n = new add();
				// $h = $n->edit('settings');
				// break;
			// }
			// case'projects':
			// case'projectecho':
			// case'project':
			// case'tasks':
			// case'taskstatus':
			// case'task':
			// case'items':
			// case'itemsecho':
			// case'item':
			// case'daysInYear':
			// case'itemtime':
			// case'itemstimed':
			// case'timeline':
			// case'status':
			// case'archive':{
				// $n = new pti();
				// $h = $n->$f();
				// break;
			// }
			// case'pnl':
			// case'faq':
			// case'terms':
			// case'help':
			// case'pdf':
			// case'timeline':
			// case'contacts':{
				// $n = new $f();
				// if($n){
					// $h = $n->$f();
				// }
				// else{
					// $h = $k->denide('Undefined class or function');
				// }
				
				// break;
			// }
			// case'logout':
			// case'registration':{
				// $n = new usr();
				// $h = $n->$f();
				// break;
			// }
			// case'title':
			// case'header':
			// case'demo':
			// case'footer':{
				// $n = new hme();
				// $h = $n->$f();
				// break;
			// }
			// case'hme':
			// case'home':{
				// $n = new hme();
				// $h = $n->hme();
				// break;
			// }
			// case'news':
			// case'nws':{
				// $n = new nws();
				// $h = $n->nws();
				// break;
			// }
			// case'subscribe':{
				// $n = new nws();
				// $h = $n->$f();
				// break;
			// }
			// case'p':{
				// $n = new pti();
				// $h = $n->items($k->r('idin'));
				// break;
			// }
			// default:{
				// if($k->uid>0){//echo $k->st;
					// if($st!=1){$h = $k->$f();}
					// elseif($st==1){$h = $k->denide('User is blocked');}
					// else{$h = $k->denide('System error. Please, concact with us to check this error');}
				// }
				// else{$h = $k->denide();}
				// break;
			// }
		// }
		
		// echo $h;
		// break;
	// }
	if($k->uid>0){
		
		switch($f){
			case'ftp':
			case'sites':
			case'deletedir':
			case'rchmod':
			case'ftp_copy':
			case'ftpsave':
			case'ftplost':{
				$n = new ftp();
				$h = $n->$f();
				break;
			}
			case'clients':
			case'users':
			case'posts':{
				$n = new usr();
				$h = $n->$f();
				break;
			}
			case'delete':
			case'addfinanceone':
			case'edit':
			case'add':{
				$n = new add();
				$h = $n->$f();
				break;
			}
			case'finance':{
				$n = new fin();
				$h = $n->$f();
				break;
			}
			case'makeup':{
				$n = new mkp();
				$h = $n->mkp();
				break;
			}
			case'settings':{
				$n = new add();
				$h = $n->edit('settings');
				break;
			}
			case'projects':
			case'projectecho':
			case'project':
			case'tasks':
			case'taskstatus':
			case'task':
			case'items':
			case'itemsecho':
			case'item':
			case'daysInYear':
			case'itemtime':
			case'itemstimed':
			case'timeline':
			case'status':
			case'archive':{
				$n = new pti();
				$h = $n->$f();
				break;
			}
			case'pnl':
			case'faq':
			case'terms':
			case'help':
			case'pdf':
			case'timeline':
			case'contacts':{
				$n = new $f();
				if($n){
					$h = $n->$f();
				}
				else{
					$h = $k->denide('Undefined class or function');
				}
				
				break;
			}
			case'logout':
			case'registration':{
				$n = new usr();
				$h = $n->$f();
				break;
			}
			case'title':
			case'header':
			case'demo':
			case'footer':{
				$n = new hme();
				$h = $n->$f();
				break;
			}
			case'hme':
			case'home':{
				$n = new hme();
				$h = $n->hme();
				break;
			}
			case'news':
			case'nws':{
				$n = new nws();
				$h = $n->nws();
				break;
			}
			case'subscribe':{
				$n = new nws();
				$h = $n->$f();
				break;
			}
			case'p':{
				$n = new pti();
				$h = $n->items($k->r('idin'));
				break;
			}
			default:{
				if($k->uid>0){//echo $k->st;
					if($st!=1){$h = $k->$f();}
					elseif($st==1){$h = $k->denide('User is blocked');}
					else{$h = $k->denide('System error. Please, concact with us to check this error');}
				}
				else{$h = $k->denide();}
				break;
			}
		}
		
		// echo $h;
		// break;
	}
	else{
		switch($f){
			case'projects':
			case'projectecho':
			case'project':
			case'tasks':
			case'taskstatus':
			case'task':
			case'items':
			case'itemsecho':
			case'item':
			case'daysInYear':
			case'itemtime':
			case'itemstimed':
			case'timeline':
			case'status':
			case'archive':{
				$n = new pti();
				$h = $n->$f();
				break;
			}
			case'pnl':
			case'faq':
			case'terms':
			case'help':
			case'pdf':
			case'timeline':
			case'contacts':{
				$n = new $f();
				if($n){
					$h = $n->$f();
				}
				else{
					$h = $k->denide('Undefined class or function');
				}
				
				break;
			}
			case'logout':
			case'registration':{
				$n = new usr();
				$h = $n->$f();
				break;
			}
			case'title':
			case'header':
			case'demo':
			case'footer':{
				$n = new hme();
				$h = $n->$f();
				break;
			}
			case'hme':
			case'home':{
				$n = new hme();
				$h = $n->hme();
				break;
			}
			case'news':
			case'nws':{
				$n = new nws();
				$h = $n->nws();
				break;
			}
			case'subscribe':{
				$n = new nws();
				$h = $n->$f();
				break;
			}
			case'p':{
				$n = new pti();
				$h = $n->items($k->r('idin'));
				break;
			}
			default:{
				if($k->uid>0){//echo $k->st;
					if($st!=1){$h = $k->$f();}
					elseif($st==1){$h = $k->denide('User is blocked');}
					else{$h = $k->denide('System error. Please, concact with us to check this error');}
				}
				else{$h = $k->denide();}
				break;
			}
		}
	}
	// $st = $k->st;
	
	// else{$h = $k->none();}
	// echo $k->countquery;
	return $h;
}
?>
