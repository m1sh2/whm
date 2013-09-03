<?php
require_once('base.php');

class pti extends base{
	function __construct(){
		parent::__construct();
	}
	function projects($act='',$idclient=0,$idproject=0,$cstatus=3){
		$html = '';
		$cstatus = $this->r('cstatus')>0?$this->r('cstatus'):$cstatus;
		$idclient = $this->r('idclient')>0?$this->r('idclient'):$idclient;
		$idproject = $this->r('idproject')>0?$this->r('idproject'):$idproject;
		$act = $this->r('act')!=''?$this->r('act'):$act;
		switch($act){
			case'archive':{
				$limit = $this->r('limit')==''?0:$this->r('limit');
				$html .= '<h4>'.$limit.' &rarr; '.($limit+20).' projects</h4>';
				$sql = "SELECT p.*,c.name AS cname FROM users AS c
					LEFT JOIN projects p ON
						p.uid='".$this->uid."'
						AND p.sid=4
						".($idclient>0?"AND p.cid='".$idclient."'":"")."
					WHERE
						p.cid=c.id
						AND c.type=".$cstatus."
					ORDER BY p.id DESC LIMIT ".$limit.",20";
				$html .= $this->projectecho($sql);
				if(mysql_num_rows($this->q($sql))==20){
					$html .= '<div id="ownarchiveprojects'.$limit.'"><button onclick="Page(\'projects&act=archive&limit='.($limit+20).'\',e(\'ownarchiveprojects'.$limit.'\'));">Show more projects</button></div>';
				}
				break;
			}
			case'closed':{
				// $html .= '<h3>Closed Projects</h3>';
				$limit = $this->r('limit')==''?0:$this->r('limit');
				$html .= '<h4>'.$limit.' &rarr; '.($limit+20).' projects</h4>';
				$sql = "SELECT p.*,c.name AS cname FROM jos_whm AS c
					LEFT JOIN jos_whm AS p ON
						p.type='project'
						AND (p.user='".$this->uid."' OR p.users='".$this->uid."')
						AND p.status=3
						".($idclient>0?"AND p.client='".$idclient."'":"")."
					WHERE
						c.type='client'
						AND p.client=c.id
						AND c.status=".$cstatus."
					ORDER BY p.id DESC LIMIT ".$limit.",20";
				$html .= $this->projectecho($sql);
				if(mysql_num_rows($this->q($sql))==20){
					$html .= '<div id="ownclosedprojects'.$limit.'"><button onclick="Page(\'projects&act=closed&limit='.($limit+20).'\',e(\'ownclosedprojects'.$limit.'\'));">Show more projects</button></div>';
				}
				break;
			}
			case'tasks':{
				$sql = "SELECT p.*,c.name AS cname FROM jos_whm AS c
					LEFT JOIN jos_whm AS p ON
						p.type='project'
						AND (p.user='".$this->uid."' OR p.users='".$this->uid."')
						AND (p.status=0 OR p.status=1 OR p.status=2 OR p.status=6 OR p.status=5)
						".($idclient>0?"AND p.client='".$idclient."'":"")."
					WHERE
						c.type='client'
						AND p.client=c.id
						AND c.status=".$cstatus."
					ORDER BY p.id ASC
					LIMIT 1";
				if(mysql_num_rows($this->q($sql))>0){
					$html .= '<h2>Tasks ('.mysql_num_rows($this->q($sql)).')</h2>';
					// $valute = $this->q("SELECT id,name FROM jos_whm AS v WHERE v.type='valute' ORDER BY v.id ASC");
					// $val = array();
					// $val[0] = '$';
					// while($v = mysql_fetch_assoc($valute)){
						// $val[$v['id']] = $v['name'];
					// }
					while($project = mysql_fetch_assoc($this->q($sql))){
						
						$allusers = array();
						$uall = $this->q("SELECT t.users FROM jos_whm AS t WHERE t.type='task' AND t.status!=4 AND t.project=".$project['id']." AND (t.user=".$this->uid." OR t.users='".$this->uid."')");
						if($this->r('idin')=='user'&&$this->uid!=818){
							$users = '';
						}
						else{
							while($a = mysql_fetch_assoc($uall)){
								$allusers[] = $a['users'];
							}
							$users = '<ul class="addedusers o-h">';
							foreach(array_unique($allusers) as $u){
								if($u!=''&&$u!=0){
									$user = mysql_fetch_assoc($this->q("SELECT id,login FROM jos_whm AS u WHERE u.id='".$u."'"));
									$users .= '<li class="f-l'.($this->r('idin')=='user'&&$this->r('idin2')==$user['id']?' active':'').'" onclick="if(hasClass(this,\'active\')){jQuery(\'.tasks>li\').show();jQuery(this).parent().find(\'li\').removeClass(\'active\');}else{jQuery(\'.tasks>li\').hide();jQuery(\'.tasks>li.user'.$user['id'].'\').show();jQuery(this).parent().find(\'li\').removeClass(\'active\');jQuery(this).addClass(\'active\');}">'.$user['login'].'</li>';
								}
							}
							$users .= '</ul>';
						}
						$sql2 = $this->q("SELECT
								t.*,
								p.name AS pname,
								p.contacts AS pcont,
								p.val AS pval
							FROM
								jos_whm AS p
							LEFT JOIN jos_whm AS t
							ON
								t.project=p.id
								AND t.type='task'
								AND t.status!=4
								
							WHERE
								p.id='".$project['id']."'
								AND (t.user='".$this->uid."' OR t.users='".$this->uid."')
							ORDER BY t.date1 DESC");
						
						
						$html .= '<table>';
						while($a = mysql_fetch_assoc($sql2)){
							$s = $this->taskstatus($a['id']);
							$time = mysql_fetch_assoc($this->q("SELECT SUM(i.time) AS itime FROM jos_whm AS i WHERE i.type='item' AND i.task='".$a['id']."'"));
							$a['itime'] = $time['itime'];
							if($s[0]==0){
								$st = 0;
							}
							elseif($s[0]==100){
								$st = 1;
							}
							else{
								$st = 2;
							}
							
							if($s[1]==1){
								$st = 5;
							}
							
							$datediffnow = floor((strtotime($a['date2'])-strtotime(date('Y-m-d H:i:s')))/(60*60*24));
							$datediffend = floor((strtotime($a['date3'])-strtotime($a['date1']))/(60*60*24));
							$us = array();
							$html .= '<tr>';
							$html .= $this->task($a,$s,$st,$datediffnow,$allusers,$this->val,$us);
							$html .= '</tr>';
						}
						$html .= '</table>';
					}
					
					
				}
				break;
			}
			case'demo':{
				$html = '<h2>'.$this->lng['Projects'].' &rarr; ';
				if($idclient==0){
					$html .= '<span class="fs-14">
							<span class="link'.($cstatus==3?' active':'').'" style="width:auto;" onclick="Page(\'projects&cstatus=3\',e(\'content\'));jQuery(this).parent().find(\'span.btn\').removeClass(\'active\');jQuery(this).addClass(\'active\');">'.$this->lng['Clients'].'</span>
							<span class="link'.($cstatus==4?' active':'').'" style="width:auto;" onclick="Page(\'projects&cstatus=4\',e(\'content\'));jQuery(this).parent().find(\'span.btn\').removeClass(\'active\');jQuery(this).addClass(\'active\');">'.$this->lng['Contractors'].'</span>
							<span class="link d-n" style="width:auto;padding:5px 15px;" onclick="Page(\'projects&act=tasks&cstatus=0\',e(\'content\'))">'.$this->lng['Tasks Clients'].'</span>
							<span class="link d-n" onclick="window.location=\'archive-projects.html\';"><span class="icon3 icon-folder-collapsed" title="'.$this->lng['Archive'].'"></span></span>
							<span id="selall" class="link d-n" onclick="jQuery(\'.projectcheck\').attr(\'checked\',true);jQuery(\'#projectsoperations,#deselall\').show();jQuery(\'#selall\').hide();ProjectsStatus()"><span class="icongreen icon-check" title="'.$this->lng['Select all'].'"></span></span></li>
							<span id="deselall" style="display:none;" class="btn f-l" onclick="jQuery(\'.projectcheck\').attr(\'checked\',false);jQuery(\'#projectsoperations,#deselall\').hide();jQuery(\'#selall\').show();ProjectsStatus()"><span class="iconred icon-cancel" title="'.$this->lng['Deselect'].'"></span></span></li>
							<span id="projectsoperations" style="display:none;">
								<span class="btn f-l" onclick="ProjectsDelete()"><span class="iconred icon-trash" title="Delete"></span></span>
							</span>
							<span class="btnst" style="display:none;" id="projectsstatus">
								<span class="st0" title="Not executed"></span>
								<span class="st2 d-n" title="In the process"></span>
								<span class="st1 d-n" title="Done"></span>
								<span class="st3 d-n" title="Closed"></span>
								<span class="st4" title="Archive"></span>
							</span>
							<span class="link d-n" onclick="Page(\'wcalendar&act=projects\',\'0\',\'\')"><span class="icon3 icon-calendar" title="Календарь"></span></span>';
					
					// $html .= '<span class="f-l p-5">&nbsp;</span>';
					//echo $this->uid;
					$users = $this->q("SELECT DISTINCT u.id,u.login FROM tasks t
						INNER JOIN users u ON u.id=t.user
						WHERE t.uid='0' AND t.user>0 AND t.sid<>4");
					if(mysql_num_rows($users)>0||mysql_num_rows($this->q("SELECT DISTINCT u.id,u.login FROM users u WHERE u.uid=0"))>0){
						$html .= '&nbsp;&nbsp;&nbsp;By employee: <select onchange="if(this.value==0){jQuery(\'.btnpuser\').removeClass(\'active\');jQuery(\'li.project\').show();}else{jQuery(\'.btnpuser\').removeClass(\'active\');jQuery(this).addClass(\'active\');jQuery(\'li.project\').hide();jQuery(\'.projectuser\'+this.value+\'\').parent().parent().parent().parent().show();}">';
						$html .= '<option value="0">All users</option>';
						$us = array();
						while($u = mysql_fetch_assoc($users)){
							$html .= '<option value="'.$u['id'].'">'.$u['login'].'</option>';
							$us[] = $u['id'];
						}
						
						// $html .= '<span class="f-l p-5">|</span>';
						
						// $users2 = $this->q("SELECT DISTINCT u.id,u.login,u.name FROM users u WHERE u.uid=0 AND u.type=2 AND u.id NOT IN (".implode(',',$us).")");
						// while($u = mysql_fetch_assoc($users2)){
							// $html .= '<option value="'.$u['id'].'">'.($u['name']==''?'':$u['name'].' ').''.$u['login'].'</option>';
						// }
						
						$html .= '</select>';
					}
					// $html .= '<span class="f-l p-5">&nbsp;</span>';
					$clients = $this->q("SELECT DISTINCT c.id,c.name FROM users AS c
						INNER JOIN projects AS p ON
							p.uid='0'
							AND (p.sid=0 OR p.sid=1 OR p.sid=2 OR p.sid=6 OR p.sid=5)
							".($idclient>0?"AND p.cid='".$idclient."'":"")."
						WHERE
							c.type=3
							AND p.cid=c.id
							AND c.type=".$cstatus."
						ORDER BY p.id ASC");
					if(mysql_num_rows($clients)>0||mysql_num_rows($this->q("SELECT DISTINCT c.id,c.name FROM users c WHERE c.type=".$cstatus." AND c.uid=0"))>0){
						// $html .= '<div class="cl-b o-h" style="border-top:1px solid #999;padding-top:5px;">';
						$html .= '&nbsp;&nbsp;&nbsp;By client: <select onchange="if(this.value==0){jQuery(\'.btncuser\').removeClass(\'active\');jQuery(\'li.project\').show();}else{jQuery(\'.btncuser\').removeClass(\'active\');jQuery(this).addClass(\'active\');jQuery(\'li.project\').hide();jQuery(\'.projectclient\'+this.value+\'\').show();}">';
						$html .= '<option value="0">All clients</option>';
						$cs = array();
						while($c = mysql_fetch_assoc($clients)){
							$html .= '<option value="'.$c['id'].'">'.$c['name'].'</option>';
							$cs[] = $c['id'];
						}
						$html .= '</select>';
						// $html .= '<span class="f-l p-5">|</span>';
						
						// $users2 = $this->q("SELECT DISTINCT u.id,u.login FROM jos_whm u WHERE u.type='user' AND u.user=".$this->uid." AND u.id NOT IN (".implode(',',$us).")");
						// while($u = mysql_fetch_assoc($users2)){
							// $html .= '<span class="btn f-l btnpuser" style="width:auto;padding:2px 8px;" onclick="if(jQuery(this).hasClass(\'active\')){jQuery(\'.btnpuser\').removeClass(\'active\');jQuery(\'li.project\').show();}else{jQuery(\'.btnpuser\').removeClass(\'active\');jQuery(this).addClass(\'active\');jQuery(\'li.project\').hide();jQuery(\'.projectuser'.$u['id'].'\').parent().parent().parent().parent().show();}">'.$u['login'].'</span>';
						// }
						
						// $html .= '</div>';
					}
					$html .= '</span></h1>';
				}
				$html .= '<div class="left">';
				$sql = "SELECT p.*,c.name AS cname FROM users c
					INNER JOIN projects p ON
						p.uid='0'
						AND (p.sid=0 OR p.sid=1 OR p.sid=2 OR p.sid=6 OR p.sid=5)
						".($idclient>0?"AND p.cid='".$idclient."'":"")."
					WHERE
						p.cid=c.id
						AND c.type=".$cstatus."
					ORDER BY p.id ASC";
				if(mysql_num_rows($this->q($sql))>0){
					$html .= '<h2>'.$this->lng['Own projects'].' ('.mysql_num_rows($this->q($sql)).')</h2>';
					$html .= $this->projectecho($sql,'demo');
				}
				
				$sql = "SELECT p.*,c.name AS cname FROM users c
					INNER JOIN projects p ON
						p.uid='0'
						AND p.sid=3
						".($idclient>0?"AND p.cid='".$idclient."'":"")."
					WHERE
						p.cid=c.id
						AND c.type=".$cstatus."
					ORDER BY p.id ASC";
				if(mysql_num_rows($this->q($sql))>0){
					$html .= '<button onclick="Page(\'projects&act=closed\',e(\'ownclosedprojects\'));jQuery(\'.hideownclosedprojects\').show()">Show closed projects '.mysql_num_rows($this->q($sql)).'</button>';
					$html .= '<button class="hideownclosedprojects" onclick="jQuery(this).hide();jQuery(\'#ownclosedprojects\').html(\'\');" style="display:none;">Hide closed projects</button>';
					$html .= '<div id="ownclosedprojects"></div>';
				}
				
				$sql = "SELECT p.*,c.name AS cname FROM users c
					INNER JOIN projects p ON
						p.uid='0'
						AND p.sid=4
						".($idclient>0?"AND p.cid='".$idclient."'":"")."
					WHERE
						p.cid=c.id
						AND c.type=".$cstatus."
					ORDER BY p.id ASC";
				if(mysql_num_rows($this->q($sql))>0){
					// $html .= '<h3>Own projects</h3>';
					$html .= '<button onclick="Page(\'projects&act=archive\',e(\'ownarchiveprojects\'));jQuery(\'.hideownarchiveprojects\').show()">Show archive projects '.mysql_num_rows($this->q($sql)).'</button>';
					$html .= '<button class="hideownarchiveprojects" onclick="jQuery(this).hide();jQuery(\'#ownarchiveprojects\').html(\'\');" style="display:none;">Hide archive projects</button>';
					$html .= '<div id="ownarchiveprojects"><script>jQuery(function(){Page(\'projects&act=archive\',e(\'ownarchiveprojects\'));jQuery(\'.hideownarchiveprojects\').show();});</script></div>';
					// $html .= $this->projectecho($sql);
				}
				
				
				$sql = "
					SELECT DISTINCT p.id,p.*
					FROM tasks t
						INNER JOIN projects p
						ON
							p.id=t.pid
					WHERE
						t.user='0' AND t.uid=0
					ORDER BY p.id ASC
					";
				if(mysql_num_rows($this->q($sql))>0){
					$html .= '<h2>'.$this->lng['Attached projects'].' ('.mysql_num_rows($this->q($sql)).')</h2>';
					$html .= $this->projectecho($sql,'demo');
				}
				
				$html .= '</div>';
				$html .= '<div class="center" id="center"></div>';
				$html .= '<div class="cl-b"></div>';
				
				
				break;
			}
			default:{
				$html = '<h1>'.$this->lng['Projects'].' &rarr; ';
				// echo '<pre>';print_r($this);echo '</pre>';
				if($idclient==0){
					$html .= '<span class="fs-14">
							<span class="link'.($cstatus==3?' active':'').'" style="width:auto;" onclick="Page(\'projects&cstatus=3\',e(\'content\'));jQuery(this).parent().find(\'span.btn\').removeClass(\'active\');jQuery(this).addClass(\'active\');">'.$this->lng['Clients'].'</span>
							<span class="link'.($cstatus==4?' active':'').'" style="width:auto;" onclick="Page(\'projects&cstatus=4\',e(\'content\'));jQuery(this).parent().find(\'span.btn\').removeClass(\'active\');jQuery(this).addClass(\'active\');">'.$this->lng['Contractors'].'</span>
							<span class="link d-n" style="width:auto;padding:5px 15px;" onclick="Page(\'projects&act=tasks&cstatus=0\',e(\'content\'))">'.$this->lng['Tasks Clients'].'</span>
							<span class="link d-n" onclick="window.location=\'archive-projects.html\';"><span class="icon3 icon-folder-collapsed" title="'.$this->lng['Archive'].'"></span></span>
							<span id="selall" class="link d-n" onclick="jQuery(\'.projectcheck\').attr(\'checked\',true);jQuery(\'#projectsoperations,#deselall\').show();jQuery(\'#selall\').hide();ProjectsStatus()"><span class="icongreen icon-check" title="'.$this->lng['Select all'].'"></span></span></li>
							<span id="deselall" style="display:none;" class="btn f-l" onclick="jQuery(\'.projectcheck\').attr(\'checked\',false);jQuery(\'#projectsoperations,#deselall\').hide();jQuery(\'#selall\').show();ProjectsStatus()"><span class="iconred icon-cancel" title="'.$this->lng['Deselect'].'"></span></span></li>
							<span id="projectsoperations" style="display:none;">
								<span class="btn f-l" onclick="ProjectsDelete()"><span class="iconred icon-trash" title="Delete"></span></span>
							</span>
							<span class="btnst" style="display:none;" id="projectsstatus">
								<span class="st0" title="Not executed"></span>
								<span class="st2 d-n" title="In the process"></span>
								<span class="st1 d-n" title="Done"></span>
								<span class="st3 d-n" title="Closed"></span>
								<span class="st4" title="Archive"></span>
							</span>
							<span class="link d-n" onclick="Page(\'wcalendar&act=projects\',\'0\',\'\')"><span class="icon3 icon-calendar" title="Календарь"></span></span>';
					
					// $html .= '<span class="f-l p-5">&nbsp;</span>';
					//echo $this->uid;
					$users = $this->q("SELECT DISTINCT u.id,u.login FROM tasks t
						INNER JOIN users u ON u.id=t.user
						WHERE t.uid='".$this->uid."' AND t.user>0 AND t.sid<>4");
					if(mysql_num_rows($users)>0||mysql_num_rows($this->q("SELECT DISTINCT u.id,u.login FROM users u WHERE u.uid=".$this->uid.""))>0){
						$html .= '&nbsp;&nbsp;&nbsp;By employee: <select onchange="if(this.value==0){jQuery(\'.btnpuser\').removeClass(\'active\');jQuery(\'li.project\').show();}else{jQuery(\'.btnpuser\').removeClass(\'active\');jQuery(this).addClass(\'active\');jQuery(\'li.project\').hide();jQuery(\'.projectuser\'+this.value+\'\').parent().parent().parent().parent().show();}">';
						$html .= '<option value="0">All users</option>';
						$us = array();
						while($u = mysql_fetch_assoc($users)){
							$html .= '<option value="'.$u['id'].'">'.$u['login'].'</option>';
							$us[] = $u['id'];
						}
						
						// $html .= '<span class="f-l p-5">|</span>';
						
						$users2 = $this->q("SELECT DISTINCT u.id,u.login,u.name FROM users u WHERE u.uid=".$this->uid." AND u.type=2 AND u.id NOT IN (".implode(',',$us).")");
						while($u = mysql_fetch_assoc($users2)){
							$html .= '<option value="'.$u['id'].'">'.($u['name']==''?'':$u['name'].' ').''.$u['login'].'</option>';
						}
						
						$html .= '</select>';
					}
					// $html .= '<span class="f-l p-5">&nbsp;</span>';
					$clients = $this->q("SELECT DISTINCT c.id,c.name FROM users AS c
						INNER JOIN projects AS p ON
							p.uid='".$this->uid."'
							AND (p.sid=0 OR p.sid=1 OR p.sid=2 OR p.sid=6 OR p.sid=5)
							".($idclient>0?"AND p.cid='".$idclient."'":"")."
						WHERE
							c.type=3
							AND p.cid=c.id
							AND c.type=".$cstatus."
						ORDER BY p.id ASC");
					if(mysql_num_rows($clients)>0||mysql_num_rows($this->q("SELECT DISTINCT c.id,c.name FROM users c WHERE c.type=".$cstatus." AND c.uid=".$this->uid.""))>0){
						// $html .= '<div class="cl-b o-h" style="border-top:1px solid #999;padding-top:5px;">';
						$html .= '&nbsp;&nbsp;&nbsp;By client: <select onchange="if(this.value==0){jQuery(\'.btncuser\').removeClass(\'active\');jQuery(\'li.project\').show();}else{jQuery(\'.btncuser\').removeClass(\'active\');jQuery(this).addClass(\'active\');jQuery(\'li.project\').hide();jQuery(\'.projectclient\'+this.value+\'\').show();}">';
						$html .= '<option value="0">All clients</option>';
						$cs = array();
						while($c = mysql_fetch_assoc($clients)){
							$html .= '<option value="'.$c['id'].'">'.$c['name'].'</option>';
							$cs[] = $c['id'];
						}
						$html .= '</select>';
						// $html .= '<span class="f-l p-5">|</span>';
						
						// $users2 = $this->q("SELECT DISTINCT u.id,u.login FROM jos_whm u WHERE u.type='user' AND u.user=".$this->uid." AND u.id NOT IN (".implode(',',$us).")");
						// while($u = mysql_fetch_assoc($users2)){
							// $html .= '<span class="btn f-l btnpuser" style="width:auto;padding:2px 8px;" onclick="if(jQuery(this).hasClass(\'active\')){jQuery(\'.btnpuser\').removeClass(\'active\');jQuery(\'li.project\').show();}else{jQuery(\'.btnpuser\').removeClass(\'active\');jQuery(this).addClass(\'active\');jQuery(\'li.project\').hide();jQuery(\'.projectuser'.$u['id'].'\').parent().parent().parent().parent().show();}">'.$u['login'].'</span>';
						// }
						
						// $html .= '</div>';
					}
					$html .= '</span></h1>';
				}
				$html .= '<div class="left">';
				$sql = "SELECT p.*,c.name AS cname FROM users c
					INNER JOIN projects p ON
						p.uid='".$this->uid."'
						AND (p.sid=0 OR p.sid=1 OR p.sid=2 OR p.sid=6 OR p.sid=5)
						".($idclient>0?"AND p.cid='".$idclient."'":"")."
					WHERE
						p.cid=c.id
						AND c.type=".$cstatus."
					ORDER BY p.id ASC";
				if(mysql_num_rows($this->q($sql))>0){
					$html .= '<h2>'.$this->lng['Own projects'].' ('.mysql_num_rows($this->q($sql)).')</h2>';
					$html .= $this->projectecho($sql);
				}
				
				$sql = "SELECT p.*,c.name AS cname FROM users c
					INNER JOIN projects p ON
						p.uid='".$this->uid."'
						AND p.sid=3
						".($idclient>0?"AND p.cid='".$idclient."'":"")."
					WHERE
						p.cid=c.id
						AND c.type=".$cstatus."
					ORDER BY p.id ASC";
				if(mysql_num_rows($this->q($sql))>0){
					$html .= '<button onclick="Page(\'projects&act=closed\',e(\'ownclosedprojects\'));jQuery(\'.hideownclosedprojects\').show()">Show closed projects '.mysql_num_rows($this->q($sql)).'</button>';
					$html .= '<button class="hideownclosedprojects" onclick="jQuery(this).hide();jQuery(\'#ownclosedprojects\').html(\'\');" style="display:none;">Hide closed projects</button>';
					$html .= '<div id="ownclosedprojects"></div>';
				}
				
				$sql = "SELECT p.*,c.name AS cname FROM users c
					INNER JOIN projects p ON
						p.uid='".$this->uid."'
						AND p.sid=4
						".($idclient>0?"AND p.cid='".$idclient."'":"")."
					WHERE
						p.cid=c.id
						AND c.type=".$cstatus."
					ORDER BY p.id ASC";
				if(mysql_num_rows($this->q($sql))>0){
					// $html .= '<h3>Own projects</h3>';
					$html .= '<button onclick="Page(\'projects&act=archive\',e(\'ownarchiveprojects\'));jQuery(\'.hideownarchiveprojects\').show()">Show archive projects '.mysql_num_rows($this->q($sql)).'</button>';
					$html .= '<button class="hideownarchiveprojects" onclick="jQuery(this).hide();jQuery(\'#ownarchiveprojects\').html(\'\');" style="display:none;">Hide archive projects</button>';
					$html .= '<div id="ownarchiveprojects"><script>jQuery(function(){Page(\'projects&act=archive\',e(\'ownarchiveprojects\'));jQuery(\'.hideownarchiveprojects\').show();});</script></div>';
					// $html .= $this->projectecho($sql);
				}
				
				
				$sql = "
					SELECT DISTINCT p.id,p.*
					FROM tasks t
						INNER JOIN projects p
						ON
							p.id=t.pid
					WHERE
						t.user='".$this->uid."'
					ORDER BY p.id ASC
					";
				if(mysql_num_rows($this->q($sql))>0){
					$html .= '<h2>'.$this->lng['Attached projects'].' ('.mysql_num_rows($this->q($sql)).')</h2>';
					$html .= $this->projectecho($sql);
				}
				
				$html .= '</div>';
				$html .= '<div class="center" id="center"></div>';
				$html .= '<div class="cl-b"></div>';
				
				break;
			}
		}
		return $html;
	}
	function projectecho($sql,$home=''){
		$html = '';
		$all = $this->q($sql);
		// $valute = $this->q("SELECT * FROM jos_whm AS v WHERE v.type='valute' ORDER BY v.id ASC");
		// $val = array();
		// while($v = mysql_fetch_assoc($valute)){
			// $val[$v['id']] = $v['name'];
			// $conv[$v['id']] = $v['contacts'];
		// }
		$projects_array = array();
		$y = 0;
		// $html .= mysql_num_rows($all).' - '.$sql;
		$fin = new fin();
		while($a = mysql_fetch_assoc($all)){
			$status = $this->q("SELECT t.id,t.user FROM tasks t WHERE t.sid!=4 AND t.pid='".$a['id']."'");
			$taskscostsum = mysql_fetch_assoc($this->q("SELECT SUM(t.cost) AS s FROM tasks t WHERE t.pid='".$a['id']."'"));
			$st = 0;
			$i = 0;
			$z = 0;
			$w = 0;
			if($a['sid']!=3&&$a['sid']!=4&&mysql_num_rows($status)>0){
				while($s = mysql_fetch_assoc($status)){
					$st3 = $this->taskstatus($s['id']);
					$st += $st3[0];
					if($st3[1]==1){$z = 1;}
					if($st3[2]==1){$w = 1;}
					$u[] = $s['user'];
					$i++;
				}
				if($i>0){$st = round($st/$i);}
				// elseif($z==1){$st = -1;}
				else{$st = 0;}
				
				if($st==0){$stcolor = 'c-f00';$sst = 0;}
				elseif($st==100){$stcolor = 'c-green2';$sst = 1;}
				else{$stcolor = 'c-rose';$sst = 2;}
				
				if($w==1){$stcolor = 'c-blue';$sst = 6;}
				if($z==1){$stcolor = 'c-orange';$sst = 5;}
				
				$st2 = $st;
				$st .= '%';
			}
			elseif($a['sid']==3){
				while($s = mysql_fetch_assoc($status)){
					$st3 = $this->taskstatus($s['id']);
					$st += $st3[0];
					$u[] = $s['user'];
					$i++;
				}
				if($i>0){$st = round($st/$i);}
				else{$st = 0;}
				$stcolor = 'c-grey';
				$st2 = $st;
				$st .= '%';
				$sst = 3;
			}
			elseif($a['sid']==4){
				// while($s = mysql_fetch_assoc($status)){
					// $st3 = $this->taskstatus($s['id']);
					// $st += $st3[0];
					// $u[] = $s['users'];
					// $i++;
				// }
				// if($i>0){$st = round($st/$i);}
				// else{$st = 0;}
				$stcolor = 'c-000';
				// $st2 = $st;
				// $st .= '%';
				$st = '';
				$st2 = '';
				$sst = 4;
			}
			else{
				$st = '';
				$st2 = '';
				$sst = 1;
				$stcolor = 'c-grey';
			}
			//echo $st2.'<br />';
			$params = explode(':',$a['sets']);
			$acost = $fin->finance('project',$a['id']);
			$projects_array[$sst][$a['priority']][$y]['status'] = $st2;
			// $a['pcost'] = $acost['s1']-$acost['s2'];
			$a['pcost'] = $taskscostsum['s']+$params[0]-$acost['s1'];
			$projects_array[$sst][$a['priority']][$y]['content'] = $this->project($a,$st,$sst,$stcolor,$home);
			$projects_array[$sst][$a['priority']][$y]['id'] = $a['id'];
			$projects_array[$sst][$a['priority']][$y]['user'] = $a['uid'];
			$projects_array[$sst][$a['priority']][$y]['client'] = $a['cid'];
			$projects_array[$sst][$a['priority']][$y]['priority'] = $a['priority'];
			$projects_array[$sst][$a['priority']][$y]['cost'] = $params[0]+$taskscostsum['s'];
			// $projects_array[$sst][$a['priority']][$y]['costplaninreg'] = $a['costplaninreg'];
			// $projects_array[$sst][$a['priority']][$y]['costplanoutreg'] = $a['costplanoutreg'];
			$y++;
		}
		
		$priority = array(2,1,0);
		$priority_color = array('green','orange','red');
		$st_array = array(0,5,6,2,1,3,4);
		$st_array_title = array(0=>''.$this->lng['Not in progress'].'',5=>''.$this->lng['Need to discuss'].'',2=>''.$this->lng['Paused'].'',6=>''.$this->lng['In progress'].'',1=>''.$this->lng['Done'].'',3=>''.$this->lng['Closed Projects'].'',4=>''.$this->lng['Archive Projects'].'');
		
		// echo '<pre>';print_r($projects_array);echo '</pre>';
		$html .= '<div class="projectblocks">';
		$k = 1;
		$html .= '<div class="projectblock">';
		//$html .= '<h3>'.$st_array_title[$st].'</h3>';
		$html .= '<ul class="projects">';
		foreach($st_array as $st){
			foreach($projects_array as $key=>$projects){
				if($key==$st){
					foreach($priority as $prio){
						foreach($projects as $key2=>$projects2){
							if($key2==$prio){
								rsort($projects2);
								foreach($projects2 as $p){
								$html .= '<li class="project projectclient'.$p['cid'].' pst'.$st.'" id="project'.$p['id'].'">
									<span class="prio0 icon'.$priority_color[$p['priority']].' icon-bullet f-l" title="priority"></span>
									<table cellspacing="0" cellpadding="3" border="0" class="tbl">
										<tr>
											'.($p['user']==$this->uid?'
											<td width="10" class="fs-14 ta-c va-m" onclick="Project(\''.$p['id'].'\');" style="cursor:pointer;">
												<input class="projectcheck f-l" type="checkbox" onclick="ProjectsCheck();" value="'.$p['id'].'" /><br />
											</td>
											':'').'
											<td width="20" class="fs-14 ta-c va-m" onclick="Project(\''.$p['id'].'\');" style="cursor:pointer;">
												'.$k.'.
											</td>
											'.$p['content'].'
										<div id="project_tasks'.$p['id'].'bg" class="project_tasksbg" onclick="Project(\''.$p['id'].'\');"></div>
										<div id="project_tasks'.$p['id'].'" class="project-tasks">
											<div class="arrow"></div>
											<div id="project_tasks'.$p['id'].'in"></div>
										</div>
										<div id="pftp'.$p['id'].'" style="display:none;padding: 0 0 20px;"></div>
									</li>';
								$k++;
								}
							}
						}
					}
					// }
					
				}
			}
		}
		$html .= '</ul>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<br class="cl-b" />';
		return $html;
	}
	function project($p,$st,$sst,$stcolor,$home){
		$html = '';
		$i = 0;
		$u = array();
		$tcost = 0;
		$tcostplanin = 0;
		$counttasks = 0;
		$tasks = $this->q("SELECT * FROM tasks t WHERE t.pid='".$p['id']."'");
		
		while($t = mysql_fetch_assoc($tasks)){
			if($t['user']>0&&!in_array($t['user'],$u)&&$t['sid']!=4){
				$u[] = $t['user'];
			}
			$counttasks++;
		}
		$hours = mysql_fetch_assoc($this->q("SELECT SUM(b.time) AS t FROM tasks a LEFT JOIN items b ON b.tid=a.id WHERE a.pid='".$p['id']."' AND a.sid!='4' AND a.uid='".$this->uid."'"));
		$hours = $hours['t'];
		$hours2 = (int)(($hours-(int)($hours/(24*60*60))*24*60*60+(int)($hours/(24*60*60))*24*60*60)/(60*60));
		$minutes = (int)(($hours-(int)($hours/(24*60*60))*24*60*60-(int)(($hours-(int)($hours/(24*60*60))*24*60*60)/(60*60))*(60*60))/(60));
		$cost = $p['pcost'];
		$priority = array('green','orange','red');
		$projectuser = '';
		foreach($u as $k=>$v){
			$projectuser .= ' projectuser'.$v;
		}
		
		$html .= '
					'.($p['uid']==$this->uid?'
					<td width="90" class="c-green fs-12 ta-r va-t projectprice d-n">
						<sup class="c-999" style="right:2px;top:0px;text-align: right;width: 150px;">'.number_format($hours/3600,2,'.',' ').'h ('.$hours2.':'.(strlen($minutes)==1?'0':'').$minutes.')</sup>
						<span style="'.($p['uid']==$this->uid?'top:6px;':'').'" onclick="Page(\'finance&project='.$p['id'].'\',\'0\')">'.
							(abs($cost)>0?'<span class="c-'.($cost>0?'green2':'grey').' cr-p">'.($p['vid']==768?$this->val[$p['vid']].''.number_format($cost,0,'.',' '):number_format($cost,0,'.',' ').' '.$this->val[$p['vid']]).'</span>':'-')
						.'
						</span>
					</td>
					<td width="30" class="c-666 fs-12 ta-l va-t d-n projecttasks projectusers'.$projectuser.'" onclick="Project(\''.$p['id'].'\');" style="cursor:pointer;">
						<span class="f-r p-1" style="width:30px;margin:0 2px 0 0;position:absolute;top:0;"><span class="iconlblue icon-document" title="Tasks"><span style="font-size:11px;position:absolute;right:-10px;top:1px;">'.$counttasks.'</span></span></span>
						<span class="f-r p-1" style="width:30px;margin:0 2px 0 0;position:absolute;top:13px;"><span class="icon3 icon-person" title="Users"><span style="font-size:11px;position:absolute;right:-10px;top:1px;">'.count($u).'</span></span></span>
					</td>
					':'').'
					<td width="'.($p['uid']==$this->uid?'110':'44').'" class="d-n va-t">
						
					</td>
					<td width="60" align="center" class="d-n va-t" onclick="Project(\''.$p['id'].'\');" style="cursor:pointer;"><span class="fs-10" title="All days"><b>'.floor((strtotime($p['date2'])-strtotime($p['date1']))/(60*60*24)).'</b>'.($p['status']==1||$p['status']==3?'':' (<span class="c-'.(floor((strtotime($p['date2'])-strtotime(date('Y-m-d H:i:s')))/(60*60*24))>0?'green2':'f00').' fs-12" title="Осталось дней">'.floor((strtotime($p['date2'])-strtotime(date('Y-m-d H:i:s')))/(60*60*24)).'</span>)').'</span></td>
					<td class="fs-14 va-t" onclick="Project(\''.$p['id'].'\');" style="cursor:pointer;">
						'.($p['uid']==$this->uid?'<sup class="c-333 fs-12" style="left:3px;top:0px;height: 15px;overflow: hidden;">'.((strlen($p['cname'])>50?mb_substr($p['cname'],0,50,'utf-8').'':$p['cname'])==''?'-':(strlen($p['cname'])>50?mb_substr($p['cname'],0,50,'utf-8').'':$p['cname'])).'</sup>':'').'
						<div>
							<span '.($st==3?'title="Closed Project" class="projectclosed':
										($st==4?'title="Project in Archive" class="projectarchive':
											($p['pcost']==0?'title="FREE Project" class="projectfree':
												($p['pcost']>0?'title="Once Project" class="projectonce':
													'title="Project" class="projectproject'
												)
											)
										)
									).'
							" style="'.($p['uid']==$this->uid?'padding-top:12px;':'').'">'.$p['name'].'</span>
						</div>
					</td>
					<td class="cr-p ta-r va-m">
						<div align="right" class="projectprogress cl-b fs-14'.($st==''?'':' '.$stcolor).'" onclick="Project(\''.$p['id'].'\');" onclick="Project(\''.$p['id'].'\');">'.$st.'</div>
					</td>
				</tr>	
			</table>
			';
			// if(jQuery(\'#pftp'.$p['id'].'\').is()){jQuery(\'#pftp'.$p['id'].'\').hide();}else{Page(\'ftp&pid='.$p['id'].'\',e(\'pftp'.$p['id'].'\'));}
		return $html;
	}
	
	function tasks($idproject=0){
		// echo $this->r('idin');
		$fin = new fin();
		$idproject = $this->r('idproject')>0?$this->r('idproject'):($this->r('idproject')=='all'?'':$idproject);
		// $valute = $this->q("SELECT id,name FROM jos_whm AS v WHERE v.type='valute' ORDER BY v.id ASC");
		// $val = array();
		// $val[0] = '$';
		$allusers = array();
		// while($v = mysql_fetch_assoc($valute)){
			// $val[$v['id']] = $v['name'];
		// }
		$home = $idproject=='home'?'home':'';
		
		$html = '';
		$uid = $this->uid;
		if($this->r('idin')=='user'&&$this->uid==818){
			$uid = $this->r('idin2');
		}
		
		if($idproject>0){
			$project = mysql_fetch_assoc($this->q("SELECT * FROM projects p WHERE id='".$idproject."'"));
			$idproject = $idproject;
		}
		elseif($this->r('idin')>0){
			$idproject = $this->r('idin');
			$project = mysql_fetch_assoc($this->q("SELECT * FROM projects p WHERE id='".$this->r('idin')."'"));
		}
		else{
			$idproject = 0;
			$project = array();
		}
		$all = $this->q("SELECT
				t.*,
				p.name AS pname,
				p.vid AS pval
			FROM
				projects p
			LEFT JOIN tasks t
			ON
				t.pid=p.id
				AND t.sid!=4
				
			WHERE
				".($idproject=='home'?"t.public=1":"p.id='".$idproject."' AND (t.uid='".$uid."' OR t.user='".$uid."')")."
				
			ORDER BY t.date DESC");
		// $html .= mysql_num_rows($all);
		
		if($this->r('idin')=='user'&&$this->uid!=818){
			$users = '';
		}
		elseif($idproject=='home'){
			
		}
		else{
			$uall = $this->q("SELECT DISTINCT t.user,u.name,u.login FROM tasks t INNER JOIN users u ON u.id=t.user WHERE t.sid!=4 AND t.pid=".$idproject." AND (t.uid=".$uid." OR t.user='".$uid."')");
			while($a = mysql_fetch_assoc($uall)){
				$allusers[$a['user']] = $a['login'];
			}
			// $users = '<ul class="addedusers o-h">';
			$users = '';
			foreach($allusers as $k=>$u){
				$users .= '<span class="btn f-l'.($this->r('idin')=='user'&&$this->r('idin2')==$k?' active':'').'" style="width:auto;padding:5px 10px;" onclick="if(hasClass(this,\'active\')){jQuery(\'.tasks>li\').show();jQuery(this).parent().find(\'span\').removeClass(\'active\');}else{jQuery(\'.tasks>li\').hide();jQuery(\'.tasks>li.user'.$k.'\').show();jQuery(this).parent().find(\'span\').removeClass(\'active\');jQuery(this).addClass(\'active\');}">'.$u.'</span>';
			}
			// $users .= '</ul>';
		}
		$params = explode(':',$project['sets']);
		$taskscostsum = mysql_fetch_assoc($this->q("SELECT SUM(t.cost) AS s FROM tasks t WHERE t.pid='".$project['id']."'"));
		$acost = $fin->finance('project',$project['id']);
		// $pcost = $taskscostsum['s']+$params[0]-$acost['s1'];
		$html .= '
			<div class="f-r z-1">
				<span class="btn f-r p-10" style="margin:0 2px 0 0;" onclick="Page(\'ftp&pid='.$project['id'].'\',\'f\',\''.$project['name'].'|'.$project['id'].'\')"><span class="iconblue icon-transferthick-e-w" title="Ftp connection (beta version)"></span></span>
				<span class="btn f-r p-10" style="margin:0 2px 0 0;" onclick="jQuery(\'.pinfo'.$project['id'].'\').slideToggle(200)"><span class="iconlblue icon-info" title="Additional information"></span></span>
				'.($project['uid']==$this->uid?'
				<span class="btn f-r p-10" style="margin:0 2px 0 0;" onclick="if(confirm(\'Are You sure You want to delete this project?\')){ProjectStatus(\'delete\',\''.$project['id'].'\');}"><span class="iconred icon-trash" title="Delete"></span></span>
				<span class="btn f-r p-10 btnarchive" style="margin:0 2px 0 0;" onclick="'.($project['sid']==4?'ProjectStatus(\'active\',\''.$project['id'].'\');':'ProjectStatus(\'archive\',\''.$project['id'].'\');').'"><span class="iconorange icon-folder-collapsed" title="Archive"></span></span>
				<span class="btn f-r p-10" style="margin:0 2px 0 0;" onclick="Page(\'edit&act=project&pid='.$project['id'].'&k=&st='.$st.'&sst='.$sst.'&stcolor='.$stcolor.'\',\'0\');"><span class="iconlblue icon-pencil" title="Edit"></span></span>
				':'').'
				<span class="f-r p-10 fs-16">
					'.($project['vid']==768?
						'<span class="c-green"><span class="fs-12">'.$this->val[$project['vid']].'</span>'.($taskscostsum['s']+$params[0]).'</span> - <span class="c-red"><span class="fs-12">'.$this->val[$project['vid']].'</span>'.$acost['s1'].'</span> = <span class="c-'.(($taskscostsum['s']+$params[0]-$acost['s1'])>0?'green':'red').'"><span class="fs-12">'.$this->val[$project['vid']].'</span>'.($taskscostsum['s']+$params[0]-$acost['s1']).'</span>'
						:
						'<span class="c-green">'.($taskscostsum['s']+$params[0]).'<span class="fs-12">'.$this->val[$project['vid']].'</span></span> - <span class="c-red">'.$acost['s1'].'<span class="fs-12">'.$this->val[$project['vid']].'</span></span> = <span class="c-'.(($taskscostsum['s']+$params[0]-$acost['s1'])>0?'green':'red').'">'.($taskscostsum['s']+$params[0]-$acost['s1']).'<span class="fs-12">'.$this->val[$project['vid']].'</span></span>'
					).'
				</span>
			</div>';
		$html .= ($idproject=='home'?'':($this->r('view')=='clear'?'':'
			<span class="btn p-1 d-n" style="position:absolute;z-index:10;right:30px;top:5px;" onclick="TasksResize(\''.$project['id'].'\');jQuery(this).toggleClass(\'active\')"><span class="icon3 icon-newwin" title="Expand"></span></span>
			<span class="close d-n" onclick="Project(\''.$project['id'].'\');"><span class="icon3 icon-close" title="Close"></span></span>
			<h2 style="padding:10px 0px;margin:0;"><b>'.$project['name'].'</b></h2>
			<div class="pinfo'.$project['id'].'" style="display:none;">'.$this->txt2link(str_replace('[-quot-]',"'",str_replace('[-amp-]','&',str_replace('<br />',' <br />',$project['description'])))).'</div>
			').'
			<div class="control" style="margin:0;">
				<span class="btn f-l bg-green" onclick="jQuery(\'#addtaskform'.$project['id'].'\').show();Page(\'add&act=item&idproject='.$project['id'].'\',e(\'addtaskform'.$project['id'].'\'),\'\');"><span class="icon0 icon-plus" title="Add task"></span></span>
				<span id="selall" class="btn f-l d-n" onclick="jQuery(\'.taskcheck\').attr(\'checked\',true);jQuery(\'#tasksoperations,#deselall\').show();jQuery(\'#selall\').hide();"><span class="icongreen icon-check" title="Select all"></span></span>
				<span id="deselall" style="display:none;" class="btn f-l" onclick="jQuery(\'.taskcheck\').attr(\'checked\',false);jQuery(\'#tasksoperations,#deselall\').hide();jQuery(\'#selall\').show();"><span class="iconred icon-cancel" title="Deselect"></span></span>
				<span class="btn f-l" style="width:40px;" onclick="Page(\'archive&act=tasks&project='.$project['id'].'\',e(\'taskitems'.$project['id'].'archivein\'));jQuery(\'#taskitems'.$project['id'].'archive\').toggle(250);"><span class="iconorange icon-folder-collapsed" title="Archive"></span><span style="position:absolute;display:block;right:10px;top:6px;">'.mysql_num_rows($this->q("SELECT t.*,p.name AS pname,p.description AS pcont FROM projects p LEFT JOIN tasks t ON t.pid=p.id AND t.sid=4 WHERE p.id='".$idproject."' AND (t.uid='".$uid."' OR t.user='".$uid."') ORDER BY t.date DESC")).'</span></span>
				<span id="tasksoperations'.$idproject.'" style="display:none;">
					<span class="btn f-l" onclick="TasksDelete()"><span class="iconred icon-trash" title="Delete"></span></span>
					<span class="btn f-l" onclick="TasksArchive(\''.$idproject.'\');"><span class="iconorange icon-folder-open" title="To the archive"></span></span>
				</span>
				'.$users.'
			</div>');
		$tasks_array = array();
		$tasks_users_array = array();
		$n = 1;
		$i = 0;
		$html .= '<div id="addtaskform'.$project['id'].'" style="display:none;background:#eee;padding:0px;margin:10px 0px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;">
					<span class="close" onclick="jQuery(\'#addtaskform\').hide()"><span class="icon3 icon-closethick" title="Close"></span></span>
					<table cellpadding="5" cellspacing="0" border="0">
						<tr>
							<td colspan="2">
								<input type="text" name="name" value="Task Title..." />
								<select name="project">
									<option value="0">No project</option>
								</select>
								<input type="text" name="projectname" value="Project Title..." />
								<input type="text" name="dateend" value="'.date('Y-m-d').'" onclick="jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd\'}).focus();" />
							</td>
						</tr>
						<tr>
							<td>
								<textarea name="text" cols="80"></textarea>
							</td>
							<td>
								<input type="button" name="newrow" value="New Row" />
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<input type="submit" name="submit" value="Add" />
								<input type="button" name="cancel" value="Cancel" />
							</td>
						</tr>
					</table>
				</div>';
		if(mysql_num_rows($all)>0){
			while($a = mysql_fetch_assoc($all)){
				$s = $this->taskstatus($a['id']);
				$time = mysql_fetch_assoc($this->q("SELECT SUM(i.time) AS itime FROM items i WHERE i.tid='".$a['id']."'"));
				$a['itime'] = $time['itime'];
				if($s[0]==0){
					$st = 0;
				}
				elseif($s[0]==100){
					$st = 1;
				}
				else{
					$st = 2;
				}
				
				if($s[1]==1){
					$st = 5;
				}
				
				$datediffnow = floor((strtotime($a['date2'])-strtotime(date('Y-m-d H:i:s')))/(60*60*24));
				$datediffend = floor((strtotime($a['date2'])-strtotime($a['date']))/(60*60*24));
				$us = array();
				$tasks_array[$st][$a['id']] = $this->task($a,$s,$st,$datediffnow,$allusers,$this->val,$us,$home);
				$tasks_users_array[$a['id']] = $a['user'];
			}
			// $html .= $users;
			$st_array = array(0,5,2,1,3);
			foreach($st_array as $st){
				foreach($tasks_array as $key=>$tasks){
					if($key==$st){
						$html .= '<ul class="tasks countdown" id="tasks'.$st.'">';
						$i = 1;
						foreach($tasks as $k=>$t){
							$html .= '<li class="user'.$tasks_users_array[$k].'" id="task'.$k.'">
								<table cellpadding="5" cellspacing="0" border="0" id="ts'.$k.'" class="item">
									<tr>
										'.($home=='home'?'':'
										<td width="10" valign="middle"><input type="checkbox" class="taskcheck" onclick="TasksCheck(\''.$idproject.'\')" value="'.$k.'" /></td>
										').'
										'.$t.'';
							$i++;
						}
						$html .= '</ul><hr />';
					}
				}
			}
		}
		$html .= '<div id="taskitems'.$idproject.'archive" class="taskitems" style="display:none;background:#fff;margin:0 -10px 20px;padding:10px;"><div id="taskitems'.$idproject.'archivein"></div></div>';
		return $html;
	}
	function taskstatus($id=0){
		if($id==0){
			$id = $this->r('tid');
			$act = 'string';
		}
		else{
			$id = $id;
			$act = 'array';
		}
		$html = '';
		$b = 0;
		$s = 0;
		$z = 0;
		$w = 0;
		$pr_query = $this->q("SELECT sid FROM items WHERE tid='".$id."' AND sid!=3");
		while($pr = mysql_fetch_assoc($pr_query)){
			if($pr['sid']=='1'){
				$s++;
				$b++;
			}
			elseif($pr['sid']=='2'||$pr['sid']=='6'){
				$s=$s+0.1;
				$b++;
				if($pr['sid']=='2'){
					$w = 1;
				}
			}
			elseif($pr['sid']=='0'){
				$s=$s+0;
				$b++;
			}
			elseif($pr['sid']=='5'){
				$s=$s+0.5;
				$b++;
				$z = 1;
			}
		}
		switch($act){
			case'array':{
				$html = array(round($s*100/($b>0?$b:1)),$z,$w);
				break;
			}
			case'string':{
				$html = round($s*100/($b>0?$b:1)).'%';
				break;
			}
		}
		
		return $html;
	}
	function task($a,$s,$st,$datediffnow,$allusers2,$us=array(),$archive=0,$home=''){
		$hours = $a['itime'];
		$hours2 = (int)(($hours-(int)($hours/(24*60*60))*24*60*60+(int)($hours/(24*60*60))*24*60*60)/(60*60));
		$minutes = (int)(($hours-(int)($hours/(24*60*60))*24*60*60-(int)(($hours-(int)($hours/(24*60*60))*24*60*60)/(60*60))*(60*60))/(60));
		$html = '
						<td width="20" class="d-n" valign="middle" align="center" title="'.($datediffnow<0&&$s[0]<100?'':($s[0]==100?'':''.$datediffnow.'/'.(floor((strtotime($a['date2'])-strtotime($a['date1']))/(60*60*24))<0?'0':''.floor((strtotime($a['date2'])-strtotime($a['date1']))/(60*60*24)).'').'')).''.($datediffnow<0&&$s[0]<100?'':($s[0]==100?'':''.$datediffnow.'/'.(floor((strtotime($a['date2'])-strtotime($a['date1']))/(60*60*24))<0?'0':''.floor((strtotime($a['date2'])-strtotime($a['date1']))/(60*60*24)).'').'')).'">
							<span class="'.($this->taskstatus($a['id'])<100?'
							'.($datediffnow<0?'iconred icon-clock f-l
								':($datediffnow>=0&&$datediffnow<1?'iconorange icon-clock f-l
								':'icongreen icon-clock f-l')).'':'icongreen icon-check f-l').'"></span>
						</td>
						<td class="va-m ta-l fs-14 fsl-i cr-p" onclick="if(jQuery(\'#task'.$a['id'].'\').is(\'.active\')){jQuery(\'ul.tasks>li\').removeClass(\'active\');jQuery(\'#taskitems'.$a['id'].'\').hide();}else{Page(\'items&idin='.$a['id'].'&home='.$home.'\',e(\'taskitems'.$a['id'].'in\'));jQuery(\'#taskitems'.$a['id'].'\').show();jQuery(\'ul.tasks>li\').removeClass(\'active\');jQuery(\'#task'.$a['id'].'\').addClass(\'active\');}">
							'.($a['name']==''?'[- No task name -]':$a['name']).'
							<sup class="c-666">'.$a['id'].'</sup>
						</td>
						<td width="120" valign="middle" align="right">
							'.($a['uid']==$this->uid?'
							'.(!isset($a['pval'])?'':($a['pval']==768?'<span class="fs-12" style="top:6px;"><span class="fs-10">'.($a['pval']==0?$this->val[768]:$this->val[$a['pval']]).'</span>'.number_format($a['cost'],2,'.',' ').'</span>':'<span class="fs-12" style="top:6px;">'.number_format($a['cost'],2,'.',' ').' <span class="fs-10">'.($a['pval']==0?$this->val[768]:$this->val[$a['pval']]).'</span></span>')).'
							':'').'
							<sup class="c-999" style="right:5px;top:0px;">'.number_format($hours/3600,2,'.',' ').'h ('.$hours2.':'.$minutes.')</sup>
						</td>
						'.($home=='home'?'':'
						<td width="100" valign="middle" align="right">
							'.($a['uid']==$this->uid?'
							<span class="btn f-r p-5" onclick="Page(\'add&act=userto&eid='.$a['id'].'\',\'0\',\'\')"><span class="icon3 icon-person" title="Add worker"></span></span>
							':'').'
							<span class="btn f-r p-5" onclick="jQuery(\'#info'.$a['id'].'\').slideToggle(200)"><span class="iconlblue icon-info" title="Additional information"></span></span>
							'.($a['uid']==$this->uid?'
							<span class="btn f-r p-5" onclick="jQuery(\'#task'.$a['id'].'edit\').show();Load(e(\'task'.$a['id'].'edit\'),\'type=edit&act=task&tid='.$a['id'].'\');"><span class="iconlblue icon-pencil" title="Edit task"></span></span>
							':'').'
						</td>
						<td width="35" align="right"><span style="z-index:1;" class="c-'.($archive==0?($s[0]==100?'green':($s[1]==1?'orange':'lblue')):'grey').'" id="taskprogress'.$a['id'].'">'.$s[0].'%</span></td>
						').'
					</tr>
				</table>
				<div id="task'.$a['id'].'edit" class="" style="display:none;"></div>
				<div id="info'.$a['id'].'" class="pinfo" style="display:none;"><span class="btn f-r p-5" onclick="jQuery(this).parent().slideUp(350)"><span class="icon3 icon-close" title="Close"></span></span>'.$this->txt2link(str_replace('[-quot-]',"'",str_replace('[-amp-]','&',str_replace('<br />',' <br />',$a['description'])))).'</div>
				<div id="taskitems'.$a['id'].'" class="taskitems" style="display:none;background:#fff;margin:0 0 20px;"><div id="taskitems'.$a['id'].'in"></div></div>
			</li>';
		return $html;
	}
	
	function items($idtask=0){
		$idtask = $this->r('idin')>0?$this->r('idin'):$idtask;
		$home = $this->r('home');
		// echo $home.' '.$idtask;
		$html = '';
		if(mysql_num_rows($this->q("SELECT i.id FROM items i WHERE i.tid='".$idtask."'"))>0){
			$task = mysql_fetch_assoc($this->q("SELECT * FROM tasks WHERE id='".$idtask."'"));
			$project = mysql_fetch_assoc($this->q("SELECT id,name,description FROM projects WHERE id='".$task['project']."'"));
			if($this->r('itemsact')!=''){
				$this->setses('itemsact',$this->r('itemsact'));
			}
			if($this->r('type')!='items'&&$this->r('idin')>0){
				$html .= '<h1>'.($this->uid>0?$project['name'].' &rarr; ':'').''.$task['name'].'</h1>';
			}
			$html .= '
				<div class="control">
					<span id="itemsselall'.$idtask.'" class="btn f-l d-n" onclick="jQuery(\'.itemcheck\').attr(\'checked\',true);jQuery(\'#itemsoperations,#deselall\').show();jQuery(\'#selall\').hide();ItemsStatus(\''.$idtask.'\')"><span class="icongreen icon-check" title="Select all"></span></span>
					<span id="itemsdeselall'.$idtask.'" style="display:none;" class="btn f-l" onclick="jQuery(\'.itemcheck\').attr(\'checked\',false);jQuery(\'#itemsoperations,#deselall\').hide();jQuery(\'#selall\').show();ItemsStatus(\''.$idtask.'\')"><span class="iconred icon-closethick" title="Deselect"></span></span>
					
					<span class="btnst" style="display:none;" id="itemsstatus1">
						<span class="st0" title="Not done"></span>
						<span class="st2" title="In progress"></span>
						<span class="st1" title="Done"></span>
						<span class="st3" title="Will not be executed"></span>
						<span class="st6" title="Have a question"></span>
					</span>
					<span id="showall" style="display:none;" class="btn f-l" onclick="jQuery(\'.items li\').slideDown(350);jQuery(\'#hideall\').show();jQuery(this).parent().hide()"><span class="icongreen icon-lightbulb" title="Show all"></span></span>
					<span class="btn f-l d-n" onclick="Page(\'wcalendar&act=items&idin='.$idtask.'\',\'0\',\'\')"><span class="icon3 icon-calendar" title="Календарь"></span></span>
					'.($this->ses('itemsact')=='clear'?'
					<span class="btn f-l d-n" onclick="Page(\'items&itemsact=0&idin='.$idtask.'\',e(\'content\'),\'\')"><span class="iconlblue icon-circle-plus" title="Показывать все"></span></span>
					':'
					<span class="btn f-l d-n" onclick="Page(\'items&itemsact=clear&idin='.$idtask.'\',e(\'content\'),\'\')"><span class="iconred icon-circle-minus" title="Убрать лишнее"></span></span>
					').'
					<a class="btn f-l d-n" href="'.$this->site.'timeline/'.$idtask.'.html"><span class="icon3 icon-calendar" title="Timeline"></span></a>
					<a class="btn f-l d-n" href="'.$this->site.'items-'.$idtask.'.html"><span class="icon3 icon-print" title="Show all"></span></span>
					<a class="btn f-l" href="'.$this->site.'p/'.$idtask.'.html"><span class="icon3 icon-extlink" title="Export as PDF/Print"></span></a>
					<span id="itemsoperations" style="display:none;">
						<span id="hideall" class="btn f-l d-n" onclick="ItemsHide();jQuery(\'#showall\').show();jQuery(this).parent().hide()"><span class="iconred icon-lightbulb" title="To the archive"></span></span>
						<span class="btn f-l d-n" onclick="ItemsUsers();"><span class="icon0 icon-person" title="Add/Delete user"></span></span>
						<span class="btn f-l" onclick="ItemsDelete(\''.$idtask.'\')"><span class="iconred icon-trash" title="Delete"></span></span>
						<span class="btn f-l" onclick="ItemEdit(\''.$idtask.'\')"><span class="iconlblue icon-pencil" title="Edit"></span></span>
					</span>
				</div>';
			$uall = $this->q("SELECT * FROM tasks t WHERE t.id='".$idtask."' AND (t.uid='".$this->uid."' OR t.user='".$this->uid."') ORDER BY t.id ASC");
			if(mysql_num_rows($uall)>0){
				$r = '';
			}
			else{
				if($home=='home'){
					$r = '';
				}
				else{
					//$r = " AND i.uid='".$this->uid."' ";
				}
			}
			$all = $this->q("SELECT i.*,t.uid AS tuser FROM tasks t LEFT JOIN items i ON i.tid=t.id AND i.iid='0'".$r."".($this->ses('itemsact')=='clear'?" AND i.sid!=1 AND i.sid!=3":'')." WHERE t.id=".$idtask." ORDER BY i.id ASC");
			
			$k = 1;
			// if($home=='home'){
				// $logged = 0;
			// }
			// else{
				// $logged = 1;
			// }
			// print_r(mysql_fetch_assoc($all));
			$html .= $this->itemsecho($all,$k,'');
		}
		else{
			$html = $this->denide();
		}
		return $html;
	}
	function itemsecho($all,$k,$k_parent,$logged=1){
		$html = '';
		$html .= '<ul class="items">';
		$j = 0;
		$i = 1;
		//echo '112 '.mysql_num_rows($all);
		while($a = mysql_fetch_assoc($all)){
			$k = ($k_parent==''?'':$k_parent.'.').$i;
			$html .= '<li id="i'.$a['id'].'">'.$this->item($a,$k,$logged).'';
			if(mysql_num_rows($this->q("SELECT id FROM items WHERE iid='".$a['id']."'"))>0){
				$all2 = $this->q("SELECT * FROM items WHERE iid='".$a['id']."' ORDER BY id ASC");
				$k_p = $k;
				$html .= $this->itemsecho($all2,1,$k_p,$logged);
			}
			// $k++;
			$i++;
			$j = 1-$j;
			$html .= '</li>';
		}
		$html .= '</ul>';
		return $html;
	}
	function item($a,$i,$logged){
		$date1 = gmmktime(date("H",strtotime($a['date2'])),date("i",strtotime($a['date2'])),date("s",strtotime($a['date2'])),date("m",strtotime($a['date2'])),date("d",strtotime($a['date2'])),date("Y",strtotime($a['date2'])));
		$date2 = gmmktime(date("H",strtotime($a['date3'])),date("i",strtotime($a['date3'])),date("s",strtotime($a['date3'])),date("m",strtotime($a['date3'])),date("d",strtotime($a['date3'])),date("Y",strtotime($a['date3'])));
		$da = strtotime($a['date3'])-strtotime($a['date2']);
		if($a['sid']==0){
			$time = '0.00';
		}
		elseif(($a['sid']==2||$a['sid']==4)&&$da<0){
			$time = number_format((strtotime(date('Y-m-d H:i:s'))-strtotime($a['date2']))/3600,2,'.',' ');
			// $time = gmdate('H:i:s',$a['time']+strtotime($da));
		}
		else{
			$time = number_format($a['time']/3600,2,'.',' ');
		}
		$hours = $time*3600;
		$hours2 = (int)(($hours-(int)($hours/(24*60*60))*24*60*60+(int)($hours/(24*60*60))*24*60*60)/(60*60));
		$minutes = (int)(($hours-(int)($hours/(24*60*60))*24*60*60-(int)(($hours-(int)($hours/(24*60*60))*24*60*60)/(60*60))*(60*60))/(60));
		$html = '
			<table cellpadding="3" cellspacing="0" border="0" class="item'.$a['id'].' st'.$a['sid'].'">
				<tr>
					'.($logged==0||$this->uid==0?'':''.($a['sid']==1||$a['sid']==3?'':'<td width="20"><input type="checkbox" class="itemcheck itemtime'.$a['id'].'" onclick="ItemCheck(\''.$a['tid'].'\')" value="'.$a['id'].'" style="'.($a['sid']=='1'||$a['sid']=='3'?'display:none;':'').'" /></td>').'').'
					<td width="'.(count(explode('.',$i))*10+10).'" valign="middle" align="right" class="fs-12">'.$i.'</td>
					<td class="itemtxt">'.$this->txt2link(str_replace('[-quot-]',"'",str_replace('[-amp-]','&',str_replace('<br />',' <br />',$a['name'])))).'</td>
					'.($a['sid']==1||$a['sid']==3?'':($this->uid>0||($this->uid==0&&$a['uid']==0)?'
					<td width="105" align="left">
						<div class="btnstdiv f-l d-n"><span class="btn f-l p-2" style="margin:0 3px 0 0;"><span class="icon3 icon-plus"></span></span>
							<ul class="btnst">
								<li><span class="btn f-l p-2" onclick="Page(\'add&act=item&pid='.$a['id'].'\',\'0\',\'\')"><span class="icon3 icon-plus"></span></span></li>
								<li class="btnst6" onclick="Load(e(\'i'.$a['id'].'\'),\'type=status&act=item&idin='.$a['id'].'&nstatus=6&ntask='.$a['tid'].'&i='.$i.'\')" title="Set status \"Have a question\""></li>
								<li class="btnst3" onclick="Load(e(\'i'.$a['id'].'\'),\'type=status&act=item&idin='.$a['id'].'&nstatus=3&ntask='.$a['tid'].'&i='.$i.'\')" title="Set status \"Will not be executed\""></li>
								<li class="btnst2" onclick="Load(e(\'i'.$a['id'].'\'),\'type=status&act=item&idin='.$a['id'].'&nstatus=2&ntask='.$a['tid'].'&i='.$i.'\')" title="Set status \"In progress\""></li>
								<li class="btnst1" onclick="Load(e(\'i'.$a['id'].'\'),\'type=status&act=item&idin='.$a['id'].'&nstatus=1&ntask='.$a['tid'].'&i='.$i.'\')" title="Set status \"Done\""></li>
								<li class="btnst0" onclick="Load(e(\'i'.$a['id'].'\'),\'type=status&act=item&idin='.$a['id'].'&nstatus=0&ntask='.$a['tid'].'&i='.$i.'\')" title="Set status \"Not done\""></li>
							</ul>
						</div>
						<span class="btn f-r p-2 itemtime'.$a['id'].'" style="margin:0 3px 0 0;'.($a['sid']=='1'||$a['sid']=='3'?'display:none;':'').'" onclick="Item(\'block\',\''.$a['id'].'\',\''.$a['sid'].'\',\''.$a['time'].'\',this,\''.$a['tid'].'\');"><span class="icon3 icon-'.($a['sid']==3?'un':'').'locked"></span></span>
						<span class="btn f-r p-2 help itemtime'.$a['id'].'" style="margin:0 3px 0 0;'.($a['sid']=='1'||$a['sid']=='3'?'display:none;':'').'" onclick="Item(\'help\',\''.$a['id'].'\',\''.$a['sid'].'\',\''.$a['time'].'\',this,\''.$a['tid'].'\');"><span class="icon3 icon-help"></span></span>
						<span class="btn f-r p-2 stop itemtime'.$a['id'].'" style="margin:0 3px 0 0;'.($a['sid']=='1'||$a['sid']=='3'?'display:none;':'').'" onclick="Item(\'stop\',\''.$a['id'].'\',\''.$a['sid'].'\',\''.$a['time'].'\',this,\''.$a['tid'].'\');"><span class="icon3 icon-stop"></span></span>
						<span class="btn f-r p-2 pause itemtime'.$a['id'].' blink" style="margin:0 3px 0 0;'.($date2-$date1<0?($a['sid']=='1'||$a['sid']=='3'?'display:none;':''):'display:none;').'" onclick="Item(\'pause\',\''.$a['id'].'\',\''.$a['sid'].'\',\''.$a['time'].'\',this,\''.$a['tid'].'\');"><span class="icon3 icon-pause"></span></span>
						<span class="btn f-r p-2 play itemtime'.$a['id'].'" style="margin:0 3px 0 0;'.($date2-$date1<0||$a['sid']=='1'||$a['sid']=='3'?'display:none;':'').'" onclick="Item(\'play\',\''.$a['id'].'\',\''.$a['sid'].'\',\''.$a['time'].'\',this,\''.$a['tid'].'\');"><span class="icon3 icon-play"></span></span>
						
					</td>
					':'')).'
					<td width="40" align="right" class="itemtime time'.$a['id'].'">
						<sup class="" style="right:5px;top:0px;">'.$hours2.':'.(strlen($minutes)==1?'0':'').$minutes.'</sup>
						<span class="fs-10" style="top:6px;">'.number_format($hours/3600,2,'.',' ').'h</span>
					</td>
					
				</tr>
			</table><div id="i'.$a['id'].'e"></div>';
		return $html;
	}
	function daysInYear($date){
		$fd = (int)substr($date, 8, 2);
		$fm = (int)substr($date, 5, 2);
		$fy = (int)substr($date, 0, 4);
		return $days=date('z',mktime(0,0,0,$fm,$fd,$fy));
	}
	function itemtime(){
		switch($this->r('act')){
			case'start':{
				$this->q("UPDATE items SET sid='2',date2='".date('Y-m-d H:i:s')."' WHERE id='".$this->r('itemid')."'");
				break;
			}
			case'pause':{
				$a = mysql_fetch_assoc($this->q("SELECT * FROM items WHERE id='".$this->r('itemid')."'"));
				$metTS1 = strtotime($a['date2']);
				$metTS2 = strtotime(date('Y-m-d H:i:s'));
				$date1 = mktime(date("H",strtotime($a['date2'])),date("i",strtotime($a['date2'])),date("s",strtotime($a['date2'])),date("m",strtotime($a['date2'])),date("d",strtotime($a['date2'])),date("Y",strtotime($a['date2'])));
				$date2 = mktime(date("H",strtotime($a['date3'])),date("i",strtotime($a['date3'])),date("s",strtotime($a['date3'])),date("m",strtotime($a['date3'])),date("d",strtotime($a['date3'])),date("Y",strtotime($a['date3'])));
				$time = $metTS2-$metTS1+$a['time'];
				$this->q("UPDATE items SET sid='6',date3='".date('Y-m-d H:i:s')."',time='".$time."' WHERE id='".$this->r('itemid')."'");
				break;
			}
			case'help':{
				$a = mysql_fetch_assoc($this->q("SELECT * FROM items WHERE id='".$this->r('itemid')."'"));
				// $metTS1 = strtotime($a['date6']);
				// $metTS2 = strtotime(date('Y-m-d H:i:s'));
				// $date1 = mktime(date("H",strtotime($a['date6'])),date("i",strtotime($a['date6'])),date("s",strtotime($a['date6'])),date("m",strtotime($a['date6'])),date("d",strtotime($a['date6'])),date("Y",strtotime($a['date6'])));
				// $date2 = mktime(date("H",strtotime($a['date7'])),date("i",strtotime($a['date7'])),date("s",strtotime($a['date7'])),date("m",strtotime($a['date7'])),date("d",strtotime($a['date7'])),date("Y",strtotime($a['date7'])));
				// $time = $a['time']>0?$metTS2-$metTS1+$a['time']:0;
				$this->q("UPDATE items SET sid='".($a['sid']==5?6:5)."' WHERE id='".$a['id']."'");
				break;
			}
			case'stop':{
				$item = mysql_fetch_assoc($this->q("SELECT * FROM items WHERE id='".$this->r('itemid')."'"));
				$t1 = strtotime($item['date2']);
				$t2 = strtotime(date('Y-m-d H:i:s'));
				$time = $item['sid']==2?$t2-$t1+(int)$item['time']:(int)$item['time'];
				// echo $t1.' - '.$t2.' - '.$item['time'].' - '.$this->r('itemid');
				$this->q("UPDATE items SET sid='1',date3='".date('Y-m-d H:i:s')."',time='".$time."' WHERE id='".$this->r('itemid')."'");
				break;
			}
			case'lock':{
				$item = $this->q("SELECT * FROM items WHERE id='".$this->r('itemid')."'");
				// $metTS1 = strtotime($item['date6']);
				// $metTS2 = strtotime(date('Y-m-d H:i:s'));
				// $time = date('Y-m-d H:i:s',($metTS2 - $metTS1));
				$this->q("UPDATE items SET sid='3',date3='".date('Y-m-d H:i:s')."' WHERE id='".$this->r('itemid')."'");
				break;
			}
			case'unlock':{
				$item = $this->q("SELECT * FROM items WHERE id='".$this->r('itemid')."'");
				// $metTS1 = strtotime($item['date6']);
				// $metTS2 = strtotime(date('Y-m-d H:i:s'));
				// $time = date('Y-m-d H:i:s',($metTS2 - $metTS1));
				$this->q("UPDATE items SET sid='".($item['time']==0?'0':'6')."' WHERE id='".$this->r('itemid')."'");
				break;
			}
		}
	}
	function itemstimed(){
		$html = '';
		$items = $this->q("SELECT
				i.*,
				p.id AS idproject,
				p.name AS nameproject,
				t.id AS idtask,
				t.name AS nametask,
				u.login AS ulogin,
				u.id AS uid
			FROM jos_whm AS p
				LEFT JOIN jos_whm AS t
				ON
					t.project=p.id
					AND t.type='task'
				LEFT JOIN jos_whm AS i
				ON
					i.task=t.id
					
				LEFT OUTER JOIN jos_whm AS u
				ON
					u.id=t.users
			WHERE
				p.type='project'
				AND i.name<>''
				AND i.date6<>'0000-00-00 00:00:00'
				AND i.id>0
				AND (i.status='2' OR i.status='6')
				AND (p.user='".$this->uid."' OR p.users='".$this->uid."')
				AND p.status<>4 AND p.status<>3");
		if(mysql_num_rows($items)>0){
			$html .= '<table cellpadding="2" cellspacing="0" border="0" width="708">';
			$html .= '<tr>
					<th width="20">#</th>
					<th width="100">Project</th>
					<th width="100">Task</th>
					<th>Item</th>
					<th width="100">User</th>
					<th width="50">Time</th>
				</tr>';
			$j = 0;
			$k = 1;
			while($i = mysql_fetch_assoc($items)){
				// $uid = (int)$i['tu'];
				$html .= '<tr class="st'.$i['status'].'">
						<td'.($j>0?' style="border-top:1px dashed #666;"':'').' valign="top" align="center">'.$k.'</td>
						<td'.($j>0?' style="border-top:1px dashed #666;"':'').' valign="top">'.($i['nameproject']==''?'[- No name -]':$i['nameproject']).'</td>
						<td'.($j>0?' style="border-top:1px dashed #666;"':'').' valign="top">'.($i['nametask']==''?'[- No name -]':$i['nametask']).'</td>
						<td'.($j>0?' style="border-top:1px dashed #666;"':'').' valign="top">
							<a class="c-000" href="javascript:void(0)" onclick="Project(\''.$i['idproject'].'\');var t = setTimeout(function(){if(jQuery(\'#task'.$i['idtask'].'\').is(\'.active\')){jQuery(\'ul.tasks>li\').removeClass(\'active\');jQuery(\'#taskitems'.$i['idtask'].'\').hide();}else{Page(\'items&idin='.$i['idtask'].'\',e(\'taskitems'.$i['idtask'].'in\'));jQuery(\'#taskitems'.$i['idtask'].'\').show();jQuery(\'ul.tasks>li\').removeClass(\'active\');jQuery(\'#task'.$i['idtask'].'\').addClass(\'active\');var t = setTimeout(function(){jQuery(\'.timestarted\').each(function(){jQuery(this).countdown({since:jQuery(this).html(),compact:true,format:\'HMS\'});})},1000);}},1000);jQuery(\'#itemstimed table\').toggle();">'.$i['name'].'</a>
						</td>
						<td'.($j>0?' style="border-top:1px dashed #666;"':'').' valign="top" align="center">'.($i['ulogin']?$i['ulogin']:'- No Worker -').'</td>
						<td'.($j>0?' style="border-top:1px dashed #666;"':'').' valign="top" align"right">'.gmdate('H:i:s',$i['time']).'</td>
					</tr>';
				$j++;
				$k++;
			}
			$html .= '</table>
				<span class="btn f-l p-2" onclick="jQuery(\'#itemstimed table\').toggle()"><span class="icon3 icon-newwin" title="Hide/Show"></span></span>';
		}
		return $html;
	}
	
	function timeline(){
		$idtask = $this->r('idin');
		$q = mysql_fetch_assoc($this->q("SELECT type FROM jos_whm WHERE id='".$idtask."'"));
		$txt = array('task'=>'Task items timeline',
			'project'=>'Project tasks timeline',
			'user'=>'User works timeline');
		$html = '<h1>'.$txt[$q['type']].'</h1>';
		$html .= '<table cellspacing="0" cellpadding="5" border="0" width="100%">';
		$html .= '<tr>';
		$html .= '<td width="5" class="st0"></td><td>Not done&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$html .= '<td width="5" class="st1"></td><td>Done&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$html .= '<td width="5" class="st2"></td><td>In progress&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$html .= '<td width="5" class="st3"></td><td>Will not be done&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		$html .= '<td width="5" class="st6"></td><td>Paused&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		// $html .= '<td align="right" width="30%">Spent time: <b class="fs-20">'.number_format($time['itime']/3600,2,'.',' ').'</b>&nbsp;hours</td>';
		$html .= '</tr>';
		$html .= '</table>';
		// print_r($_REQUEST);86400
		$width = 100;
		$height = 20;
		$all = $this->q("SELECT i.* FROM jos_whm AS t LEFT JOIN jos_whm AS i ON i.task=t.id AND i.type='item' WHERE t.id='".$idtask."' AND t.type='task' ORDER BY i.id ASC");
		$start1 = mysql_fetch_assoc($this->q("SELECT MIN(i.date1) AS start FROM jos_whm AS t LEFT JOIN jos_whm AS i ON i.task=t.id AND i.type='item' WHERE t.id='".$idtask."' AND t.type='task' ORDER BY i.id ASC"));
		$end1 = mysql_fetch_assoc($this->q("SELECT MAX(i.date2) AS end FROM jos_whm AS t LEFT JOIN jos_whm AS i ON i.task=t.id AND i.type='item' WHERE t.id='".$idtask."' AND t.type='task' ORDER BY i.id ASC"));
		$end = (strtotime($end1['end'])/86400)-(strtotime($start1['start'])/86400);
		$start = 0;
		$count = mysql_num_rows($all);
		if($count>0){
			$array = array();
			$j = 0;
			$w = 0;
			// $w1 = 0;
			// $w2 = 0;
			while($i = mysql_fetch_assoc($all)){
				// $uid = (int)$i['tu'];
				$array[$j]['start1'] .= (((strtotime($i['date1'])/86400)-(strtotime($start1['start'])/86400))*$width);
				$array[$j]['start2'] .= (((strtotime($i['date4'])/86400)-(strtotime($start1['start'])/86400))*$width);
				$array[$j]['width1'] .= (((strtotime($i['date2'])/86400)-(strtotime($i['date1'])/86400))*$width);
				$array[$j]['width2'] .= (((strtotime($i['date5'])/86400)-(strtotime($i['date4'])/86400))*$width);
				$array[$j]['date5'] .= $i['date5'];
				$array[$j]['name'] .= $i['name'];
				$array[$j]['status'] .= $i['status'];
				$array[$j]['date'] .= $i['status'];
				$w = max($array[$j]['width1'],$array[$j]['width2'],$w);
				$j++;
			}
			$html .= '<div class="timeline">';
			$html .= '<div class="timelinearea" style="width:'.($width*$w).'px;">';
			$html .= '<ul class="timelinedates" style="width:'.($width*$w).'px;">';
			// $date = date('d.m.Y',strtotime("-1 day",strtotime(date('d.m.Y'))));
			$date = date('d.m.Y',strtotime($start1['start']));
			for($i=0;$i<20;$i++){
				$html .= '<li class="timelinedate" style="left:'.($i*$width).'px;">';
				$html .= ''.$date.'';
				$html .= '</li>';
				$date = date('d.m.Y',strtotime("+1 day",strtotime($date)));
			}
			$html .= '</ul>';
			foreach($array as $a){
				$html .= '<div class="timelineitem">';
				$html .= '<div class="name" style="left:'.$a['start1'].'px;min-width:100px;">'.substr($a['name'],0,50).'</div>';
				$html .= '<div class="plan" style="height:'.($height).'px;width:'.($a['width1']).'px;left:'.$a['start1'].'px;"></div>';
				$html .= '<div class="fact st'.$a['status'].'" style="height:'.($height).'px;width:'.($a['status']==6&&$a['date5']=='0000-00-00 00:00:00'?$width:$a['width2']).'px;left:'.$a['start2'].'px;"></div>';
				$html .= '</div>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}
		else{
			$html .= '- There is no items to display -';
		}
		return $html;
	}
	function status(){
		$html = '';
		switch($this->r('act')){
			case'project':{
				$objects = explode('[-s-]',$this->r('idin'));
				$a = array();
				foreach($objects as $ob){
					if($ob!=''){
						$this->q("UPDATE projects SET sid='".$this->r('nstatus')."',date3='".date('Y-m-d H:i:s')."' WHERE id='".$ob."'");
						$p = mysql_fetch_assoc($this->q("SELECT name FROM projects WHERE id='".$ob."'"));
						// echo $this->r('ntask');
						$a[] = $p['name'];
					}
				}
				// print_r($a);
				$s = array('Not done','Done','In progress','Close','Archive');
				$_REQUEST['idin'] = '';
				$html = $this->info('info','Status has been changed!</strong> Status of the project'.((count($objects)-1)==1?'':'s').' <b>'.implode(', ',$a).'</b> has been changed to <b><i>"'.$s[$this->r('nstatus')].'"</i></b>').$this->projects();
				break;
			}
			case'task':{
				$objects = explode('[-s-]',$this->r('idin'));
				foreach($objects as $ob){
					if($ob!=''){
						$this->q("UPDATE tasks SET sid='".$this->r('nstatus')."' WHERE id='".$ob."'");
					}
				}
				$_REQUEST['idin'] = '';
				$html = $this->tasks();
				break;
			}
			case'item':{
				$objects = explode('[-s-]',$this->r('idin'));
				foreach($objects as $ob){
					if($ob!=''){
						$this->q("UPDATE items SET sid='".$this->r('nstatus')."' WHERE id='".$ob."'");
					}
				}
				if($this->taskstatus($this->r('ntask'))==100){
					$this->q("UPDATE items SET date3='".date('Y:m:d H:i:s')."' WHERE id='".$this->r('ntask')."'");
				}
				else{
					$this->q("UPDATE items SET date3='0000-00-00 00:00:00' WHERE id='".$this->r('ntask')."'");
				}
				$_REQUEST['idin'] = '';//print_r($objects);
				if(count($objects)==1){
					$a = mysql_fetch_assoc($this->q("SELECT i.*,t.user AS tuser FROM items i RIGHT JOIN tasks t ON t.id=i.tid WHERE i.id='".$objects[0]."'"));
					// print_r($a);
					$html = $this->item($a,$this->r('i'));
				}
				else{
					$html = $this->items($this->r('ntask'));
				}
				break;
			}
		}
		return $html;
	}
	function archive(){
		$html = '';
		switch($this->r('act')){
			case'projects':{
				$html = '<h1>Projects archive</h1>';
				$all = $this->q("SELECT * FROM projects p WHERE p.sid=4 AND p.uid='".$this->uid."' ORDER BY p.date3 DESC");
				// $valute = $this->q("SELECT * FROM jos_whm AS v WHERE v.type='valute' ORDER BY v.id ASC");
				// $val = array();
				// $conv = array(1=>1,2=>1.25,3=>0.03,4=>0.125);
				// while($v = mysql_fetch_assoc($valute)){
					// $val[$v['id']] = $v['name'];
				// }
				$html .= '<div class="control">
						<span id="selall" class="btn f-l" onclick="jQuery(\'.projectcheck\').attr(\'checked\',true);jQuery(\'#projectsoperations,#deselall\').show();jQuery(\'#selall\').hide();ProjectsStatus()"><span class="icongreen icon-check" title="Select all"></span></span>
						<span id="deselall" style="display:none;" class="btn f-l" onclick="jQuery(\'.projectcheck\').attr(\'checked\',false);jQuery(\'#projectsoperations,#deselall\').hide();jQuery(\'#selall\').show();ProjectsStatus()"><span class="iconred icon-cancel" title="Deselect"></span></span>
						<span id="projectsoperations" style="display:none;">
							<span class="btn f-l" onclick="ProjectsDelete()"><span class="iconred icon-closethick" title="Delete"></span></span>
						</span>
						<span class="btnst" style="display:none;" id="projectsstatus">
							<span class="st0" title="Set status \"Not Done\""></span>
							<span class="st2" title="Set status \"In progress\""></span>
							<span class="st1" title="Set status \"Выполнен\""></span>
							<span class="st3" title="Set status \"Закрыт\""></span>
							<span class="st4" title="Set status \"Архив\""></span>
						</span>
						<span class="btn f-l d-n" onclick="Page(\'wcalendar&act=projects\',\'0\',\'\')"><span class="icon3 icon-calendar" title="Calendar"></span></span>
						<span class="btn f-l d-n" onclick="Load(e(\'content\'),\'type=archive&act=projects\')"><span class="icon3 icon-folder-collapsed" title="Архив"></span></span>
					</div>';
				$projects_array = array();
				$k = 1;
				while($a = mysql_fetch_assoc($all)){
					$cname = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm WHERE id='".$a['client']."'"));
					$projects_array[] = '
						<li lang="projects_'.$a['id'].'" id="p'.$a['id'].'">
							<table cellspacing="0" cellpadding="5" border="0" class="tbl">
								<tr>
									<td width="20" class="ta-r fs-11">'.$k.'</td>
									<td width="10" class="st'.$a['status'].'"><input class="projectcheck" type="checkbox" onclick="ProjectsCheck();" value="'.$a['id'].'" /></td>
									<td>
										<table cellpadding="0" cellspacing="0" border="0" width="100%">
											<tr>
												<td align="right"><span class="icongreen icon-person" title="Клиент"></span></td>
												<td>&nbsp;</td>
												<td class="fs-12">'.((strlen($cname['name'])>50?mb_substr($cname['name'],0,50,'utf-8').'...':$cname['name'])==''?'-':(strlen($cname['name'])>50?mb_substr($cname['name'],0,50,'utf-8').'...':$cname['name'])).'</td>
												<td>&nbsp;</td>
												<td align="center"><span class="fs-9 c-666" style="cursor:text;">БАЛАНС</span></td>
												<td>&nbsp;</td>
												<td align="center"><span class="iconblue icon-clock" title="Время"></span></td>
												<td>&nbsp;</td>
												<td align="center"><span class="iconlblue icon-document" title="Задания"></span></td>
												<td>&nbsp;</td>
												<td align="center"><span class="icon3 icon-person" title="Пользователи"></span></td>
												<td>&nbsp;</td>
												<td align="center"><span class="icon3 icon-gear" title="Settings"></span></td>
											</tr>
											<tr>
												<td width="20" align="right"><span class="iconorange icon-folder-collapsed" title="Project"></span></td>
												<td width="5">&nbsp;</td>
												<td class="fs-12">'.(strlen($a['name'])>50?mb_substr($a['name'],0,50,'utf-8').'...':$a['name']).'</td>
												<td width="10">&nbsp;</td>
												<td width="100" align="center">
													<b class="c-'.($cost2<0?'f00':'green2').'">'.$v[0].''.$cost2.'</b>/<span class="c-'.($cost3<0?'f00':'green2').' fs-12">'.$v[0].''.$cost3.'</span>
												</td>
												<td width="10">&nbsp;</td>
												<td width="120" align="center">
													'.($a['status']==3?'<b class="tt-u fs-10">закрыт</b>':'
													'.(floor((strtotime($a['date2'])-strtotime(date('Y-m-d H:i:s')))/(60*60*24))<0?'<b class="tt-u c-f00 fs-10">Просрочен</b>':'<b class="c-green2" title="Осталось">'.floor((strtotime($a['date2'])-strtotime(date('Y-m-d H:i:s')))/(60*60*24)).'</b> <span class="fs-10 tt-u">дн.</span>').'
													/<span class="fs-12" title="Всего">'.floor((strtotime($a['date2'])-strtotime($a['date1']))/(60*60*24)).' <span class="fs-10 tt-u">дн.</span></span></td>
													').'
												<td width="10">&nbsp;</td>
												<td width="20" align="center">
													'.(mysql_num_rows($this->q("SELECT id FROM jos_whm WHERE type='task' AND project='".$a['id']."' AND status!='4' AND (user='".$this->uid."' OR users LIKE '%]".$this->uid."[-s-]%')"))).'
												</td>
												<td width="10">&nbsp;</td>
												<td width="20" align="center">'.(count(explode('[-s-]',$a['users']))-1).'</td>
												<td width="10">&nbsp;</td>
												<td width="80" align="center">
													<span class="btn f-r p-1" onclick="jQuery(this).parent().parent().parent().parent().parent().find(\'.pinfo\').slideDown(350)"><span class="iconlblue icon-info" title="Дополнительная информация"></span></span>
													<span class="btn f-r p-1" onclick="window.location = \''.$this->site.'tasks-'.$a['id'].'.html\'"><span class="iconlblue icon-document" title="Перейти к заданиям этого проекта"></span></span>
													<span class="btn f-r p-1" onclick="Page(\'add&act=userto&eid='.$a['id'].'\',\'0\',\'\')"><span class="icon3 icon-person" title="Add пользователя"></span></span>
													<span class="btn f-r p-1" onclick="Load(e(\'p'.$a['id'].'\'),\'type=edit&act=project&pid='.$a['id'].'\')"><span class="iconlblue icon-pencil" title="Редактировать"></span></span>
												</td>
											</tr>
										</table>
										<div class="pinfo" style="display:none;"><span class="btn f-r p-1" onclick="jQuery(this).parent().slideUp(350)"><span class="iconred icon-closethick" title="Close"></span></span>'.$this->txt2link(str_replace('[-quot-]',"'",str_replace('[-amp-]','&',str_replace('<br />',' <br />',$a['contacts'])))).'</div>
									</td>
								</tr>
							</table>
						</li>';
					$k++;
				}
				$html .= '<ul class="projects">';
				$pa = array_reverse($projects_array);
				foreach($pa as $p){
					$html .= $p;
				}
				$html .= '</ul>';
				break;
			}
			case'tasks':{
				$html = '<h3><b>Tasks archive</b></h3>';
				$idproject = $this->r('project');
				$all = $this->q("SELECT
						t.*,
						p.name AS pname,
						t.description AS pcont,
						p.vid AS pval
					FROM
						projects p
					LEFT JOIN tasks t
					ON
						t.pid=p.id
						AND t.sid=4
					WHERE
						p.id='".$idproject."'
					ORDER BY t.date DESC");
				$k = 1;
				$users = '';
				// $valute = $this->q("SELECT id,name FROM jos_whm AS v WHERE v.type='valute' ORDER BY v.id ASC");
				// $val = array();
				$allusers = array();
				// while($v = mysql_fetch_assoc($valute)){
					// $val[$v['id']] = $v['name'];
				// }
				if(mysql_num_rows($all)>0){
					if(mysql_num_rows($all)>0){
						while($a = mysql_fetch_assoc($all)){
							$s = $this->taskstatus($a['id']);
							$time = mysql_fetch_assoc($this->q("SELECT SUM(i.time) AS itime FROM items i WHERE i.tid='".$a['id']."'"));
							$a['itime'] = $time['itime'];
							if($s[0]==0){
								$st = 0;
							}
							elseif($s[0]==100){
								$st = 1;
							}
							else{
								$st = 2;
							}
							
							if($s[1]==1){
								$st = 5;
							}
							
							$datediffnow = floor((strtotime($a['date2'])-strtotime(date('Y-m-d H:i:s')))/(60*60*24));
							$datediffend = floor((strtotime($a['date3'])-strtotime($a['date']))/(60*60*24));
							$us = array();
							$tasks_array[$st][$a['id']] = $this->task($a,$s,$st,$datediffnow,$allusers,$this->val,$us,1);
							$tasks_users_array[$a['id']] = $a['user'];
						}
						$html .= $users;
						$st_array = array(0,5,2,1,3);
						$j = 0; 
						foreach($st_array as $st){
							foreach($tasks_array as $key=>$tasks){
								if($key==$st){
									$html .= '<ul class="tasks countdown" id="tasks'.$st.'">';
									$i = 1;
									foreach($tasks as $k=>$t){
										$html .= '<li class="t'.$j.' user'.$tasks_users_array[$k].'" id="task'.$k.'">
											<table cellpadding="5" cellspacing="0" border="0" id="ts'.$k.'" class="item">
												<tr>
													<td width="10" valign="middle" class="d-n"><input type="checkbox" class="taskcheck" onclick="TasksCheck(\''.$idproject.'\')" value="'.$k.'" /></td>
													'.$t.'';
										$j = 1-$j;
										$i++;
									}
									$html .= '</ul><hr />';
								}
							}
						}
					}
				}
				else{
					$html .= '- No tasks -';
				}
				break;
			}
			default:{
				$html = '<h1>Archive</h1>';
				$html .= '<h3><a href="archive-projects.html">Projects archive</a></h3>';
				$html .= '<h3><a href="archive-tasks.html">Tasks archive</a></h3>';
				break;
			}
		}
		return $html;
	}
}

?>
