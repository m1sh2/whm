<?php
// session_start();
// echo realpath (dirname(__FILE__));
// define("DS","/",true);
// echo substr(str_replace('\\', '/',realpath(dirname(__FILE__))),0,-7).DS.'config'.DS.'config.php';
// include_once(str_replace('\\', '/',realpath(dirname(__FILE__))).DS.'base.php');
include_once(substr(__DIR__,0,-7).'/config/config.php');
// Kernel
// require_once('init.php');
// print_r($_SESSION);
// echo session_id();
class base extends config{
	var $db;
	var $uid;
	var $ulogin;
	var $uem;
	var $uval;
	var $users;
	var $userid;
	var $worker;
	var $utype;
	var $prjct1;
	var $action;
	var $udatelast;
	// var $site;
	var $forgotpasscode;
	var $forgotemail;
	var $at;
	var $typename;
	var $lang = 'en';
	var $display;
	var $lng;
	var $lang_array = array();
	var $lang_array2 = array();
	var $os = 0;
	var $countquery = 0;
	var $st = 0;
	var $version = '5beta';
	var $val = array();
	var $valcost = array();
	var $o = array();
	
	function __construct($act=''){
		$this->db = $this->DBC($this->dbhost,$this->dbname,$this->dbuser,$this->dbpass);
		
		switch($act){
			case'url':{
				return $this->site;
				break;
			}
			default:{
				// $mac = $this->getMac();
				// $mac = trim($mac);
				// echo $mac;
				// if(!isset($this->ses('id'))||$this->ses('id')==''){setcookie('id','0',time()+360000);}
				
				// echo '-'.$this->ses('sesid').'-';
				// echo $_GET['PHPSESSID'];
				// echo session_id();
				// echo $this->ses("PHPSESSID");
				// print_r($_REQUEST);
				// echo $this->r('viewer_id');
				
				if($this->r('api_id')=='3775835'){
					$this->os = 'vk';
				}
				elseif($this->r('chrome')=='1'){
					$this->os = 'chrome';
				}
				else{
					$this->os = 'else';
				}
				$languages = $this->q("SELECT * FROM languages WHERE 1");
				while($l = mysql_fetch_assoc($languages)){
					$this->lang_array[$l['code']] = $l['id'];
					$this->lang_array2[$l['id']] = $l['code'];
				}
				if($this->os=='vk'){
					$html = '';
					
					// echo '<pre>';print_r($response);echo '</pre>';
					// echo $response['response'][0]['first_name'];
					if(mysql_num_rows($this->q("SELECT * FROM users WHERE email='".$this->r('viewer_id')."@vk.com'"))>0){
						
					}
					else{
						$response = json_decode($this->r('api_result'), true);
						switch($this->r('language')){
							default:
							case'3':{
								$l = 'en';
								break;
							}
							case'0':{
								$l = 'ru';
								break;
							}
						}
						
						$this->q("INSERT INTO users (
							email,
							login,
							password,
							type,
							date,
							laid
							) VALUES (
							'".$this->r('viewer_id')."@vk.com',
							'".$response['response'][0]['first_name'].' '.$response['response'][0]['last_name']."',
							'".md5(md5($this->r('viewer_id')))."',
							'user',
							'".date('Y.m.d H:i:s')."',
							'".$this->lang_array[$l]."'
							)");
						// $_REQUEST = array();
						// $text = "Our congratulations!\n";
						// $text .= "You have successfully registered in the system WEB - HELP ME!\n";
						// $text .= "Your login details:\n";
						// $text .= "Login: ".$this->r('email')."\n";
						// $text .= "Password: ".$this->r('password');
						// mail($this->r('email'),'WHM - Registration',$text,"From: no-reply@web-help-me.com\r\n");
						// $html = $this->login('registered');
					}
					$user = $this->q("SELECT * FROM users WHERE email='".$this->r('viewer_id')."@vk.com'");
					$u = mysql_fetch_assoc($user);
					// print_r($u);
					if($u['sid']==0){
						
						if(mysql_num_rows($this->q("SELECT * FROM cache WHERE sesid='".session_id()."'"))==0){
							
							$this->q("UPDATE users SET datelastlogin='".date('Y-m-d H:i:s')."' WHERE id='".$u['id']."'");
							$params = array(
								'uid'=>$u['id'],
								'ulogin'=>$u['login'],
								'uem'=>$u['email'],
								'users'=>$u['cid'],
								'userid'=>$u['uid'],
								'worker'=>$u['uid'],
								'prjct1'=>'all',
								'udatelast'=>$u['datelastlogin'],
								'fyear'=>'',
								'fmonth'=>'',
								'fday'=>'',
								'toyear'=>'',
								'tomonth'=>'',
								'today'=>'',
								'ffnoproj'=>'',
								'ffproj'=>'',
								'ffin'=>'',
								'ffout'=>'',
								'uval'=>($u['vid']==0?768:$u['vid']),
								'uuser'=>$u['uid'],
								'uusers'=>$u['cid'],
								'language'=>$u['laid'],
								'version'=>$this->version,
								'os'=>'vk'
							);
							$s = explode('|',$u['settings']);
							$sets = array();
							$sets['dpclosed'] = 0;
							$sets['dparchive'] = 0;
							$sets['display'] = 800;
							if(count($s)>1){
								foreach($s as $s2){
									$s3 = explode(':',$s2);
									// if(array_key_exists($s3[0],$sets)){
										$sets[$s3[0]] = $s3[1];
									// }
									
								}
							}
							foreach($sets as $k=>$v){
								$params[$k] = $v;
							}
							clearstatcache();
							$this->q("INSERT INTO cache (params,sesid) VALUES ('".(str_replace('=','[=]',str_replace('&','[-s-]',http_build_query($params))))."','".session_id()."')");
							$this->setses('sesid',mysql_insert_id());
						}
						else{
							
						}
						
						//$form = $this->lnk($this->site);
						// echo $this->ses('sesid').' 222';
					}
					else{
						$error = $this->info('alert','<strong>Error!</strong> User is bloked');
					}
				}
				// echo session_id().' 333 '.$this->ses('sesid');
				if(mysql_num_rows($this->q("SELECT * FROM cache WHERE `sesid`='".session_id()."'"))>0){
					// echo 'ok';
					$cache = mysql_fetch_assoc($this->q("SELECT * FROM cache WHERE sesid='".session_id()."' ORDER BY id DESC LIMIT 1"));
					// echo $cache['params'].' '.$cache['id'];
					$cache = explode('[-s-]',$cache['params']);
					foreach($cache as $c){
						$c1 = explode('[=]',$c);
						$this->$c1[0] = $c1[1];
					}
					// print_r($this);
					$st = mysql_fetch_assoc($this->q("SELECT sid,laid FROM users WHERE id='".$this->uid."'"));
					// echo $st['status'];
					$this->st = $st['sid'];
					// $this->uid = $c['id'];
					// $this->ulogin = $c['login_user'];
					// $this->uem = $c['email_user'];
					// $this->utype = $u['type_user'];
					// $this->prjct1 = 'all';
					// $this->udatelast = $u['datelast'];
					// print_r($this);
					$this->lang = $st['language']==''?'en':$this->lang_array2[$st['laid']];
					if($this->lang==''){
						$this->lang = 'en';
						$this->setses('lang',$this->lang);
					}
					// include_once(substr(__DIR__,0,-10).'/lang/'.$this->lang.'.php');
					
					// echo '<pre>';print_r($this->lng);echo '</pre>';
				}
				$this->at = $this->r('action')!=''?explode('-',$this->r('action')):array();
				$this->action = count($this->at)>0&&$this->at[0]!==''?$this->at[0]:'home';//echo $this->ses('session');
				// $this->siteurl = $siteurl;
				break;
			}
		}
		$lng = new lang($this->lang);
		$this->lng = $lng->lang($this->lang);
		// echo '<pre>';print_r($this);echo '</pre>';
		$val = $this->q("SELECT id,name,cost FROM valute WHERE 1 ORDER BY name");
		while($v = mysql_fetch_assoc($val)){
			$this->val[$v['id']] = $v['name'];
			$this->valcost[$v['id']] = $v['cost'];
		}
		$operation = $this->q("SELECT id,name FROM operations WHERE 1 ORDER BY name");
		while($o = mysql_fetch_assoc($operation)){
			$this->o[$o['id']] = $o['name'];
			// $this->valcost[$v['id']] = $v['cost'];
		}
		$this->uid = $this->uid==2422?818:$this->uid;
		// print_r($this->lng);
	}
	function event(){
		
	}
	function DBC($host,$base,$name,$pass){
		$data_base = mysql_connect ($host,$name,$pass);
		if (!$data_base) {
			die('Could not connect: ' . mysql_error());
		}
		$db_selected = mysql_select_db ($base,$data_base);
		if (!$db_selected) {
			die ('Can\'t use foo : ' . mysql_error());
		}
		mysql_query("SET NAMES utf8");
		mysql_query("SET character_set_client = utf8");
		mysql_query("SET character_set_connection = utf8");
		mysql_query("SET character_set_results = utf8");
		return $data_base;
	}
	function start($act=''){
		$this->db = $this->DBC($this->dbhost,$this->dbname,$this->dbuser,$this->dbpass);
		// $this->lang = $this->ses('lang')!=false?$this->ses('lang'):'en';
		// if($this->lang==''){
			// $this->lang = 'en';
			// $this->setses('lang',$this->lang);
		// }
		// include_once(substr(__DIR__,0,-10).'/lang/'.$this->lang.'.php');
		// $lng = new lang($this->lang);
		// $this->lng = $lng->lang($this->lang);
		// echo '<pre>';print_r($this->lng);echo '</pre>';
		switch($act){
			case'url':{
				return $this->site;
				break;
			}
			default:{
				// $mac = $this->getMac();
				// $mac = trim($mac);
				// echo $mac;
				// if(!isset($this->ses('id'))||$this->ses('id')==''){setcookie('id','0',time()+360000);}
				
				// echo '-'.$this->ses('sesid').'-';
				// echo $_GET['PHPSESSID'];
				// echo session_id();
				// echo $this->ses("PHPSESSID");
				if(mysql_num_rows($this->q("SELECT * FROM cache WHERE `sesid`='".session_id()."'"))>0){
					// echo 'ok';
					$cache = mysql_fetch_assoc($this->q("SELECT * FROM cache WHERE sesid='".session_id()."' ORDER BY id DESC LIMIT 1"));
					// echo $cache['params'].' '.$cache['id'];
					$cache = explode('[-s-]',$cache['params']);
					foreach($cache as $c){
						$c1 = explode('[=]',$c);
						$this->$c1[0] = $c1[1];
					}
					// print_r($this);
					$st = mysql_fetch_assoc($this->q("SELECT sid FROM users WHERE id='".$this->uid."'"));
					// echo $st['status'];
					$this->st = $st['sid'];
					// $this->uid = $c['id'];
					// $this->ulogin = $c['login_user'];
					// $this->uem = $c['email_user'];
					// $this->utype = $u['type_user'];
					// $this->prjct1 = 'all';
					// $this->udatelast = $u['datelast'];
					// print_r($this);
				}
				$this->at = $this->r('action')!=''?explode('-',$this->r('action')):array();
				$this->action = count($this->at)>0&&$this->at[0]!==''?$this->at[0]:'home';//echo $this->ses('session');
				// $this->siteurl = $siteurl;
				break;
			}
		}
		// echo '<pre>';print_r($this);echo '</pre>';
	}
	function pi(){
		$html = '';
		$html .= phpinfo();
		return $html;
	}
	function i(){
		$html = '<h1>File</h1>';
		$file = explode('-',$this->r('idin'));
		$fquery = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE id='".$file[0]."'"));
		switch($fquery['level']){
			case'img':{
				$html .= '<img src="'.$this->siteurl.'/1or/files/img/'.$file[0].'.'.$file[1].'" alt="" style="max-width:700px;" />';
				break;
			}
			default:{
				$html .= '<a href="'.$this->siteurl.'/'.$fquery['name'].'">Download</a>';
				break;
			}
		}
		
		// print_r($_REQUEST);
		
		return $html;
	}
	function q($query){
		$html = mysql_query($query) or die(mysql_error());
		$this->countquery++;
		// echo ' '.$this->countquery;
		return $html;
	}
	function email($email,$title,$text){
		$to  = $email;
		$subject = 'WHM: '.$title;
		// $text2 = explode("/n",$text);
		// $text = '';
		// foreach($text2 as $t){
			// $text = '<p>'.$t.'</p>';
		// }
		$b = md5(date('Y-m-d H:i:s'));
		$message = "
			<html>
				<head>
					<title>Web - Help Me!</title>
				</head>
				<body>
					<h1>Web - Help Me!</h1>
					$text
					<hr />
					<table>
						<tr>
							<td><a href='".$this->site."home.html'>Home</a></td>
							<td><a href='".$this->site."login.html'>Log in</a></td>
							<td><a href='".$this->site."registration.html'>Registration</a></td>
							<td><a href='".$this->site."contacts.html'>Contacts</a></td>
							<td><a href='".$this->site."news.html'>News</a></td>
							<td><a href='".$this->site."faq.html'>FAQ</a></td>
						</tr>
					</table>
					<p>© 2011-".date('Y')." WHM - easy control</p>
				</body>
			</html>";
		
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; boundary=\"".$b."\"\r\n";
		$headers .= "From: no-reply@whm.asdat.biz\r\n";
		$headers .= "Reply-To: no-reply@whm.asdat.biz\r\n";
		// $headers .= "X-Mailer: PHP/".phpversion()."\r\n";
		// mail($to,$subject,$text,);
		mail($to,$subject,$message,$headers);
	}
	function lnk($url='',$time=0){
		$html = '<img alt="loading..." src="img/ajax-loader.gif" />';
		$html .= '<script language="JavaScript" type="text/javascript">window.location = \''.$url.'\';</script>';
		return $html;
	}
	function none(){
		$html = '<h1 class="ta-c bg-alert c-f00 fw-b fs-40" style="text-shadow:0px 1px #fff;padding:30px;border-radius:0 0 10px 10px;'.($this->r('num')==''?'margin:0 -10px -10px;':'').'"><span style="">Unavailable area!</h1>';
		if($this->r('num')>0){
			$html .= '<table border="0" cellspacing="0" cellpadding="10" width="100%">
					<tr>
						<td align="center">
							<input type="button" name="button" value="Close" onclick="Remove(e(\'messwindow'.$this->r('num').'\'))" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
						</td>
					</tr>
				</table>';
		}
		return $html;
	}
	function denide($txt=''){
		$user = new usr();
		$html = '<div class="denide">'.($txt==''?$user->login():'<h1>'.$txt.'</h1>').'</div>';
		return $html;
	}
	function r($request){$html = isset($_REQUEST[$request])?$_REQUEST[$request]:'';return $html;}
	function ses($id){$html = !isset($_SESSION[$id])?false:$_SESSION[$id];return $html;}
	function setses($id,$value){$_SESSION[$id] = $value;}
	function info($act,$message,$position='absolute'){
		switch($act){
			default:
			case'info':{
				$color = 'lblue';
				break;
			}
			case'alert':{
				$color = 'red';
				break;
			}
		}
		switch($position){
			case'fixed':{
				$top = 'top:50px;';
				break;
			}
			default:
			case'absolute':{
				$top = '';
				break;
			}
		}
		$html = '<div class="info" style="position:'.$position.';'.$top.';">
				<span class="close" onclick="jQuery(this).parent().remove();"><span class="icon3 icon-close" title="Close"></span></span>
				<div class="icn icon'.$color.' icon-'.$act.'"></div>
				<div class="txt">'.$message.'</div>
			</div>';
		return $html;
	}
	function p(){
		// print_r($_REQUEST);
		$id = $this->r('idin');
		$ob = mysql_fetch_assoc($this->q("SELECT type,name,task,project FROM jos_whm WHERE id='".$id."'"));
		$html = '';
		switch($ob['type']){
			case'task':	{
				// echo '<div style="position:fixed;z-index:1000001;left:0;right:0;bottom:0;top:0;width:100%;height:100%;"></div>';
				// $this->lnk($this->siteurl.'pdftask.php?id='.$this->r('id'));
				$project = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm WHERE id='".$ob['project']."'"));
				$all = $this->q("SELECT i.*,t.user AS tuser FROM jos_whm AS i RIGHT JOIN jos_whm AS t ON t.id=".$id." WHERE i.task='".$id."' AND i.type='item' AND i.pid='0' ORDER BY i.id ASC");
				$time = mysql_fetch_assoc($this->q("SELECT SUM(i.time) AS itime FROM jos_whm AS i WHERE i.task='".$id."' AND i.type='item' ORDER BY i.id ASC"));
				$status = $this->taskstatus($id);
				$html .= '<h1>'.$project['name'].'</h1>';
				$html .= '<table cellspacing="0" cellpadding="5" border="0" width="100%">';
				$html .= '<tr>';
				$html .= '<td colspan="10"><h2>'.$ob['name'].'</h2></td>';
				$html .= '<td align="right" width="70">Progress:<br /><b class="fs-20" id="taskprogress'.$id.'">'.$status[0].'%</b></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td width="5" class="st0"></td><td>Not done&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				$html .= '<td width="5" class="st1"></td><td>Done&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				$html .= '<td width="5" class="st2"></td><td>In progress&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				$html .= '<td width="5" class="st3"></td><td>Will not be done&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				$html .= '<td width="5" class="st6"></td><td>Paused&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				$html .= '<td align="right">Spent time: <b class="fs-20">'.number_format($time['itime']/3600,2,'.',' ').'</b>&nbsp;hours</td>';
				$html .= '</tr>';
				$html .= '</table>';
				$html .= '<ul class="pdf">';
				$html .= $this->itemsecho($all,1,'',0);
				$html .= '</ul>';
				break;
			}
		}
		return $html;
	}
	function btns($act='',$iid='',$itype=''){
		$html = '';
		$act = $this->r('act')!=''?$this->r('act'):$act;
		$iid = $this->r('iid')!=''?$this->r('iid'):$iid;
		$itype = $this->r('itype')!=''?$this->r('itype'):$itype;
		// echo $iid.'='.$itype;
		switch($act){
			case'additem':{
				$html = '
					<span class="btn f-l p-2 d-n" onclick="AddFile(this,\''.$this->uid.'\',\''.$iid.'\',\''.$itype.'\')"><span class="icon3 icon-image" title="Add изображение"></span></span>
					<span class="btn f-l p-2" onclick="AddItems(this,event,\'sub\',\''.date('Y-m-d H:i').'\');this.style.display=\'none\'"><span class="icongreen icon-plus" title="Add подпункт"></span></span>
					<span class="btn f-l p-2" onclick="if(jQuery(this).parent().parent().parent().parent().children().size()==1){this.parentNode.parentNode.parentNode.parentNode.style.display=\'none\';}else{this.parentNode.parentNode.parentNode.style.display=\'none\';}"><span class="iconred icon-trash" title="Delete пункт с подпунктами"></span></span>
				';
				break;
			}
		}
		return $html;
	}
	function test(){
		// echo '<h1>Test</h1>';
		// echo $this->r('a');
		// echo '11123';
		// print_r($this->lng);
	}
	function months($start, $end) {
		$startParsed = date_parse_from_format('Y-m-d', $start);
		$startMonth = $startParsed['month'];
		$startYear = $startParsed['year'];

		$endParsed = date_parse_from_format('Y-m-d', $end);
		$endMonth = $endParsed['month'];
		$endYear = $endParsed['year'];

		return ($endYear - $startYear) * 12 + ($endMonth - $startMonth) + 1;
	}
	function zzip($source, $destination){
		if(!extension_loaded('zip')||!file_exists($source)){
			return false;
		}
		$zip = new ZipArchive();
		if(!$zip->open($destination, ZIPARCHIVE::CREATE)){
			return false;
		}
		$source = str_replace('\\', '/', realpath($source));
		if(is_dir($source)===true){
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			foreach($files as $file){
				$file = str_replace('\\', '/', realpath($file));

				if(is_dir($file)===true){
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				elseif(is_file($file)===true){
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		elseif(is_file($source)===true){
			$zip->addFromString(basename($source),file_get_contents($source));
		}
		return $zip->close();
	}
	function backup_tables($id,$host,$user,$pass,$name,$tables = '*'){
		$link = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$link);
		
		//get all of the tables
		if($tables == '*'){
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result)){
				$tables[] = $row[0];
			}
		}
		else{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
		
		//cycle through
		foreach($tables as $table){
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
			
			$return.= 'DROP TABLE IF EXISTS '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++){
				while($row = mysql_fetch_row($result)){
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++){
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if(isset($row[$j])) {$return.= '"'.$row[$j].'"' ; } else {$return.= '""'; }
						if($j<($num_fields-1)) {$return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}
		
		//save file
		$handle = fopen('/home/admin/domains/web-help-me.com/public_html/sites/'.$id.'/sitedatabase'.date('Y-m-d-H-i-s').'.sql','w+');
		fwrite($handle,$return);
		fclose($handle);
	}
	function txt2link($text){
		$ntext = $text;
		preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $text, $match);
		// $ntext = ''.implode(' -|- ',$match);
		
		foreach($match[0] as $k=>$v){
			$ntext = ''.str_replace($v,'<a href="'.$v.'" target="_blank">link</a>',$ntext);
		}
		// echo '<pre>';print_r($match);echo '</pre>';
		// $n = explode('http',$text);
		
		// for($i=0;$i<count($n);$i++){
			// $ntext .= $i%2==0?'">link':$n[$i].'<a href="';
		// }
		return $ntext;
	}
	function gen(){
		switch(rand(0,2)){
			case'0':{
				$html = rand(48,57);
				break;
			}
			case'1':{
				$html = rand(65,90);
				break;
			}
			case'2':{
				$html = rand(97,122);
				break;
			}
		}
		return $html;
	}
}
?>
