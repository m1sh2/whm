<?php
require_once('base.php');

class usr extends base{
	function __construct(){
		parent::__construct();
	}
	function users($act=''){
		$html = '';
		$act = $act==''?$this->r('act'):$act;
		// $html .= ''.implode(' | ',$_REQUEST).' - '.$_SERVER['HTTP_REFERER'].' - '.$_SERVER['REQUEST_URI'];
		switch($act){
			case'users':{
				$users = $this->q("SELECT u.id,u.login FROM jos_whm AS u WHERE u.type='user' AND u.user='0' AND u.id<>'".$this->uid."' ORDER BY u.login ASC");
				while($u = mysql_fetch_assoc($users)){
					$html .= '<option value="'.$u['id'].'">'.$u['login'].'</option>';
				}
				break;
			}
			case'hired':{
				$html .= '<h3>You are hired in</h3>';
				$position = mysql_fetch_assoc($this->q("SELECT pos.name AS posname,u.* FROM jos_whm u INNER JOIN jos_whm pos ON pos.id=u.worker WHERE u.id=".$this->uid.""));
				$html .= '<ol>
						<li>Position: '.$position['posname'].'</li>
						<li>Salary: '.$position['cost'].' '.$this->val[$position['val']].'</li>
					</ol>';
				break;
			}
			default:{
				$workers = mysql_fetch_assoc($this->q("SELECT COUNT(*) AS c,u.login AS ulogin,SUM(u.cost) AS sum FROM jos_whm AS u WHERE u.type='user' AND u.user='".$this->uid."'"));
				// $html .= $this->info('info','<strong>Sorry!</strong> This section is under construction. Some functionality can be not working');
				$html .= '<h1>Users</h1>';
				$html .= '<h3>Employees: '.$workers['c'].'</h3>';
				$html .= '<h3>Summary salary: '.number_format($workers['sum'],2,'.',' ').' '.$this->val[$this->uval].'</h3>';
				$html .= $this->position();
				break;
			}
		}
		return $html;
	}
	function position($pid=0,$i=1,$lvl='',$x=0,$y=0){
		$html = '';
		$positions = $this->q("SELECT
				p.*,
				(SELECT COUNT(*) FROM jos_whm AS p2 WHERE p2.pid=p.id) AS cp,
				(SELECT COUNT(*) FROM jos_whm AS u WHERE u.worker=p.id) AS cu
			FROM jos_whm AS p WHERE type='post' AND user='".$this->uid."' AND pid=".$pid." ORDER BY p.id");
		$html .= '<ul class="positions positions'.$pid.'">';
		$c = array();
		$i = 1;
		// $lvl = $lvl==''?$i:$lvl;
		while($p = mysql_fetch_assoc($positions)){
			$alvl = explode('.',$lvl);
			if(count($alvl)>1){
				$lvl = $alvl[0];
				for($j=1;$j<(count($alvl)-1);$j++){
					$lvl .= '.'.$alvl[$j];
				}
				$lvl .= '.'.$i;
			}
			else{
				$lvl = $i;
			}
			// $lvl2 = $lvl.'.'.$i;
			$html .= '<li><i></i><span onclick="Page(\'positionusers&pid='.$p['id'].'&cu='.$p['cu'].'\',e(\'position'.$p['id'].'\'))">'.$lvl.'. '.$p['name'].' ('.$p['cu'].')</span>
					<span class="btn f-r p-1 br-10" onclick="if(confirm(\'Are you sure?\')){Load(e(\'position'.$p['id'].'\'),\'type=edit&act=position&pid='.$p['id'].'&i='.$i.'&act2=delete\');}"><span class="iconred icon-close" title="Delete position"></span></span>
					<span class="btn f-r p-1 br-10" onclick="Load(e(\'position'.$p['id'].'\'),\'type=edit&act=position&pid='.$p['id'].'&i='.$i.'\');"><span class="iconlblue icon-pencil" title="Edit position"></span></span>
					<div id="position'.$p['id'].'">';
			$html .= $this->positionusers($p['id'],$p['cu']);
			$html .= '</div></li>';
			if($p['cp']>0){
				$html .= $this->position($p['id'],1,$lvl.'.1',($x+20),$y);
			}
			$i++;
			
			
			// $lvl = count()>1?$lvl.'.'.$i:$i;
		}
		$html .= '</ul>';
		return $html;
	}
	function positionusers($pid=0,$cu=0){
		$html = '';
		$pid = $this->r('pid')!=''?$this->r('pid'):$pid;
		$cu = $this->r('cu')!=''?$this->r('cu'):$cu;
		if($cu>0){
			$html .= '<table rules="rows" cellspacing="0" cellpadding="5" bgcolor="ffffff">';
			$queryusers = $this->q("SELECT * FROM jos_whm AS u WHERE u.type='user' AND u.worker=".$pid);
			$i = 1;
			while($qu = mysql_fetch_assoc($queryusers)){
				$html .= '<tr id="positionuser'.$qu['id'].'">';
				$html .= $this->positionuser($qu,$i);
				$html .= '</tr>';
				$i++;
			}
			$html .= '</table>';
		}
		else{
			// $html .= '- No users -';
		}
		return $html;
	}
	function positionuser($qu='',$i=1){
		if($qu==''){
			$qu = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.type='user' AND u.id=".$this->r('uid')));
			$i = $this->r('i');
		}
		
		$html = '';
		$html .= '<td>'.$i.'</td>';$html .= '<td width="100">'.$qu['login'].'</td>';
		$html .= '<td width="80" class="ta-r">'.number_format($qu['cost'],2,'.',' ').' '.$this->val[$qu['val']].'</td>';
		$html .= '<td><span class="btn f-r p-1 br-10" onclick="if(confirm(\'Are you sure?\')){jQuery(\'#positionuser'.$qu['id'].'\').hide();Load(e(\'positionuser'.$qu['id'].'\'),\'type=edit&act=positionuser&uid='.$qu['id'].'&i='.$i.'&act2=delete\');}"><span class="iconred icon-close" title="Delete employee"></span></span>
				<span class="btn f-r p-1 br-10" onclick="Load(e(\'positionuser'.$qu['id'].'\'),\'type=edit&act=positionuser&uid='.$qu['id'].'&i='.$i.'\');"><span class="iconlblue icon-pencil" title="Edit employee"></span></span></td>';
		return $html;
	}
	function posts($t,$current=0){
		$html = '';
		$posts = $this->q("SELECT * FROM jos_whm AS p WHERE type='post' AND user='".$this->uid."' AND pid=0 ORDER BY p.id");
		$l = 0;
		$html .= $this->postsecho($posts,1,'',$t,$l,$current);
		return $html;
	}
	function postsecho($all,$k,$k_parent,$t,$l,$current=0){
		$html = '';
		switch($t){
			case'option':{
				$html .= '';
				break;
			}
			case'li':{
				$html .= '<ul class="posts">';
				break;
			}
		}
		$j = 0;
		$i = 1;
		while($a = mysql_fetch_assoc($all)){
			
			switch($t){
				case'option':{
					$lvl = '';
					for($z=1;$z<=$l;$z++){
						$lvl .= '- ';
					}
					$html .= '<option value="'.$a['id'].'"'.($current==$a['id']?' selected="selected"':'').'>'.$lvl.'';
					
					break;
				}
				case'li':{
					$html .= '<li>';
					break;
				}
			}
			// $l = substr($l,0,-1);
			$html .= ($t=='li'?$this->post($a):$a['name']);
			$i++;
			
			if(mysql_num_rows($this->q("SELECT id FROM jos_whm AS t WHERE pid='".$a['id']."'"))>0){
				$all2 = $this->q("SELECT * FROM jos_whm AS t WHERE pid='".$a['id']."' ORDER BY t.id ASC");
				$k_parent = $k_parent.($k_parent==''?'':'.').$k;
				$l++;
				
				$html .= $this->postsecho($all2,1,$k_parent,$t,$l,$current);
				$l--;
			}
			
			$k++;
			$j = 1-$j;
			switch($t){
				case'option':{
					$html .= '</option>';
					break;
				}
				case'li':{
					$html .= '</li>';
					break;
				}
			}
		}
		$l--;
		switch($t){
			case'option':{
				$html .= '';
				break;
			}
			case'li':{
				$html .= '</ul>';
				break;
			}
		}
		return $html;
	}
	function post($a){
		$html = '';
		$html .= '<label>
				'.$a['name'].'
				<span class="btn f-r p-1 br-10 bg-red" onclick="if(confirm(\'Are You sure You want to delete this Section?\')){Load(e(\'content\'),\'type=edit&act=post&act2=delete&eid='.$a['id'].'\')}"><span class="iconf icon-closethick" title="Delete Section"></span></span>
				<span class="btn f-r p-1 br-10" onclick="Page(\'edit&act=post&eid='.$a['id'].'\',\'0\',\'\')"><span class="iconblue icon-pencil" title="Edit Section"></span></span>
			</label>';
		$all = $this->q("SELECT *,u.login AS ulogin FROM jos_whm AS u WHERE u.type='user' AND u.worker='".$a['id']."' ORDER BY u.id");
		$j = 1;
		
		if(mysql_num_rows($all)==0){
			$html .= '<div><table cellspacing="0" cellpadding="5" border="0" width="100%"><tr><td align="left">- Empty -</td></tr></table></div>';
		}
		else{
			$val = $this->q("SELECT id,name,cost,contacts FROM jos_whm WHERE type='valute' ORDER BY id");
			$va = array();
			while($v = mysql_fetch_assoc($val)){
				$va[$v['id']]['name'] = $v['name'];
				$va[$v['id']]['cost'] = $v['cost'];
				// $va[$v['id']]['cost'] = $v['contacts'];
			}
			
			while($a = mysql_fetch_assoc($all)){
				$level = $a['html']+$a['css']+$a['php']+$a['js']+$a['mysql']+$a['joomla'];
				
				$html .= '<div>
						<table cellspacing="0" cellpadding="5" border="0" width="100%">
							<tr>
								<td width="30" align="right">'.$j.'</td>
								<td width="30" align="right">'.$a['id'].'</td>
								<td><a href="javascript:void(0)" onclick="Load(e(\'content\'),\'type=projects&idin='.$a['id'].'\')">'.$a['ulogin'].'</a></td>
								<td width="100" align="right">'.($a['val']==768?$va[$a['val']]['name'].' <b class="fs-16">'.$a['cost'].'</b>':'<b class="fs-16">'.$a['cost'].'</b> '.$va[$a['val']]['name'].' <sup class="c-000">~$'.($a['cost']*$va[$a['val']]['cost']).'</sup>').'</td>
								<td width="20" align="center"><span class="'.($a['status']==1?'iconred icon-locked" title="Blocked':'icongreen icon-unlocked" title="Unblocked').'"></span></td>
								<td width="50" align="center">'.date('d.m.y',strtotime($a['date2'])).'</td>
								<td width="30" align="center"><b class="fs-14">'.$level.'</b></td>
								'.($this->uid==818?'
								<td width="140">
									<span class="btn f-r p-1 br-10 bg-red" onclick="if(confirm(\'Are You sure You want to delete this employee?\')){Load(e(\'content\'),\'type=edit&act=post&act2=removeuser&eid='.$a['id'].'\')}"><span class="iconf icon-closethick" title="Delete employee"></span></span>
									<span class="btn f-r p-1 br-10" onclick="if(confirm(\'Are You sure You want to '.($a['status']==1?'unblock':'block').' this employee?\')){Load(e(\'content\'),\'type=edit&act=user&act2=block&eid='.$a['id'].'&block='.($a['status']==1?'0':'1').'\')}"><span class="'.($a['status']==0?'iconred icon-locked" title="Block employee':'icongreen icon-unlocked" title="Unblock employee').'"></span></span>
									<span class="btn f-r p-1 br-10 d-n" onclick=""><span class="iconorange icon-folder-collapsed" title=""></span></span>
									<span class="btn f-r p-1 br-10 d-n" onclick=""><span class="iconlblue icon-document" title=""></span></span>
									<span class="btn f-r p-1 br-10 d-n" onclick=""><span class="iconlblue icon-pencil" title=""></span></span>
								</td>
								':'
								<td width="40">
									<span class="btn f-r p-5 br-15 bg-red" onclick="if(confirm(\'Are You sure You want to delete this employee?\')){Load(e(\'content\'),\'type=edit&act=post&act2=delete&eid='.$a['id'].'\')}"><span class="iconf icon-closethick" title="Delete employee"></span></span>
								</td>
								').'
							</tr>
						</table>
					</div>';
				$j++;
			}
		}
		return $html;
	}
	
	function clients(){
		$html = '';
		// $html .= '
								// <div class="ui-state-error ui-corner-all"> 
									// <p><span class="ui-icon ui-icon-info"></span>
									// <strong>Sorry!</strong> This section is under construction. Some functionality can be not working</p>
								// </div>
							// ';
		$html .= '<h1>Clients and contractors</h1>';
		$html .= '
					<div class="control">
						<span class="d-n btn f-l d-n" onclick="window.location=\'archive-projects.html\';"><span class="icon3 icon-folder-collapsed" title="Архив"></span></span>
						<span id="selall" class="btn f-l d-n" onclick="jQuery(\'.projectcheck\').attr(\'checked\',true);jQuery(\'#projectsoperations,#deselall\').show();jQuery(\'#selall\').hide();ProjectsStatus()"><span class="icongreen icon-check" title="Select all"></span></span></li>
						<span id="deselall" style="display:none;" class="btn f-l" onclick="jQuery(\'.projectcheck\').attr(\'checked\',false);jQuery(\'#projectsoperations,#deselall\').hide();jQuery(\'#selall\').show();ProjectsStatus()"><span class="iconred icon-cancel" title="Deselect"></span></span></li>
						<span id="clientsoperations" style="display:none;">
							<span class="btn f-l" onclick="ClientsDelete()"><span class="iconred icon-trash" title="Delete"></span></span>
						</span>
						<span class="btnst" style="display:none;" id="clientsstatus">
							<span class="st0" title="Not executed"></span>
							<span class="st2 d-n" title="In the process"></span>
							<span class="st1 d-n" title="Done"></span>
							<span class="st3" title="Closed"></span>
							<span class="st4" title="Archive"></span>
						</span>
						<span class="btn f-l d-n" onclick="Page(\'wcalendar&act=projects\',\'0\',\'\')"><span class="icon3 icon-calendar" title="Календарь"></span></span>
					</div>';
		$all = $this->q("SELECT * FROM users c WHERE type=3 AND uid='".$this->uid."' ORDER BY c.name ASC");
		if(mysql_num_rows($all)>0){
			// $valute = $this->q("SELECT * FROM jos_whm AS v WHERE v.type='valute' ORDER BY v.id ASC");
			// $val = array();
			// $conv = array(1=>1,2=>1.25,3=>0.03,4=>0.125);
			// while($v = mysql_fetch_assoc($valute)){
				// $val[$v['id']] = $v['name'];
			// }
			$html .= '<ul class="clients">';
			$i = 1;
			while($a = mysql_fetch_assoc($all)){
				$html .= '<li id="client'.$a['id'].'" class="t'.($i%2==0?'0':'1').'">'.$this->client($i,$a).'</li>';
				$i++;
			}
			$html .= '</ul>';
		}
		else{
			$html .= '[- No records about clients -]';
		}
		return $html;
	}
	function client($i,$a){
		$projects = $this->q("SELECT * FROM projects WHERE cid='".$a['id']."' ORDER BY id DESC");
		switch($a['sid']){
			case'3':{
				$cst = 'client';
				$cstcolor = 'c-green ';
				break;
			}
			case'4':{
				$cst = 'contractor';
				$cstcolor = 'c-red ';
				break;
			}
		}
		$html = '
						<table cellspacing="0" cellpadding="5" border="0" width="100%">
							<tr>
								<td width="20" align="right" class="fs-11">'.$i.'</td>
								<td width="20" style="cursor:pointer;"><input class="clientcheck" type="checkbox" onclick="ClientsCheck();" value="'.$a['id'].'" /></td>
								<td>'.$a['name'].'</td>
								<td width="70" class="'.$cstcolor.'fs-12 ta-c">'.$cst.'</td>
								<td width="40" class="fs-12 ta-l" onclick="Page(\'projects&idclient='.$a['id'].'\',\'0\');" style="cursor:pointer;"><span class="f-r p-1 btn br-15" style="width:35px;margin:-5px 2px -5px 0;padding:3px 5px;"><span class="iconlblue icon-folder-collapsed" title="Goto projects"><span style="font-size:11px;position:absolute;right:-15px;top:2px;">'.mysql_num_rows($projects).'</span></span></span></td>
								<td width="50">
									<span class="btn f-r p-1 br-10" style="margin:0 2px 0 0;" onclick="jQuery(\'.clientinfo'.$a['id'].'\').slideToggle(200)"><span class="iconlblue icon-info" title="Additional information"></span></span>
									<span class="btn f-r p-1 br-10" style="margin:0 2px 0 0;" onclick="Page(\'edit&act=client&cid='.$a['id'].'&i='.$i.'\',\'0\');"><span class="iconlblue icon-pencil" title="Edit"></span></span>
								</td>
							</tr>
						</table>
						<div class="clientinfo'.$a['id'].' p-10 bc-white" style="display:none;">'.$a['description'].'</div>';
		return $html;
	}
	
	function login($home=''){
		switch($this->r('idin')){
			case'vk':{
				$lcode = $this->r('uid').'@vk.com';
				$lpass = $this->r('uid');
				break;
			}
			default:{
				$lcode = $this->r('lcode')!=''&&$this->r('lcode')!=''?$this->r('lcode'):'';
				$lpass = $this->r('lpass')!=''&&$this->r('lpass')!=''?$this->r('lpass'):'';
				break;
			}
		}
		
		
		$title = '<h2 align="center">'.($home=='home'||$this->r('num')==''||$this->r('num')==''?'Enter':'Enter'.$this->winbtns()).'</h2>';
		$error = $this->r('error')!=''?$this->r('error'):'';
		if($home=='registered'){
			$title .= '<div class="ui-state-highlight ui-corner-all"> 
					<p><span class="ui-icon ui-icon-info"></span>
					<strong>Registration was successful!</strong> You can log in</p>
				</div>';
		}
		$form = '
				<table cellpadding="5" cellspacing="0" border="0" width="100%">
					<tr>
						<td>
							<form action="https://www.google.com/accounts/o8/id" method="post" class="d-n">
								<input type="hidden" name="openid.ns" value="http://specs.openid.net/auth/2.0" />
								<input type="hidden" name="openid.claimed_id" value="http://specs.openid.net/auth/2.0/identifier_select" />
								<input type="hidden" name="openid.identity" value="http://specs.openid.net/auth/2.0/identifier_select" />
								<input type="hidden" name="openid.return_to" value="http://localhost/whm/login/check.html" />
								<input type="hidden" name="openid.realm" value="http://localhost/whm/" />
								<input type="hidden" name="openid.mode" value="checkid_setup" />
								<input type="submit" name="submit" value="Google" />
							</form>
							<a class="d-n" href="https://www.google.com/accounts/o8/id?openid.ns=http://specs.openid.net/auth/2.0&openid.claimed_id=http://specs.openid.net/auth/2.0/identifier_select&openid.identity=http://specs.openid.net/auth/2.0/identifier_select&openid.return_to=http://localhost/whm/login/check.html&openid.realm=http://localhost/whm/&openid.mode=checkid_setup">Google</a>
						</td>
						<td width="150" valign="bottom">
							<a class="c-000" href="https://chrome.google.com/webstore/detail/whm-controling-easily/pfecocbghmnngadbjffalhjcdapfpcaj" target="_blank"><img src="'.$this->site.'img/Chrome-Store.png" alt="Google Web Store" width="100" /><br />Also aviable on Google Web Store as Google Chrome Application</a>
							<br />
							<br />
							<table cellpadding="2" cellspacing="0" border="0">
								<tr>
									<td><a class="c-000 logindemo" href="'.$this->site.'demo.html">Demo</a></td>
								</tr>
							</table>
						</td>
						<td width="50%" valign="bottom">
							<form method="post" action="'.$this->site.'login.html">
								<input name="curl" type="hidden" value="'.(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:$this->site.'home.html').'" />
								<div id="b2">
									<table cellpadding="2" cellspacing="0" border="0">
										<tr>
											<td colspan="2"><div id="errorlogin"></div></td>
										</tr>
										<tr>
											<td><input type="email" style="width:150px;" name="lcode" value="- Email -" onfocus="if(this.value==this.defaultValue){this.value=\'\';}else{this.value=this.value;}" onblur="if(this.value==\'\'){this.value=this.defaultValue;}else{this.value=this.value;}" /></td>
										</tr>
										<tr>
											<td><input type="text" style="width:150px;" name="lpass" value="- Password -" onfocus="if(this.value==this.defaultValue){this.value=\'\';}else{this.value=this.value;}this.type=\'password\';" onblur="if(this.value==\'\'){this.value=this.defaultValue;this.type=\'text\';}else{this.value=this.value;this.type=\'password\';}" /></td>
										</tr>
										<tr>
											<td colspan="2" align="center"><input type="submit" value="Enter" class="loginenter" /></td>
										</tr>
										<tr>
											<td colspan="2" align="center"><a class="c-000 loginreg" href="'.$this->site.'registration.html">Registration</a></td>
										</tr>
										<tr>
											<td colspan="2" align="center"><a class="c-000 loginreset" href="javascript:void(0)" onclick="Page(\'login&rp=f\',e(\'content\'))">Password reset</a></td>
										</tr>
									</table>
								</div>
							</form>
						</td>
					</tr>
					<tr>
						<td colspan="3" align="center">
							<div>
							<!-- Put this script tag to the <head> of your page -->
							<script type="text/javascript" src="//vk.com/js/api/openapi.js?98"></script>

							<script type="text/javascript">
							  VK.init({apiId: 3775835});
							</script>

							<!-- Put this div tag to the place, where Auth block will be -->
							<div id="vk_auth"></div>
							<script type="text/javascript">
							VK.Widgets.Auth("vk_auth", {width: "150px", authUrl: \'/login.html?vk\'});
							</script>
							</div>
						</td>
					</tr>
				</table>';
		if(count($this->at)>1){$rp = $this->r('rp')!=''?$this->r('rp'):$this->at[2];}
		else{$rp = $this->r('rp')!=''?$this->r('rp'):($this->at?$this->at[0]:'');}
		switch($rp){
			case'f':{
				$title = 'Password reset';
				$form = '
					Enter Email, specified at registration
					<form method="post" action="'.$this->site.'login.html">
						<input name="rp" type="hidden" value="s" />
						<p><input name="email" type="text" value="" /></p>
						<p><input type="submit" value="Reset" /></p>
					</form>
					';
				break;
			}
			case'r':{
				$title = 'Password reset';
				if(strlen($this->at[1])>50&&strlen($this->at[1])<70){
					$form = 'Enter the new password
						<form method="post" action="'.$this->site.'login.html">
							<input name="rp" type="hidden" value="p" />
							<input name="email" type="hidden" value="'.$this->at[3].'" />
							<p><input name="pass" type="password" value="" /></p>
							<p><input type="submit" value="Reset" /></p>
						</form>';
				}
				else{
					$form = '';
					$this->lnk($this->site.'login.html?rp=f&error=Wrong parameters');
				}
				break;
			}
			case's':{
				$title = 'Password reset';
				$user = mysql_num_rows($this->q("SELECT * FROM users WHERE email='".$this->r('email')."'"));
				if($user==0){
					$form = 'User with such Email <b>'.$this->r('email').'</b> is not exist.';
				}
				else{
					$form = 'Instructions to reset your password sent to <b>'.$this->r('email').'</b>.';
					$this->forgotpasscode = md5($this->gen()).$this->gen().md5($this->gen());
					$this->forgotemail = $this->r('email');
					$this->email($this->r('email'),'WEB - HELP ME: Password reset',"To recover your password, go to: ".$this->site."login-".$this->forgotpasscode."-r-".$this->forgotemail.".html\r\n____________\r\n");
				}
				break;
			}
			case'p':{
				$this->q("UPDATE users SET password='".md5(md5(trim($this->r('pass'))))."' WHERE email='".$this->r('email')."'");
				$error = 'Password has been changed';
				$this->email($this->r('email'),'WEB - HELP ME: Password reset',"The new password: ".$this->r('pass')."\r\n____________\r\n");
				break;
			}
			default:{
				if(isset($this->uid)&&$this->uid>0){
					$title = '<h2>Signed in</h2>';
					$form = '';
					$form = $this->lnk($this->site);
				}
				else{
					// echo '1 '.$this->r('act').' - '.$this->r('hash').' - '.md5('3775835'.$this->r('uid').'r4l8YAiruJNazF4zEGFp');
					// echo '<pre>';print_r($_REQUEST);echo '</pre>';
					if(($this->r('idin')=='vk'&&$this->r('hash')==md5('3775835'.$this->r('uid').'r4l8YAiruJNazF4zEGFp'))
						||($this->r('act')==''
						&&$lcode!=''
						&&strlen($lcode)>0
						&&mysql_num_rows($this->q("SELECT * FROM users WHERE email='".trim($lcode)."' AND password='".md5(md5(trim($lpass)))."'"))>0)
						){
						$user = $this->q("SELECT * FROM users WHERE email='".trim($lcode)."'");
						$u = mysql_fetch_assoc($user);
						if($u['status']==0){
						$this->q("UPDATE users SET datelastlogin='".date('Y-m-d H:i:s')."' WHERE id='".$u['id']."'");
							$params = array(
								'uid'=>$u['id'],
								'ulogin'=>$u['login'],
								'uem'=>$u['email'],
								'users'=>$u['cid'],
								'userid'=>$u['id'],
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
								'version'=>$this->version,
								'language'=>$u['language'],
								'os'=>$this->os
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
							$form = $this->lnk($this->site);
							break;
						}
						else{
							$error = $this->info('alert','<strong>Error!</strong> User is bloked');
						}
					}
					elseif($lcode!=''&&strlen($lcode)>0&&$this->q("SELECT * FROM users WHERE email!='".trim($lcode)."' OR password!='".md5(md5(trim($lpass)))."'")){
						$error = $this->info('alert','<strong>Error!</strong> Wrong Email or password');
					}
				}
				break;
			}
		}
		return '<h2>'.$title.'</h2>'.$error.$form;
		//return $html;
	}
	function logout(){
		$this->q("DELETE FROM cache WHERE sesid='".session_id()."'");
		$html = $this->lnk($this->site.'home.html');
		return $html;
	}
	function registration(){
		switch($this->r('act')){
			case'emailcheck':{
				if(mysql_num_rows($this->q("SELECT id FROM users WHERE email='".$this->r('value')."'"))>0){
					$html = 'User with the same "Email" already exists.';
				}
				else{
					$html = '';
				}
				break;
			}
			case'registration':{
				$html = '';
				$this->q("INSERT INTO users (
					email,
					login,
					password,
					type,
					date,
					laid,
					vid
					) VALUES (
					'".$this->r('email')."',
					'".$this->r('login')."',
					'".md5(md5($this->r('password')))."',
					2,
					'".date('Y.m.d H:i:s')."',
					1,
					'768'
					)");
				$_REQUEST = array();
				$text = "Our congratulations!\n";
				$text .= "You have successfully registered in the system WEB - HELP ME!\n";
				$text .= "Your login details:\n";
				$text .= "Login: ".$this->r('email')."\n";
				$text .= "Password: ".$this->r('password');
				mail($this->r('email'),'WHM - Registration',$text,"From: no-reply@web-help-me.com\r\n");
				$html = $this->login('registered');
				break;
			}
			default:{
				$html = '<h1>Registration</h1>';
				// SetCookie("test","Value");
				// $html .= $this->ses('test');
				$html .= '
					<form name="reg'.md5(rand(0,99999)).'" action="javascript:void(0)" onsubmit="CheckReg(\'registration\',this)">
						<input type="hidden" name="pid" value="'.(isset($this->uid)&&$this->uid>0?$this->uid:'0').'" />
						<table cellpadding="5" cellspacing="0" border="0" width="100%">
							<tr>
								<td valign="middle" width="150" style="border-bottom:1px dashed #999;">Nickname:</td>
								<td valign="middle" width="20" style="border-bottom:1px dashed #999;" id="el"></td>
								<td valign="middle" style="border-bottom:1px dashed #999;"><input onblur="CheckReg(\'login\',this)" onkeyup="CheckReg(\'login\',this)" id="lgn" type="text" style="padding:3px;width:150px;" name="nlogin" value="" /> <span style="font-size:11px;color:#f00;"></span></td>
							</tr>
							<tr>
								<td valign="middle" style="border-bottom:1px dashed #999;">Email:<div id="eerrr" style="color:#f00;font-size:12px;"></div></td>
								<td valign="middle" id="ee" style="border-bottom:1px dashed #999;"></td>
								<td valign="middle" style="border-bottom:1px dashed #999;"><input onblur="CheckReg(\'emailcheck\',this);CheckReg(\'email\',this)" onkeyup="CheckReg(\'email\',this)" id="eml" type="text" style="padding:3px;width:150px;" name="nemail" value="" /> <span style="font-size:11px;color:#f00;"></span></td>
							</tr>
							<tr>
								<td valign="middle" style="border-bottom:1px dashed #999;">Password:<div class="fs-11">(min 5 символов)</div></td>
								<td valign="middle" id="ep" style="border-bottom:1px dashed #999;"></td>
								<td valign="middle" style="border-bottom:1px dashed #999;"><input onblur="CheckReg(\'password\',this)" onkeyup="CheckReg(\'password\',this)" id="pss" type="password" style="padding:3px;width:150px;" name="npass" value="" /></td>
							</tr>
							<tr>
								<td valign="middle" style="border-bottom:1px dashed #999;">Repeat password:</td>
								<td valign="middle" id="epr" style="border-bottom:1px dashed #999;"></td>
								<td valign="middle" style="border-bottom:1px dashed #999;"><input onblur="CheckReg(\'repassword\',this)" onkeyup="CheckReg(\'repassword\',this)" id="rpss" type="password" style="padding:3px;width:150px;" name="rpass" value="" /></td>
							</tr>
							<tr>
								<td valign="middle">Accept the agreement:</td>
								<td valign="middle"></td>
								<td valign="middle"><input id="chd" type="checkbox" name="dogovor" value="1" onclick="if(this.checked==true){document.getElementById(\'sbregistration\').disabled=false;}else{document.getElementById(\'sbregistration\').disabled=true;}" /></td>
							</tr>
						</table>
						<div id="errorreg" style="color:#f00;font-size:12px;"></div>
						<h2>Terms of the agreement</h2>
						<table id="detail" border="0" cellspacing="0" cellpadding="5" width="100%">
							<tr><td valign="top">1.</td><td>"Stones" Do not drop ... and stones too =)</td></tr>
							<tr><td valign="top">2.</td><td>Service is free</td></tr>
							<tr><td valign="top">3.</td><td>Respect all people no matter of race, nationality, religion, nickname, projects, reviews, and other facts and factors</td></tr>
							<tr><td valign="top">4.</td><td>Respect to the service administration, regardless of decision-making its in relation to the Service user or users groups, project or group of projects</td></tr>
							<tr><td valign="top">5.</td><td>All disputes shall be resolved by users personally or through administration if no agreement was reached. In such situations, the administration acts as arbitration and as independent witness at the same time</td></tr>
							<tr><td valign="top">6.</td><td>Any changes in the work may be published in the <a href="news.html">News</a></td></tr>
							<tr><td valign="top">7.</td><td>Some information on use of services can be found in the <a href="'.$this->site.'faq.html">F.A.Q.</a> section</td></tr>
							<tr><td valign="top">8.</td><td>Treatment can be sent to the administration in any convenient manner specified in section <a href="'.$this->site.'contacts.html">Contacts</a></td></tr>
							<tr><td valign="top">9.</td><td>Copying information, functionality, design, symbol (logo or its parts), slogan, name, address and materials of the site, Service, is permitted only with administration written permission and must be accompanied by a reference to '.$this->site.'</td></tr>
						</table>
						<table cellpadding="5" cellspacing="0" border="0" width="100%">
							<tr>
								<td valign="middle" align="center"><input id="sbregistration" disabled="disabled" type="submit" name="submit" value="Register" class="btn bg-orange br-10 fs-16" /></td>
							</tr>
						</table>
					</form>';
				break;
			}
		}
		return $html;
	}
	
}

?>