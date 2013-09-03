<?php
require_once('base.php');

class add extends base{
	function __construct(){
		parent::__construct();
	}
	function add($act=''){
		$html = '';
		$act = $this->r('act')==''?$act:$this->r('act');
		$act2 = $this->r('act2');
		$act = $act=='home'||$act==''||$act=='projects'?'project':$act;
		$pti = new pti();
		$fin = new fin();
		$ftp = new ftp();
		$usr = new usr();
		
		switch($act){
			case'file':{
				$files = $this->q("SELECT id,name,contacts,cost FROM jos_whm AS f WHERE f.type='file' AND f.".$this->r('itype')."='".$this->r('iid')."' ORDER BY f.id ASC");
				$file = array();
				$html = '<select class="ffff">';
				while($f = mysql_fetch_assoc($files)){
					$file[$f['cost']][] = array('url'=>$f['name'],'name'=>$f['id'].': '.$f['contacts']);
				}
				$agroup = array('img','doc','xls','arch');
				foreach($agroup as $a){
					if(isset($file[$a])&&is_array($file[$a])){
						$html .= '<optgroup label="'.$a.'">';
						foreach($file[$a] as $f){
							$html .= '<option value="'.$a.'[-]'.$f['url'].'">- '.$f['name'].'</option>';
						}
						$html .= '</optgroup>';
					}
				}
				$html .= '
					</select>
					<input type="button" onclick="InsertFile(this)" value="'.$this->lng['insert'].'">';
				break;
			}
			case'finance':{
				switch($act2){
					case'add':{
						$html = '';
						$data = explode('[-=s=-]',$this->r('str'));
						for($i=0;$i<count($data)-1;$i++){
							$d = explode('[-s-]',$data[$i]);
							$sql1 = '';
							$sql2 = '';
							$sql3 = '';
							$sql4 = '';
							$val = $d[3];
							// echo '1='.$val;
							if($d[6]==59||$d[6]==60||$d[6]==774||$d[6]==776||$d[6]==61||$d[6]==62){
								
								
								if($d[6]==61){
									
									$sql2 = ",".$d[8];
									$project = mysql_fetch_assoc($this->q("SELECT * FROM projects WHERE id=".$d[8]));
									if($project['id']){
										$sql1 = ",pid";
										// $project = mysql_fetch_assoc($this->q("SELECT * FROM tasks WHERE id=".$d[8]));
									}
									else{
										$sql1 = ",tid";
									}
									$val = $project['vid'];
									// echo '2='.$val;
								}
								elseif($d[6]==62){
									$sql1 = ",pid";
									$sql1 .= ",costplaninreg,costplanoutreg,span";
									$sql2 = ",62,".$d[9].",".$d[10].",".$d[11];
								}
								else{
									$sql2 = ",'".$d[7]."'";
									$project = mysql_fetch_assoc($this->q("SELECT * FROM projects WHERE id=".$d[7]));
									if($project['id']){
										// $project = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE id=".$project['project']));
										$sql1 = ",pid";
									}
									else{
										$sql1 = ",tid";
									}
									$val = $project['vid'];
									// echo '3='.$val.' id='.$d[7];
									// if($d[6]==59||$d[6]==60){
										// $sql3 = ",span";
										// $sql4 = ",'0'";
									// }
								}
							}
							
							$sql = "INSERT INTO finance
								(name,date,vid,cost,oid,uid,cost1".$sql1.$sql3.")
								VALUES
								(
								'".($d[4]=='Name'?'':$d[4])."',
								'".$d[0].date(' H:i:s')."',
								'".$val."',
								'".$d[2]."',
								'".$d[1]."',
								'".$this->uid."',
								'".$d[5]."'
								".$sql2."
								".$sql4."
								)";
							// $html .= $sql;
							$this->q($sql);
						}
						$html .= $this->info('info','<strong>'.$this->lng['Added'].'</strong> '.$this->lng['added operation']).''.$fin->finance();
						break;
					}
					default:{
						$html = '';
						$html .= '';
						
						$html .= '
							<form action="javascript:void(0)" onsubmit="AddItemsForm(this,\'type=add&act2=add\',\''.$this->r('num').'\')" id="formadd">
								<table border="0" cellspacing="0" cellpadding="10" id="messwindowcontent" width="100%">
									<tr>
										<td colspan="2">
											<h3>'.$this->lng['add finance operation'].'</h3>
											<input type="hidden" name="act" value="finance" />
											<input type="hidden" name="fnameold" value="[-new-]" />
										</td>
									</tr>
									<tr class="d-n">
										<td><input type="button" value="'.$this->lng['valuteconverter'].'" onclick="jQuery(\'.valuteconverter\').slideToggle(500);" />
										</td>
										<td>
											<iframe class="valuteconverter" width="300" borderframe="0" src="http://tables.finance.ua/ru/currency/converter" height="400" style="display:none;"></iframe>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											
											<div class="addfinanceerror" style="display:none;">'.$this->info('alert',$this->lng['Error! Please check the fields are correct']).'</div>
											<div class="addfinance"><div class="addfinanceone">'.$this->addfinanceone().'</div></div>
											<input type="button" value="'.$this->lng['Add transaction'].'" onclick="var newdiv = document.createElement(\'DIV\');newdiv.className = \'addfinanceone\';jQuery(this).parent().find(\'.addfinance\').append(newdiv);Load(newdiv,\'type=addfinanceone\');var t = setTimeout(function(){jQuery(\'.addfinanceone .opname\').autocomplete({source: autocomplite,minLength:2});},1000);" class="bg-green" />
										</td>
									</tr>
								</table>
								<div style="width:100px;margin:0 auto;">
									<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green" onclick="jQuery(\'#formadd\').submit()"><span class="icongreen icon-check" title="Ок"></span></span>
									<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="'.$this->lng['Cancel'].'"></span></span>
								</div>
							</form>';
						break;
					}
				}
				break;
			}
			case'clients':{
				switch($act2){
					case'add':{
						$html = '';
						$this->q("INSERT INTO jos_whm (type,name,date1,user,status,contacts) VALUES ('client','".$this->r('name')."','".date('Y-m-d H:i')."','".$this->uid."','".$this->r('status')."','".$this->r('contacts')."')");
						$html .= $this->info('info','<strong>'.$this->lng['Added'].'!</strong> '.$this->lng['New client'].' <b>'.$this->r('name').'</b> '.$this->lng['added'].'').$usr->clients();
						break;
					}
					default:{
						$html = '<form action="javascript:void(0)" onsubmit="AddItemsForm(this,\'type=add&act2=add\',\''.$this->r('num').'\')" id="formadd">
								<table border="0" cellspacing="0" cellpadding="10" id="messwindowcontent'.$this->r('num').'" width="100%">
									<tr>
										<td colspan="2">
											<h3>'.$this->lng['Add client or contractor'].'</h3>
											<input type="hidden" name="type" value="add" />
											<input type="hidden" name="act" value="clients" />
											<input type="hidden" name="act2" value="add" />
										</td>
									</tr>
									<tr>
										<td>'.$this->lng['Name'].'</td>
										<td><input type="text" name="name" /></td>
									</tr>
									<tr>
										<td>'.$this->lng['Type'].'</td>
										<td><select name="status">
											<option value="0">Client</option>
											<option value="1">Contractor</option>
										</select></td>
									</tr>
									<tr>
										<td>'.$this->lng['Contacts'].'</td>
										<td><textarea name="contacts" style="width:300px;"></textarea></td>
									</tr>
								</table>
								<div style="width:100px;margin:0 auto;">
									<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green" onclick="FormDebug(e(\'messwindowcontent'.$this->r('num').'\'),e(\'content\'));Remove(e(\'messwindowin'.$this->r('num').'\'));"><span class="icongreen icon-check" title="Add"></span></span>
									<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="'.$this->lng['Cancel'].'"></span></span>
								</div>
							</form>
						';
						break;
					}
				}
				break;
			}
			case'project':{
				switch($act2){
					case'add':{
						if($this->r('clientact')=='old'){
							$cid = $this->r('client');
						}
						else{
							$this->q("INSERT INTO users (type,name,date,uid) VALUES (3,'".$this->r('client')."','".date('Y-m-d H:i:s')."','".$this->uid."')");
							$cid = mysql_insert_id();
						}
						$this->q("INSERT INTO projects (name,vid,date,date2,cid,uid,description,sets,priority) VALUES ('".$this->r('pname')."','".$this->r('pval')."','".date('Y-m-d H:i:s')."','".$this->r('date2')." 23:59:59','".$cid."','".$this->uid."','".$this->r('inputs')."','".$this->r('incost').":".$this->r('outcost').":::::0','".$this->r('prio')."')");
						$html = $this->info('info','<strong>'.$this->lng['Added'].'!</strong> Project <b>'.$this->r('pname').'</b> '.$this->lng['added'].'').$pti->projects();
						break;
					}
					default:{
						$clients = $this->q("SELECT * FROM users c WHERE type=3 AND uid='".$this->uid."' ORDER BY c.name");
						$html = '<form action="javascript:void(0)" onsubmit="AddItemsForm(this,\'type=add&act2=add\',\''.$this->r('num').'\')" id="formadd">
								<table border="0" cellspacing="0" cellpadding="2" id="messwindowcontent" width="100%">
									<tr>
										<td colspan="2">
											<h2>'.$this->lng['Add project'].'</h2>
											<input type="hidden" name="act" value="project" />
										</td>
									</tr>
									<tr>
										<td>'.$this->lng['Client'].'</td>
										<td><select name="client" onchange="if(this.value>0){e(\'addnewclient\').style.display=\'none\';}else{e(\'addnewclient\').style.display=\'\';}">
											<option value="0">'.$this->lng['- New -'].'</option>';
						while($c = mysql_fetch_assoc($clients)){
							$html .= '<option value="'.$c['id'].'">'.$c['name'].'</option>';
						}
						$html .= '
										</select></td>
									</tr>
									<tr id="addnewclient">
										<td>'.$this->lng['Name of client or organisation'].'</td>
										<td><input type="text" name="cname" /></td>
									</tr>
									<tr>
										<td colspan="2"><hr /></td>
									</tr>
									<tr>
										<td>'.$this->lng['Project name'].'</td>
										<td><input type="text" name="pname" /></td>
									</tr>
									<tr>
										<td>'.$this->lng['Project priority'].'</td>
										<td>
											<span class="prio0 icongreen icon-flag f-l m-7"></span>
											<span class="prio1 iconorange icon-flag f-l m-7" style="display:none;"></span>
											<span class="prio2 iconred icon-flag f-l m-7" style="display:none;"></span>
											<select name="pprio" style="width:80px!important;" onchange="jQuery(this).parent().find(\'span\').hide();jQuery(this).parent().find(\'span.prio\'+this.value).show();">
												<option value="2" class="bc-red c-fff">High</option>
												<option value="1" class="bc-orange c-fff">Middle</option>
												<option value="0" class="bc-green c-fff" selected="selected">Low</option>
											</select>
										</td>
									</tr>
									<tr>
										<td>'.$this->lng['Valute'].'</td>
										<td>
										<table cellpadding="5" cellspacing="0" border="0" class="f-l">
												<tr>';
						$i = 0;
						foreach($this->val as $k=>$v){
							$html .= '
								<td align="center">
									<input type="radio" name="pval" value="'.$k.'"'.($i==0?' checked="checked"':'').' class="d-n" />
									<span class=" d-b btn p-0 bg-'.($i==0?'green':'grey').' fs-12 ta-c" style="width:30px;height:30px;z-index:1;line-height:30px;" onclick="jQuery(this).parent().find(\'input[type=radio]\').attr(\'checked\',true);jQuery(this).parent().parent().find(\'.btn\').removeClass(\'bg-green\').addClass(\'bg-grey\');jQuery(this).addClass(\'bg-green\');">'.$v.'</span>
								</td>';
							$i++;
						}
						$html .= '
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td><input type="text" name="incost" class="f-l ta-r m-8" size="10" value="0" />&nbsp;'.$this->lng['In'].'</td>
										<td><input type="text" name="outcost" class="f-l ta-r m-8" size="10" value="0" />&nbsp;'.$this->lng['Out'].'</td>
									</tr>
									<tr>
										<td>'.$this->lng['Deadline'].'</td>
										<td><input type="text" name="date2" value="'.date('Y-m-d').'" class="datepicker" /></td>
									</tr>
									<tr>
										<td colspan="2"><hr /></td>
									</tr>
									<tr>
										<td>'.$this->lng['Additional information'].'</td>
										<td><textarea name="inputs" style="height:30px;width: 198px;"></textarea></td>
									</tr>
								</table>
								<div style="width:100px;margin:0 auto;">
									<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green" onclick="jQuery(\'#formadd\').submit()"><span class="icongreen icon-check" title="Ок"></span></span>
									<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="Cancel"></span></span>
								</div>
							</form>
						';
						break;
					}
				}
				break;
			}
			case'task':{
				switch($this->r('act2')){
					case'add':{
						$str_array = explode('[-s-]',$this->r('str'));
						$task = mysql_fetch_assoc($this->q("SELECT type FROM jos_whm AS t WHERE t.id='".$this->r('task')."'"));
						$a = array();
						$this->setses('itemid',0);
						$taskid = 0;
						$num = $this->r('num');
						if($task['type']=='project'){
							$this->q("INSERT INTO jos_whm (type,name,date1,project,user,date2) VALUES ('task','".$this->r('tasknew')."','".date('Y-m-d H:i')."','".$this->r('task')."','".$this->uid."','".$this->r('taskdate2')."')");
							$taskid = mysql_insert_id();
						}
						else{
							$taskid = $this->r('task');
						}
						$this->q("UPDATE jos_whm SET cost='".$this->r('cost')."',val='".$this->r('val')."' WHERE id='".$taskid."'");
						foreach($str_array as $str){
							if($str!=''){
								$s = explode('[-|-]',$str);
								$this->q("INSERT INTO jos_whm (type,name,date1,date2,level,task,pid) VALUES ('item','".$s[1]."','".$s[2]."','".$s[3]." 23:59:59','".$s[0]."','".$taskid."','".$this->ses('itemid'.substr($s[0],0,(strlen($s[0])==1?-1:-2)))."')");
								$this->setses('itemid'.$s[0],mysql_insert_id());
							}
						}
						$a = $_SESSION;
						foreach($str_array as $str){
							if($str!=''){
								$s = explode('[-|-]',$str);
								$n = 'itemid'.$s[0];
								$this->setses($n,false);
							}
						}
						$_REQUEST = array();
						$_REQUEST['task'] = $taskid;
						$_REQUEST['act'] = 'item';
						$_REQUEST['pid'] = 0;
						$_REQUEST['num'] = $num;
						$html = $this->add();
						break;
					}
					default:{
						$projects = $this->q("SELECT * FROM projects p WHERE sid!=4 AND sid!=3 AND uid='".$this->uid."' ORDER BY p.name");
						$tasks = $this->q("SELECT * FROM tasks t WHERE sid!=4 AND (uid='".$this->uid."' OR user='".$this->uid."') ORDER BY t.pid");
						$atasks = array();
						$i = 0;
						$sel = '';
						while($t = mysql_fetch_assoc($tasks)){
							$atasks[$t['project']][$t['id']] = $t['name'];
							$sel .= '<input type="text" class="taskcost ta-r" name="taskcost" p="'.$t['id'].'" value="'.$t['cost'].'" style="display:none;" />';
							$sel .= '<select class="taskval" p="'.$t['id'].'" name="taskval'.$t['id'].'" style="display:none;">';
							foreach($this->val as $k=>$v){
								$sel .= '<option value="'.$k.'"'.($k==$t['val']?' selected="selected"':'').'>'.$v.'</option>';
							}
							$sel .= '</select>';
							$i++;
						}
						$_REQUEST['act'] = '';
						$html = '
							<tr>
								<td colspan="2">
									<h3>'.$this->lng['Add task'].'</h3>
									<input type="hidden" name="act" value="task" />
								</td>
							</tr>
							<tr>
								<td width="200">
									'.$this->lng['Cost'].'<br />
									<input type="text" class="taskcost ta-r c-999" p="0" name="taskcost" value="0.00" onfocus="if(this.value==this.defaultValue){this.value = \'\';}this.style.color=\'#000\';" onblur="if(this.value==\'\'){this.value = this.defaultValue;this.style.color=\'#999\';}else{this.style.color=\'#000\';}" />
									<select name="taskval0" p="0" class="taskval">';
						foreach($this->val as $k=>$v){
							$html .= '<option value="'.$k.'">'.$v.'</option>';
						}
						$html .= '
									</select>
									'.$sel.'
								</td>
								<td>
									'.$this->lng['End date'].'<br />
									<input class="f-l" id="taskdate2" class="datepicker" onclick="jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd\'}).focus();" type="text" name="taskdate2" value="'.date('Y-m-d').'" />
								</td>
							</tr>
							<tr>
								<td>
									'.$this->lng['Project &rarr; old task'].'<br />
									<select name="task" p="0" onchange="if(jQuery(this).find(\':selected\').attr(\'class\')==\'c1\'){jQuery(this).attr(\'p\',\'1\');jQuery(\'.newtask\').hide();jQuery(\'.taskval\').hide();var n=\'.taskval[p=\'+this.value+\']\';jQuery(n).show();jQuery(\'.taskcost\').hide();var n=\'.taskcost[p=\'+this.value+\']\';jQuery(n).show();}else{jQuery(this).attr(\'p\',\'0\');jQuery(\'.newtask\').show();jQuery(\'.taskval\').hide();jQuery(\'.taskval[p=0]\').show();jQuery(\'.taskcost\').hide();jQuery(\'.taskcost[p=0]\').show();}">';
						while($p = mysql_fetch_assoc($projects)){
							$html .= '<optgroup label="'.$p['name'].'">';
							$html .= '<option value="'.$p['id'].'" class="c0">'.$this->lng['- New -'].'</option>';
							if(isset($atasks[$p['id']])){
								if(count($atasks[$p['id']])>0){
									foreach($atasks[$p['id']] as $key=>$at){
										$html .= '<option value="'.$key.'" class="c1" title="'.$key.'">'.$at.'</option>';
									}
								}
							}
							$html .= '</optgroup>';
						}
						$html .= '
								</select>
								</td>
								<td>
									<span class="newtask">
										'.$this->lng['Name'].'<br />
										<input id="addnewtask" type="text" class="taskid" name="tasknew" value="" style="width:200px;" />
									</span>
								</td>
							</tr>
							';
						break;
					}
				}
				break;
			}
			case'item':{
				switch($this->r('act2')){
					case'add':{
						$str_array = explode('[-s-]',$this->r('str'));
						$user = mysql_fetch_assoc($this->q("SELECT * FROM users WHERE id='".$this->uid."'"));
						$project = mysql_fetch_assoc($this->q("SELECT * FROM projects WHERE id='".$this->r('idproject')."'"));
						$a = array();
						$this->setses('itemid',$this->r('pid'));
						if($this->r('tasknew')=='1'){
							$this->q("INSERT INTO tasks (name,date,pid,cost,uid,user) VALUES ('".$this->r('taskname')."','".date('Y-m-d H:i')."','".$this->r('idproject')."','".$this->r('cost')."',".($user['uid']>0&&$project['uid']==$user['uid']?"'".$user['uid']."','".$this->uid."'":"'".$this->uid."','0'").")");
							$taskid = mysql_insert_id();
						}
						else{
							$taskid = $this->r('task');
							$task = mysql_fetch_assoc($this->q("SELECT * FROM tasks WHERE id='".$taskid."'"));
							$newcost = $task['cost']+(int)$this->r('cost');
							// $newcost2 = $task['costplanin']+(int)$this->r('cost2');
							$this->q("UPDATE tasks SET cost='".$newcost."' WHERE id='".$taskid."'");
						}
						foreach($str_array as $str){
							if($str!=''){
								$s = explode('[-|-]',$str);
								$this->q("INSERT INTO items (name,date,date4,lvl,tid,iid) VALUES ('".$s[1]."','".$s[2]."','".$s[3]."','".$s[0]."','".$taskid."','".$this->ses('itemid'.substr($s[0],0,(strlen($s[0])==1?-1:-2)))."')");
								$this->setses('itemid'.$s[0],mysql_insert_id());
							}
						}
						$a = $_SESSION;
						foreach($str_array as $str){
							if($str!=''){
								$s = explode('[-|-]',$str);
								$n = 'itemid'.$s[0];
								$this->setses($n,false);
							}
						}
						$html = $this->info('info','<strong>'.$this->lng['Added'].'!</strong> '.$this->lng['Added a new task'].' <b>#'.$this->r('task').'</b> '.$this->lng['items'].'').$pti->tasks($this->r('idproject'));
						break;
					}
					default:{
						$taskshtml = '';
						$idproject = $this->r('idproject');
						$i = 0;
						if($this->r('pid')=='0'){
							$taskid = $this->r('task');
							$taskshtml .= '<input type="hidden" name="task" value="'.$taskid.'" />';
						}
						elseif($this->r('pid')>0){
							$item = mysql_fetch_assoc($this->q("SELECT task FROM items WHERE id='".$this->r('pid')."'"));
							$taskid = $item['task'];
							$taskshtml .= '<input type="hidden" name="task" value="'.$taskid.'" />';
						}
						elseif(strlen($this->r('pid'))==0){
							$taskid = 0;
							$tasks = $this->q("SELECT t.*,p.name AS pname,p.vid FROM projects p LEFT JOIN tasks t ON t.pid=p.id WHERE p.sid<>4 AND t.sid<>4 AND p.uid='".$this->uid."'".($idproject>0?" AND p.id='".$idproject."'":'')." ORDER BY p.name");
							$project = mysql_fetch_assoc($this->q("SELECT * FROM projects WHERE id='".$idproject."'"));
							$taskshtml .= '<div><select name="task" class="add_taskold"'.($taskid>0?'':' style="display:none;max-width:200px;"').'>';
							$i = 0;
							if(mysql_num_rows($tasks)>0){
								while($t = mysql_fetch_assoc($tasks)){
									// if($i==0){$taskshtml .= '<optgroup label="'.$t['pname'].'">';}
									$taskshtml .= '<option value="'.$t['id'].'">'.$t['name'].' ('.$t['id'].', '.$this->val[$t['vid']].')</option>';
									// if($i==0){$taskshtml .= '</optgroup>';}
									$i++;
								}
							}
							$taskshtml .= '</select>
											<input type="text" name="taskname" class="add_taskname" value=""'.($taskid>0?' style="display:none;"':'').' />
											<span class="add_taskold fs-16"'.($taskid>0?'':' style="display:none;"').'> +</span>';
							$taskshtml .= '<span>'.($project['vid']==768?$this->val[$project['vid']].'<input type="text" class="ta-r" name="cost" value="0.00" style="width:50px!important;color:#999;" onfocus="if(this.value==this.defaultValue){this.value = \'\';}this.style.color=\'#000\';" onblur="if(this.value==\'\'){this.value = this.defaultValue;this.style.color=\'#999\';}else{this.style.color=\'#000\';}" />':'<input type="text" class="ta-r" name="cost" value="0.00" style="width:50px!important;color:#999;" onfocus="if(this.value==this.defaultValue){this.value = \'\';}this.style.color=\'#000\';" onblur="if(this.value==\'\'){this.value = this.defaultValue;this.style.color=\'#999\';}else{this.style.color=\'#000\';}" onkeyup="jQuery(this).parent().parent().find(\'.cost2\').val((this.value*val['.$project['vid'].']).toFixed(2))" />'.$this->val[$project['vid']]).'</span>';
							$taskshtml .= '';
							if($i>0){
								$taskshtml .= '	<span class="btn add_tasknewbtn" onclick="jQuery(this).hide();jQuery(\'.add_taskname\').show();jQuery(\'.add_taskold\').hide();jQuery(\'.add_taskoldbtn\').show();jQuery(\'.add_tasknew\').attr({value:\'1\'});jQuery(\'.add_taskfiles\').hide();jQuery(\'.add_tasknewval\').show();"'.($taskid>0?'':' style="display:none;"').'>'.$this->lng['New'].'</span>
											<span class="btn add_taskoldbtn" onclick="jQuery(this).hide();jQuery(\'.add_taskname\').hide();jQuery(\'.add_taskold\').show();jQuery(\'.add_tasknewbtn\').show();jQuery(\'.add_tasknew\').attr({value:\'0\'});jQuery(\'.add_taskfiles\').show();jQuery(\'.add_tasknewval\').hide();"'.($taskid>0?' style="display:none;"':'').'>'.$this->lng['Old'].'</span>';
							}
							$taskshtml .= '
								</div>
								<iframe class="valuteconverter" width="300" borderframe="0" src="http://tables.finance.ua/ru/currency/converter" height="400" style="display:none;"></iframe>'; 
						}
						$_REQUEST['act'] = '';
						$html = '
							<span class="close" onclick="jQuery(\'#addtaskform'.$idproject.'\').hide();jQuery(\'#addtaskform'.$idproject.'\').html(\'\');"><span class="icon3 icon-close" title="Close"></span></span>
							<form action="javascript:void(0)" onsubmit="AddItemsForm(this,\'type=add&act2=add\',\''.$this->r('num').'\')">
								<input type="hidden" name="act" value="item" />
								<input type="hidden" name="idproject" value="'.$idproject.'" />
								<input type="hidden" name="pid" value="'.$this->r('pid').'" />
								<input type="hidden" name="tasknew" class="add_tasknew" value="'.($taskid>0?'0':'1').'" />
								<table border="0" cellspacing="0" cellpadding="10" id="messwindowcontent">
									<tr>
										<td colspan="2">
											<h3>'.$this->lng['Add a task items'].' '.($taskid>0?'<b>#'.$taskid.'</b> ':'').'</h3>
											'.$taskshtml.'
										</td>
									</tr>
									<tr class="add_taskfiles"'.($taskid>0?'':' style="display:none;"').'>
										<td colspan="2">
											<input type="button" value="'.$this->lng['Add files'].'" onclick="jQuery(\'.files\').slideToggle(500)" /><br />
											<div class="files" style="display:none;">
											<iframe src="'.$this->site.'1or/php/file_form.php?type=project&iid='.$idproject.'&uid='.$this->uid.'" width="480" height="400" frameborder="0"></iframe>
											</div>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="checkbox" value="0" onclick="if(jQuery(\'head\').find(\'#tempstyle\').size()>0){jQuery(\'head\').append(\'<style type=text/css id=tempstyle>.itemorder{display:none;}</style>\');}else{jQuery(\'head\').find(\'#tempstyle\').remove()}" name="displaynumbers" /> '.$this->lng['Display numbers'].'<br />
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<ul id="tditems">
												<li class="itemli lid1">
													<div class="itemtxt">
														<span class="itemorder">1</span>
														<input type="text" class="itemdate1" readonly="" value="'.date('Y-m-d').'" onclick="jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd\'});" />
														<input type="text" class="itemdate2" readonly="" value="'.date('Y-m-d').'" onclick="jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd\'});" />
														<div class="itembtns">'.$this->btns('additem',$taskid,'task').'</div>
														<textarea class="itemtxtarea" onkeyup="AddItems(this,event,\'\',\''.date('Y-m-d').'\',\''.$taskid.'\',\'task\');"></textarea>
													</div>
												</li>
											</ul>
										</td>
									</tr>
								</table>
								<table border="0" cellspacing="0" cellpadding="10" width="100%">
									<tr>
										<td align="center">
											<input type="submit" name="submit" value="'.$this->lng['Add'].'" class="btn" />
											<input type="button" name="button" value="'.$this->lng['Cancel'].'" onclick="jQuery(\'#addtaskform'.$idproject.'\').hide();jQuery(\'#addtaskform'.$idproject.'\').html(\'\');" class="btn" />
										</td>
									</tr>
								</table>
							</form>
							';
						break;
					}
				}
				break;
			}
			case'userto':{
				switch($this->r('act2')){
					case'add':{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$user = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.id='".$this->r('uid')."'"));
						$addstr = $this->r('uid');
						$atype = array(
							'task'=>$this->lng['task'],
							'project'=>$this->lng['project'],
							'item'=>$this->lng['task item']
						);
						$utype = array(
							'task'=>'tasks.html',
							'project'=>'projects.html',
							'item'=>'tasks/'.$element['task'].'.html'
						);
						
						$text = '
							<p>'.$this->lng['You was stuck to the'].' '.$atype[$element['type']].' #'.$element['id'].'</p>
							<p>'.$this->lng['Go to'].' '.$atype[$element['type']].' http://whm.asdat.biz/p/'.$element['id'].'.html</p>';
						$this->email($user['email'],''.$this->lng['You was stuck to the'].' '.$atype[$element['type']],$text);
						$this->q("UPDATE jos_whm SET users='".$addstr."' WHERE id='".$this->r('eid')."'");
						$_REQUEST['act'] = 'userto';
						$_REQUEST['act2'] = '';
						$html = $this->add();
						break;
					}
					case'delete':{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$this->q("UPDATE jos_whm SET users='0' WHERE id='".$this->r('eid')."'");
						$_REQUEST['act'] = 'userto';
						$_REQUEST['act2'] = '';
						$html = $this->add();
						break;
					}
					default:{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$atype = array(
							'task'=>$this->lng['task'],
							'project'=>$this->lng['project'],
							'item'=>$this->lng['task item']
						);
						$html = '';
						if($element['users']>0){
							$html .= '<h3>'.$this->lng['added performer'].' <span class="icon-q p-2" title="To delete, click on the performer`s name" onmousehover="QTip(this,\'hover\')">?</span></h3>
								<div id="addedusers">';
							$html .= '<ul class="addedusers">';
							$user = mysql_fetch_assoc($this->q("SELECT login,id FROM jos_whm WHERE id='".$element['users']."'"));
							$html .= '<li onclick="if(confirm(\'Are you sure you want to remove this performer?\')){Page(\'add&act=userto&act2=delete&eid='.$element['id'].'&uid='.$user['id'].'&num='.$this->r('num').'\',e(\'messwindowin_2'.$this->r('num').'\'),\'\');Page(\'tasks&idproject='.$element['project'].'\',e(\'project_tasks'.$element['project'].'in\'),\'\');}">'.$user['login'].'</li>';
							$html .= '</ul>';
						}
						else{
							$html .= '<h2>'.$this->lng['Add a performer to the'].' '.$atype[$element['type']].' <b>"'.$element['name'].'"</b></h2>
								<form action="javascript:void(0)" onsubmit="">
									<input type="hidden" name="act" value="userto" />
									<table border="0" cellspacing="0" cellpadding="10" id="messwindowcontent">
										<tr>
											<td>Performer</td>
											<td><select name="user" class="userid">';
							$users = $this->q("SELECT id,login FROM jos_whm AS u WHERE u.type='user' AND u.status='0' AND u.user='".$this->uid."' ORDER BY u.login ASC");
							$html .= '<option disabled="disabled"> - Workers - </option>';
							while($u = mysql_fetch_assoc($users)){
								if($u['id']!=$element['users']){
									$html .= '<option value="'.$u['id'].'">'.$u['login'].'</option>';
								}
							}
							$html .= '<option disabled="disabled"> - Others - </option>';
							
							$users = $this->q("SELECT id,login FROM jos_whm AS u WHERE u.type='user' AND u.status='0' AND u.user=0 ORDER BY u.login ASC");
							while($u = mysql_fetch_assoc($users)){
								if($u['id']!=$element['users']){
									$html .= '<option value="'.$u['id'].'">'.$u['login'].'</option>';
								}
							}
							$html .= '</select>
											<input type="button" name="button" value="'.$this->lng['Add'].'" onclick="Page(\'add&act=userto&act2=add&eid='.$element['id'].'&uid=\'+jQuery(this).parent().find(\'.userid\').val()+\'&num='.$this->r('num').'\',e(\'messwindowin_2'.$this->r('num').'\'),\'\');Page(\'tasks&idproject='.$element['project'].'\',e(\'project_tasks'.$element['project'].'in\'),\'\');" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
										</td>
									</tr>
								</table>';
						}
						$html .= '
							</div>
								<table border="0" cellspacing="0" cellpadding="10" width="100%">
									<tr>
										<td align="center">
											<input type="button" name="button" value="'.$this->lng['Cancel'].'" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
										</td>
									</tr>
								</table>
							</form>';
						break;
					}
				}
				break;
			}
			case'sites':{
				switch($this->r('act2')){
					case'add':{
						$alias = $this->r('alias');
						$pass = mt_rand();
						$this->q("INSERT INTO jos_whm (type,level,date1,user,password) VALUES ('site','".$alias."','".date('Y.m.d H:i:s')."','".$this->uid."','".$pass."')");
						
						$id = mysql_insert_id();
						$name = $id;
						$server_ip="web-help-me.com";
						$server_login="admin";
						$server_pass="3pZyfZ28";
						$server_ssl="N";
						
						$furl = '195.64.154.77';
						$fuser = 'admin';
						$fpass = '3pZyfZ28';
						
						include_once('httpsocket.php');
						
						$sock = new HTTPSocket;
						$sock->set_login($server_login,$server_pass);
						$sock->set_method('POST');
						
						if($server_ssl == 'Y'){
							$sock->connect("ssl://".$server_ip, 2222);
						}
						else{
							$sock->connect($server_ip, 2222);
						}
						// mkdir('/home/admin/domains/web-help-me.com/public_html/sites/'.$id.$alias,0777);
						$sock->query('/CMD_API_DATABASES',array('action' => 'create','name' => $name,'user' => $name,'passwd' => $pass,'passwd2' => $pass,'notify' => 'yes'));
						$result = $sock->fetch_body();
						
						$sock->query('/CMD_API_FTP',array('action' => 'create','domain' => 'web-help-me.com','user' => $id,'type' => 'custom','passwd' => $pass,'passwd2' => $pass,'custom_val'=>'/home/admin/domains/web-help-me.com/public_html/sites/'.$id.''.$alias.''));
						$result = $sock->fetch_body();
						// $sock->query('/CMD_API_FILE_MANAGER',array('action' => 'multiple','permission' => '<anything>','chmod' => 777,'path'=>'/home/admin/domains/web-help-me.com/public_html/sites','select0'=>'/home/admin/domains/web-help-me.com/public_html/sites/'.$id.''.$alias.''));
						// $result = $sock->fetch_body();
						// echo $result;
						$conn = ftp_connect($furl) or die('Not Working '.$furl);
						$login = ftp_login($conn,$fuser,$fpass);
						// chmod('/home/admin/domains/web-help-me.com/public_html/sites/'.$id.$alias,0777);
						ftp_chmod($conn,0777,'/domains/web-help-me.com/public_html/sites/'.$id.$alias);
						$newfile = fopen('/home/admin/domains/web-help-me.com/public_html/sites/'.$id.$alias.'/index.php', 'w') or die("can't open file");
						fclose($newfile);
						copy('/home/admin/domains/web-help-me.com/public_html/1or/php/files/index.php','/home/admin/domains/web-help-me.com/public_html/sites/'.$id.$alias.'/index.php');
						
						$data = '
							URL:<br />
							<a href="http://sites.web-help-me.com/'.$id.$alias.'" target="_blank">sites.web-help-me.com/'.$id.$alias.'</a><hr />
							FTP:<br />
							ftphost: web-help-me.com<br />
							ftplogin: '.$id.'@web-help-me.com<br />
							ftppassword: '.$pass.'<hr />
							MySQL:<br />
							mysqlhost: localhost<br />
							mysqldatabase: admin_'.$name.'<br />
							mysqllogin: admin_'.$name.'<br />
							mysqlpassword: '.$pass.'';
						
						$this->q("UPDATE jos_whm SET contacts='".$data."' WHERE id='".$id."'");
						ftp_close($conn);
						$html = $this->info('info','<strong>'.$this->lng['added'].'!</strong> A new site created. Link: http://sites.web-help-me.com/'.$id.$alias.'').$this->sites();
						break;
					}
					default:{
						$_REQUEST['act'] = '';
						$html = '
							<form action="javascript:void(0)" onsubmit="AddItemsForm(this,\'type=add&act2=add\',\''.$this->r('num').'\')" id="formadd">
								<table border="0" cellspacing="0" cellpadding="10" id="messwindowcontent" width="100%">
									<tr>
										<td colspan="2">
											<h3>Add site</h3>
											<input type="hidden" name="act" value="sites" />
											<input type="hidden" name="cms" value="none" />
										</td>
									</tr>
									<tr>
										<td>
											Name (alias, ex: http://sites.web-help-me.com/1000newsite, where "newsite" is alias)
										</td>
										<td><input type="text" name="alias" value="" /></td>
									</tr>
								</table>
								<div style="width:100px;margin:0 auto;">
									<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green" onclick="jQuery(\'#formadd\').submit()"><span class="icongreen icon-check" title="Ок"></span></span>
									<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="Cancel"></span></span>
								</div>
							</form>
							';
						break;
					}
				}
				break;
			}
			case'portfolio':{
				switch($this->r('act2')){
					case'add':{
						$this->q("INSERT INTO jos_whm (type,name,date1,user) VALUES ('site','".$this->r('cms').'[-s-]'.$this->r('func')."','".date('Y.m.d H:i:s')."','".$this->uid."')");
						$id = mysql_insert_id();
						$this->q("UPDATE jos_whm SET contacts='".$data."' WHERE id='".$id."'");
						$html = $this->info('info','<strong>'.$this->lng['added'].'!</strong> A new site added.').$ftp->sites();
						break;
					}
					default:{
						$_REQUEST['act'] = '';
						$html = '
									<tr>
										<td colspan="2">
											<h3>Add элемент портфолио</h3>
											<input type="hidden" name="act" value="portfolio" />
										</td>
									</tr>
									<tr>
										<td>Название</td>
										<td><input type="text" name="name" value="" /></td>
									</tr>
									<tr>
										<td>Положение в структуре</td>
										<td>
											<select name="parent">
												<option value="0">Корень</option>';
						$html .= $this->posts('option');
						$html .= '
											</select>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="button" value="Дополнительные материалы" onclick="jQuery(\'#elsematerials\').toggle();" />
											<div id="elsematerials" style="display:none;">
												<table border="0">
													<tr>
														<td>Положение</td>
														<td>
															<select name="parent">
																<option value="0">Положение в структуре</option>';
						$html .= $this->posts('option');
						$html .= '
															</select>
														</td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
							';
						break;
					}
				}
				break;
			}
			case'post':{
				switch($this->r('act2')){
					case'add':{
						$this->q("INSERT INTO jos_whm (type,name,date1,user,pid) VALUES ('post','".$this->r('name')."','".date('Y.m.d H:i:s')."','".$this->uid."','".$this->r('parent')."')");
						$_REQUEST['act'] = '';
						$html = $this->info('info','<strong>'.$this->lng['added'].'!</strong> '.$this->lng['added position'].'.').$usr->users();
						break;
					}
					default:{
						$_REQUEST['act'] = '';
						$html = '
									<tr>
										<td colspan="2">
											<h3>Add position</h3>
											<input type="hidden" name="type" value="add" />
											<input type="hidden" name="act2" value="add" />
											<input type="hidden" name="act" value="post" />
										</td>
									</tr>
									<tr>
										<td>Name of the position</td>
										<td><input type="text" name="name" value="" /></td>
									</tr>
									<tr>
										<td>Place</td>
										<td>
											<select name="parent">
												<option value="0">Zero level</option>';
						$html .= $usr->posts('option');
						$html .= '
											</select>
										</td>
									</tr>
							';
						break;
					}
				}
				break;
			}
			case'topost':{
				switch($this->r('act2')){
					case'add':{
						$this->q("UPDATE jos_whm SET
							worker='".$this->r('post')."',
							cost='".$this->r('cost')."',
							val='".$this->r('pval')."',
							user='".$this->r('user')."',
							date2='".date('Y-m-d H:i:s')."'
							WHERE id='".$this->r('uuid')."'");
						$html = '';
						$user = mysql_fetch_assoc($this->q("SELECT login,cost FROM jos_whm WHERE id='".$this->r('uuid')."'"));
						$post = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm WHERE id='".$this->r('post')."'"));
						$_REQUEST['act'] = '';
						$html .= $this->info('info','<strong>'.$this->lng['added'].'!</strong> '.$this->lng['added user'].' <b>'.$user['login'].'</b> '.$this->lng['to position'].' <b>'.$post['name'].'</b> '.$this->lng['with salary'].' <b>'.($this->val['id']==768?$this->val['name'].''.$user['cost']:$user['cost'].' '.$this->val['name']).'</b>.').$this->users();
						break;
					}
					default:{
						$_REQUEST['act'] = '';
						$usr = new usr();
						$html = '
									<tr>
										<td colspan="2">
											<h3>'.$this->lng['add user to position'].'</h3>
											<input type="hidden" name="type" value="add" />
											<input type="hidden" name="act2" value="add" />
											<input type="hidden" name="act" value="topost" />
											<input type="hidden" name="user" value="'.$this->uid.'" />
										</td>
									</tr>
									<tr>
										<td>'.$this->lng['user'].'</td>
										<td><select name="uuid">'.$usr->users('users').'</select></td>
									</tr>
									<tr>
										<td>'.$this->lng['position'].'</td>
										<td><select name="post">'.$usr->posts('option').'</select></td>
									</tr>
									<tr>
										<td>'.$this->lng['Salary'].'</td>
										<td>
											'.($this->uval==768?$this->val[$this->uval].'<input type="text" name="cost" value="0" size="5" class="ta-r" />':'<input type="text" name="cost" value="0" size="5" class="ta-r" />'.$this->val[$this->uval]).'
											<input type="hidden" name="pval" value="'.$this->uval.'" />
										</td>
									</tr>
									<tr class="d-n">
										<td>'.$this->lng['Level'].'</td>
										<td>
											<table cellpadding="5" cellspacing="0" border="0" class="f-r">
												<tr>
													<td align="center" class="ftype">
														<p>HTML</p>
														<input type="radio" name="html" value="0" checked="checked" /> 0<br />
														<input type="radio" name="html" value="1" /> 1<br />
														<input type="radio" name="html" value="2" /> 2<br />
														<input type="radio" name="html" value="3" /> 3<br />
														<input type="radio" name="html" value="4" /> 4<br />
														<input type="radio" name="html" value="5" /> 5<br />
														<input type="radio" name="html" value="6" /> 6<br />
														<input type="radio" name="html" value="7" /> 7<br />
														<input type="radio" name="html" value="8" /> 8<br />
														<input type="radio" name="html" value="9" /> 9<br />
														<input type="radio" name="html" value="10" /> 10
													</td>
													<td align="center" class="ftype">
														<p>CSS</p>
														<input type="radio" name="css" value="0" checked="checked" /> 0<br />
														<input type="radio" name="css" value="1" /> 1<br />
														<input type="radio" name="css" value="2" /> 2<br />
														<input type="radio" name="css" value="3" /> 3<br />
														<input type="radio" name="css" value="4" /> 4<br />
														<input type="radio" name="css" value="5" /> 5<br />
														<input type="radio" name="css" value="6" /> 6<br />
														<input type="radio" name="css" value="7" /> 7<br />
														<input type="radio" name="css" value="8" /> 8<br />
														<input type="radio" name="css" value="9" /> 9<br />
														<input type="radio" name="css" value="10" /> 10
													</td>
													<td align="center" class="ftype">
														<p>PHP</p>
														<input type="radio" name="php" value="0" checked="checked" /> 0<br />
														<input type="radio" name="php" value="1" /> 1<br />
														<input type="radio" name="php" value="2" /> 2<br />
														<input type="radio" name="php" value="3" /> 3<br />
														<input type="radio" name="php" value="4" /> 4<br />
														<input type="radio" name="php" value="5" /> 5<br />
														<input type="radio" name="php" value="6" /> 6<br />
														<input type="radio" name="php" value="7" /> 7<br />
														<input type="radio" name="php" value="8" /> 8<br />
														<input type="radio" name="php" value="9" /> 9<br />
														<input type="radio" name="php" value="10" /> 10
													</td>
													<td align="center" class="ftype">
														<p>JavaScript</p>
														<input type="radio" name="js" value="0" checked="checked" /> 0<br />
														<input type="radio" name="js" value="1" /> 1<br />
														<input type="radio" name="js" value="2" /> 2<br />
														<input type="radio" name="js" value="3" /> 3<br />
														<input type="radio" name="js" value="4" /> 4<br />
														<input type="radio" name="js" value="5" /> 5<br />
														<input type="radio" name="js" value="6" /> 6<br />
														<input type="radio" name="js" value="7" /> 7<br />
														<input type="radio" name="js" value="8" /> 8<br />
														<input type="radio" name="js" value="9" /> 9<br />
														<input type="radio" name="js" value="10" /> 10
													</td>
													<td align="center" class="ftype">
														<p>MySQL</p>
														<input type="radio" name="mysql" value="0" checked="checked" /> 0<br />
														<input type="radio" name="mysql" value="1" /> 1<br />
														<input type="radio" name="mysql" value="2" /> 2<br />
														<input type="radio" name="mysql" value="3" /> 3<br />
														<input type="radio" name="mysql" value="4" /> 4<br />
														<input type="radio" name="mysql" value="5" /> 5<br />
														<input type="radio" name="mysql" value="6" /> 6<br />
														<input type="radio" name="mysql" value="7" /> 7<br />
														<input type="radio" name="mysql" value="8" /> 8<br />
														<input type="radio" name="mysql" value="9" /> 9<br />
														<input type="radio" name="mysql" value="10" /> 10
													</td>
													<td align="center" class="ftype">
														<p>Joomla</p>
														<input type="radio" name="joomla" value="0" checked="checked" /> 0<br />
														<input type="radio" name="joomla" value="1" /> 1<br />
														<input type="radio" name="joomla" value="2" /> 2<br />
														<input type="radio" name="joomla" value="3" /> 3<br />
														<input type="radio" name="joomla" value="4" /> 4<br />
														<input type="radio" name="joomla" value="5" /> 5<br />
														<input type="radio" name="joomla" value="6" /> 6<br />
														<input type="radio" name="joomla" value="7" /> 7<br />
														<input type="radio" name="joomla" value="8" /> 8<br />
														<input type="radio" name="joomla" value="9" /> 9<br />
														<input type="radio" name="joomla" value="10" /> 10
													</td>
												</tr>
											</table>
										</td>
									</tr>';
						break;
					}
				}
				break;
			}
			case'createfile':{
				switch($this->r('act2')){
					case'save':{
						$this->q("UPDATE jos_whm SET name='".$this->r('name')."',date2='".$this->r('date')."',project='".$this->r('project')."',cost='".$this->r('cost')."' WHERE id='".$this->r('tid')."'");
						$idproject = $this->r('project');
						$_REQUEST = array();
						$html = $this->info('info','<strong>'.$this->lng['Changed'].'!</strong> '.$this->lng['task'].' <b>#'.$this->r('idtask').'</b> '.$this->lng['saved'].'').$this->tasks($idproject);
						break;
					}
					default:{
						$path = $this->r('path');
						$p = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE id=".$this->r('pid').""));
						$ftp_server = $p['ftphost'];
						$ftp_user_name = $p['ftplogin'];
						$ftp_user_pass = $p['ftppass'];
						$conn_id = ftp_connect($ftp_server); 
						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
						ftp_pasv($conn_id,true);
						if($login_result){
							$html = '<table cellpadding="0" cellspacing="0" border="0" id="formedittask'.$p['id'].'" width="100%">
										<tr>
											<td>
												<input type="hidden" name="act" value="createfile" />
												<input type="hidden" name="act2" value="save" />
												<input type="hidden" name="type" value="edit" />
												<input type="hidden" name="tid" value="'.$this->r('tid').'" />
												';
							$html .= '
											
											<input type="text" name="name" value="'.$task['name'].'" /></td>
										</tr>
									</table>';
						}
						$_REQUEST['act'] = '';
						
						break;
					}
				}
				break;
			}
			default:{
				$html = '
					<h2>'.$this->lng['add'].'</h2>
					<p class="o-h" onclick="">
						<span class="btn f-l p-10 m-5 d-n" onclick="Load(e(\'messwindowcontent\'),\'type=add&act=task\');jQuery(\'#formaddsubmit\').removeClass(\'d-n\');jQuery(this).parent().find(\'.btn\').removeClass(\'bg-green\');jQuery(this).addClass(\'bg-green\');jQuery(\'#messwindowcontent\').ready(function(){jQuery(\'.datepicker\').datepicker({changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd\'});});"><span class="iconblue icon-document" title="Task"></span></span>
						<span class="btn f-l p-10 m-5 d-n" onclick="Load(e(\'messwindowcontent\'),\'type=add&act=project\');jQuery(\'#formaddsubmit\').removeClass(\'d-n\');jQuery(this).parent().find(\'.btn\').removeClass(\'bg-green\');jQuery(this).addClass(\'bg-green\');"><span class="iconorange icon-folder-collapsed" title="Project"></span></span>
						<span class="btn f-l p-10 m-5 d-n" onclick="Load(e(\'messwindowcontent\'),\'type=add&act=client\');jQuery(\'#formaddsubmit\').removeClass(\'d-n\');jQuery(this).parent().find(\'.btn\').removeClass(\'bg-green\');jQuery(this).addClass(\'bg-green\');"><span class="icongreen icon-person" title="Client"></span></span>
						<span class="btn f-l p-10 m-5 d-n" onclick="Load(e(\'messwindowcontent\'),\'type=add&act=finance\');jQuery(\'#formaddsubmit\').removeClass(\'d-n\');jQuery(this).parent().find(\'.btn\').removeClass(\'bg-green\');jQuery(this).addClass(\'bg-green\');"><span class="icongreen icon-calculator" title="Financing transaction"></span></span>
						<span class="btn f-l p-10 m-5 d-n" onclick="Load(e(\'messwindowcontent\'),\'type=add&act=sites\');jQuery(\'#formaddsubmit\').removeClass(\'d-n\');jQuery(this).parent().find(\'.btn\').removeClass(\'bg-green\');jQuery(this).addClass(\'bg-green\');"><span class="iconrose icon-script" title="Site"></span></span>
						'.($act=='users'?'
						<span class="btn f-l p-10 m-5" onclick="Load(e(\'messwindowcontent\'),\'type=add&act=post\');jQuery(\'#formaddsubmit\').removeClass(\'d-n\');jQuery(this).parent().find(\'.btn\').removeClass(\'bg-green\');jQuery(this).addClass(\'bg-green\');"><span class="iconorange icon-star" title="Position"></span></span>
						<span class="btn f-l p-10 m-5" onclick="Load(e(\'messwindowcontent\'),\'type=add&act=topost\');jQuery(\'#formaddsubmit\').removeClass(\'d-n\');jQuery(this).parent().find(\'.btn\').removeClass(\'bg-green\');jQuery(this).addClass(\'bg-green\');" style="width:48px;"><span class="iconblue icon-person f-l" title="User to position"></span><span class="icon0 icon-arrowthick-1-e f-l" title="User to position"></span><span class="iconorange icon-star f-l" title="User to position"></span></span>
						':'').'
						<span class="btn f-l p-10 m-5 d-n" onclick="Load(e(\'messwindowcontent\'),\'type=add&act=portfolio\');jQuery(\'#formaddsubmit\').removeClass(\'d-n\');jQuery(this).parent().find(\'.btn\').removeClass(\'bg-green\');jQuery(this).addClass(\'bg-green\');"><span class="iconblue icon-suitcase" title="Portfolio element"></span></span>
					</p>
					<form action="javascript:void(0)" onsubmit="FormDebug(this,e(\'content\'));Remove(e(\'messwindowin'.$this->r('num').'\'))" id="formadd">
						<table border="0" cellspacing="0" cellpadding="10" id="messwindowcontent" width="100%">
							<tr>
								<td><input type="hidden" name="act" value="none" /></td>
							</tr>
						</table>
						<div style="width:100px;margin:0 auto;">
							<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green d-n" onclick="jQuery(\'#formadd\').submit()"><span class="icongreen icon-check" title="Ок"></span></span>
							<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="Cancel"></span></span>
						</div>
					</form>';
				break;
			}
		}
		return $html;
	}
	function addfinanceone(){
		$projects = $this->q("SELECT * FROM projects WHERE sid!=4 AND sid!=3 AND uid='".$this->uid."' ORDER BY name");
		$operation = $this->q("SELECT * FROM operations WHERE 1 ORDER BY name ASC");
		$users = $this->q("SELECT * FROM users WHERE uid='".$this->uid."' ORDER BY login ASC");
		$html = '';
		// $jshtml = '';
		// $jshtml .= '<script>
				// var valarray = new Array();';
		$html = '
			<input type="text" name="opname" class="opname f-l" title="Transaction name" value="Name" style="width:255px;color:#999;height:18px;" onfocus="if(this.value==this.defaultValue){this.value = \'\';}this.style.color=\'#000\';" onblur="if(this.value==\'\'){this.value = this.defaultValue;this.style.color=\'#999\';}else{this.style.color=\'#000\';}" />
			<input type="text" name="opcost" class="ta-r opcost f-l" value="0.00" id="fcost" style="width:50px!important;color:#999;height:18px;" onfocus="if(this.value==this.defaultValue){this.value = \'\';}this.style.color=\'#000\';" onblur="if(this.value==\'\'){this.value = this.defaultValue;this.style.color=\'#999\';}else{this.style.color=\'#000\';}" onkeyup="jQuery(this).parent().find(\'.cost2\').val((this.value*val[jQuery(this).parent().find(\'.opvalute\').val()]).toFixed(2))" />
			<select style="width:65px!important;" name="opvalute" class="opvalute f-l" onchange="jQuery(this).parent().find(\'.cost2\').val((jQuery(this).parent().find(\'.opcost\').val()*val[this.value]).toFixed(2));">';
		$i = 0;
		foreach($this->val as $k=>$v){
			$html .= '<option value="'.$k.'">'.$v.'</option>';
			$i++;
		}
		
		$html .= '
			</select>
			<select name="optype" class="optype f-l" style="width:170px!important;" title="Transaction type" onchange="FinanceOperation(this.value,this)">
				<option value="0">- Select operation -</option>';
		while($o = mysql_fetch_assoc($operation)){
			$html .= '<option value="'.$o['id'].'">'.$o['name'].'</option>';
		}
		
		$html .= '</select>
			<select name="op774776" class="op774776 f-l" style="width:150px!important;display:none;" title="Project-Task" onchange="FinanceOperation(\'project\',this)">';
		while($p = mysql_fetch_assoc($projects)){
			$tasks = $this->q("SELECT * FROM tasks WHERE pid='".$p['id']."' AND sid!=4 ORDER BY name");
			if(mysql_num_rows($tasks)>0){
				$html .= '<optgroup label="'.$p['name'].'">';
				while($t = mysql_fetch_assoc($tasks)){
					$html .= '<option value="'.$t['id'].'">'.$t['name'].'</option>';
				}
				$html .= '</optgroup>';
			}
			else{
				$html .= '<option value="'.$p['id'].'">[ '.$p['name'].' ]</option>';
			}
			// $jshtml .= 'valarray['.$p['id'].'] = '.$p['val'].';';
		}
		$html .= '
			</select>
			<select name="op61" class="op61 f-l" style="width:140px!important;display:none;" title="Employee">';
		while($u = mysql_fetch_assoc($users)){
			$html .= '<option value="'.$u['id'].'">'.$u['login'].'</option>';
		}
		$html .= '</select>
			<input class="datepicker f-l" style="width:70px!important;height:18px;" onclick="jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd\'}).focus();" type="text" name="date" value="'.date('Y-m-d').'" />
			<span class="btn f-l p-5" onclick="this.parentNode.parentNode.removeChild(this.parentNode);"><span class="iconred icon-closethick" title="Delete"></span></span>
			<div class="conv cl-b" style="display:none;">
				Fee (from valute): <input type="text" class="fromfee ta-r" value="0.00" style="width:50px!important;color:#999;height:18px;" onfocus="if(this.value==this.defaultValue){this.value = \'\';}this.style.color=\'#000\';" onblur="if(this.value==\'\'){this.value = this.defaultValue;this.style.color=\'#999\';}else{this.style.color=\'#000\';}" />
				&nbsp;
				To: <input type="text" class="to ta-r" value="0.00" style="width:50px!important;color:#999;height:18px;" onfocus="if(this.value==this.defaultValue){this.value = \'\';}this.style.color=\'#000\';" onblur="if(this.value==\'\'){this.value = this.defaultValue;this.style.color=\'#999\';}else{this.style.color=\'#000\';}" />
				<select class="toval" style="width:65px!important;">';
		foreach($this->val as $k=>$v){
			$html .= '<option value="'.$k.'">'.$v.'</option>';
		}
		
		$html .= '</select>
			</div>
			';
			
		// $jshtml .= '</script>';
		// $html .= $jshtml;
		return $html;
	}
	function edit($act=''){
		$html = '';
		$act = $this->r('act')==''?$act:$this->r('act');
		$act2 = $this->r('act2');
		$pti = new pti();
		$fin = new fin();
		$ftp = new ftp();
		$usr = new usr();
		switch($act){
			case'finance':{
				switch($act2){
					case'add':{
						$html = '';
						$operation = $this->q("SELECT id,name FROM jos_whm AS o WHERE o.type='operation' ORDER BY o.id ASC");
						$op = array();
						while($o = mysql_fetch_assoc($operation)){
							$op[$o['id']] = $o['name'];
						}
						$project = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm AS p WHERE p.type='project' AND p.id='".$this->r('fproject')."'"));
						$this->q("INSERT INTO jos_whm (type,name,date1,val,cost,project,pid,user) VALUES ('finance','".$this->r('fname')."','".date('Y-m-d H:i')."','".$this->r('fval')."','".$this->r('fcost')."','".$this->r('fproject')."','".$this->r('ftype')."','".$this->uid."')");
						$html .= $this->info('info','<strong>'.$this->lng['added'].'!</strong> Operation <b>'.$op[$this->r('ftype')].($this->r('ftype')==774||$this->r('ftype')==776?' '.$project['name']:'').': '.$this->r('fname').'</b> added. Value <b>'.($this->r('fval')==768?$this->val[$this->r('fval')].''.number_format($this->r('fcost'),2,'.',' '):number_format($this->r('fcost'),2,'.',' ').''.$this->val[$this->r('fval')]).'</b>').$this->finance();
						break;
					}
					default:{
						$projects = $this->q("SELECT * FROM projects WHERE sid!=4 AND uid='".$this->uid."' ORDER BY name");
						$operation = $this->q("SELECT * FROM operations WHERE 1 ORDER BY id ASC");
						$html = '
									<tr>
										<td colspan="2">
											<h3>Add financial transaction</h3>
											<input type="hidden" name="act" value="finance" />
										</td>
									</tr>
									<tr>
										<td>Transaction name</td>
										<td><input type="text" name="fname" /></td>
									</tr>
									<tr>
										<td>Type of transaction</td>
										<td>
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>';
						$i = 0;
						while($o = mysql_fetch_assoc($operation)){
							$html .= '
								<td align="center" class="ftype">
									<input type="radio" name="ftype" value="'.$o['id'].'"'.($i==0?' checked="checked"':'').' onclick="if(getRadio(ftype)==774||getRadio(ftype)==776){e(\'pdetail\').style.display=\'\';}else{e(\'pdetail\').style.display=\'none\';}" /><br />'.$o['name'].'
								</td>';
							$i++;
						}
						$html .= '
												</tr>
											</table>
										</td>
									</tr>
									<tr id="pdetail">
										<td>Project</td>
										<td><select name="fproject">';
						while($p = mysql_fetch_assoc($projects)){
							$html .= '<option value="'.$p['id'].'">'.$p['name'].'</option>';
						}
						$html .= '
										</select></td>
									</tr>
									<tr>
										<td>Value</td>
										<td>
											<input type="text" name="fcost" />
											<table cellpadding="5" cellspacing="0" border="0">
												<tr>';
						$i = 0;
						foreach($this->val as $k=>$v){
							$html .= '
								<td align="center" class="ftype">
									<input type="radio" name="fval" value="'.$k.'"'.($i==0?' checked="checked"':'').' /><br />'.$this->valcost[$k].'
								</td>';
							$i++;
						}
						$html .= '</tr>
									</table>
								</td>
							</tr>';
						break;
					}
				}
				break;
			}
			case'client':{
				switch($act2){
					case'save':{
						$html = '';
						$this->q("UPDATE jos_whm SET name='".$this->r('name')."',status='".$this->r('status')."',contacts='".$this->r('contacts')."' WHERE id='".$this->r('cid')."'");
						$a = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS c WHERE c.id='".$this->r('cid')."'"));
						$html .= $this->client($this->r('i'),$a);
						break;
					}
					default:{
						$client = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS c WHERE c.id='".$this->r('cid')."'"));
						$cst = array('Client','Contractor');
						$html = '<table cellpadding="5" cellspacing="0" border="0" id="formeditclient'.$client['id'].'" width="100%">
								<tr>
									<td colspan="2">
										<h3>Edit client</h3>
										<input type="hidden" name="type" value="edit" />
										<input type="hidden" name="act" value="client" />
										<input type="hidden" name="act2" value="save" />
										<input type="hidden" name="cid" value="'.$this->r('cid').'" />
										<input type="hidden" name="i" value="'.$this->r('i').'" />
									</td>
								</tr>
								<tr>
									<td>Name</td>
									<td><input type="text" name="name" value="'.$client['name'].'" /></td>
								</tr>
								<tr>
									<td>Type</td>
									<td><select name="status">';
						foreach($cst as $k=>$c){
							$html .= '<option value="'.$k.'"'.($k==$client['status']?' selected="selected"':'').'>'.$c.'</option>';
						}
						$html .= '
										
									</td>
								</tr>
								<tr>
									<td>Contacts</td>
									<td><textarea name="contacts" style="width:500px;">'.$client['contacts'].'</textarea></td>
								</tr>
								<tr>
									<td>
										<span class="btn f-r p-5" onclick="FormDebug(jQuery(\'#formeditclient'.$client['id'].'\'),e(\'client'.$client['id'].'\'));jQuery(\'ul.clients>li\').removeClass(\'active\');Remove(e(\'messwindowin'.$this->r('num').'\'));"><span class="iconlblue icon-disk" title="Save"></span></span>
									</td>
									<td>
										<span class="btn f-l p-5" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'));"><span class="iconred icon-close" title="Cancel"></span></span>
									</td>
								</tr>
							</table>';
						break;
					}
				}
				break;
			}
			case'project':{
				switch($act2){
					case'save':{
						$html = '';
						$id = $this->r('pid');
						$k = $this->r('k');
						$st = $this->r('st');
						$sst = $this->r('sst');
						$stcolor = $this->r('stcolor');
						$this->q("UPDATE projects SET name='".$this->r('name')."',date2='".$this->r('date')."',cid='".$this->r('client')."',description='".$this->r('inputs')."',sets='".$this->r('cost').":".$this->r('costplanin').":".$this->r('domain').":".$this->r('ftphost').":".$this->r('ftplogin').":".$this->r('ftppass').":".$this->r('ftpssl')."',vid='".$this->r('valute')."',priority='".$this->r('pprio')."' WHERE id='".$this->r('pid')."'");
						$_REQUEST = array();
						$p = mysql_fetch_assoc($this->q("SELECT p.*,c.name AS cname FROM projects p
							LEFT JOIN users c ON
								c.type=3
								AND c.id=p.cid
							WHERE
								p.id=".$id."
							ORDER BY p.id ASC"));
						$priority_color = array('green','orange','red');
						$html .= '
									<span class="prio0 icon'.$priority_color[$p['priority']].' icon-bullet f-l" title="priority"></span>
									<table cellspacing="0" cellpadding="3" border="0" class="tbl">
										<tr>
											'.($p['uid']==$this->uid?'
											<td width="10" class="fs-14 ta-c va-m" onclick="Project(\''.$p['id'].'\');" style="cursor:pointer;">
												<input class="projectcheck f-l" type="checkbox" onclick="ProjectsCheck();" value="'.$p['id'].'" /><br />
											</td>
											':'').'
											<td width="20" class="fs-14 ta-c va-m" onclick="Project(\''.$p['id'].'\');" style="cursor:pointer;">
												'.$k.'.
											</td>
											'.$pti->project($p,$st,$sst,$stcolor).'
										<div id="project_tasks'.$p['id'].'bg" class="project_tasksbg" onclick="Project(\''.$p['id'].'\');"></div>
										<div id="project_tasks'.$p['id'].'" class="project-tasks">
											<div class="arrow"></div>
											<div id="project_tasks'.$p['id'].'in"></div>
										</div>
										<div id="pftp'.$p['id'].'" style="display:none;padding: 0 0 20px;"></div>';
						break;
					}
					default:{
						$clients = $this->q("SELECT * FROM users c WHERE type=3 AND uid='".$this->uid."' ORDER BY c.name");
						$project = mysql_fetch_assoc($this->q("SELECT * FROM projects WHERE id='".$this->r('pid')."'"));
						$params = explode(':',$project['sets']);
						$_REQUEST['act'] = '';
						$html = '
							<h3>Edit project data</h3>
							<div id="formeditproject'.$project['id'].'">
										<div>
											<input type="hidden" name="k" value="'.$this->r('k').'" />
											<input type="hidden" name="st" value="'.$this->r('st').'" />
											<input type="hidden" name="sst" value="'.$this->r('sst').'" />
											<input type="hidden" name="stcolor" value="'.$this->r('stcolor').'" />
											<input type="hidden" name="act" value="project" />
											<input type="hidden" name="act2" value="save" />
											<input type="hidden" name="type" value="edit" />
											<input type="hidden" name="pid" value="'.$project['id'].'" />
											<input type="text" name="name" value="'.$project['name'].'" />
											<input type="text" name="cost" value="'.$project['cost'].'" style="width:50px;text-align:right;" />
											<select name="valute" style="width:60px!important;">';
						foreach($this->val as $k=>$v){
							$html .= '<option value="'.$k.'"'.($k==$project['vid']?' selected="selected"':'').'>'.$v.'</option>';
						}
						$html .= '</select>
										</div>
										<div>
											<select name="client" style="width:180px;">';
						while($c = mysql_fetch_assoc($clients)){
							$html .= '<option value="'.$c['id'].'"'.($c['id']==$project['cid']?' selected="selected"':'').'>'.$c['name'].'</option>';
						}
						
						$html .= '</select> Client
										</div>
										<div>
											<span class="prio0 icongreen icon-flag f-l m-7"'.($project['priority']==0?'':' style="display:none;"').'></span>
											<span class="prio1 iconorange icon-flag f-l m-7"'.($project['priority']==1?'':' style="display:none;"').'></span>
											<span class="prio2 iconred icon-flag f-l m-7"'.($project['priority']==2?'':' style="display:none;"').'></span>
											<select name="pprio" style="width:80px!important;" onchange="jQuery(this).parent().find(\'span\').hide();jQuery(this).parent().find(\'span.prio\'+this.value).show();">
												<option value="2" class="bc-red c-fff"'.($project['priority']==2?' selected="selected"':'').'>High</option>
												<option value="1" class="bc-orange c-fff"'.($project['priority']==1?' selected="selected"':'').'>Middle</option>
												<option value="0" class="bc-green c-fff"'.($project['priority']==0?' selected="selected"':'').'>Low</option>
											</select> Project priority
										</div>
										<div>
											<input type="text" name="date" readonly="" value="'.date('Y-m-d',strtotime($project['date2'])).'" onclick="displayCalendar(this,\'yyyy-mm-dd\',this,true)" /> The end date
										</div>
										<div>
											<input type="text" name="costplanin" value="'.$params[0].'" style="width:70px;text-align:right;" /> In once
										</div>
										<div>
											<input type="text" name="costplanout" value="'.$params[1].'" style="width:70px;text-align:right;" /> Out once
										</div>
										<div class="d-n">
											<input type="text" name="costplaninreg" value="'.$project['costplaninreg'].'" style="width:70px;text-align:right;" /> In regular
										</div>
										<div class="d-n">
											<input type="text" name="costplanoutreg" value="'.$project['costplanoutreg'].'" style="width:70px;text-align:right;" /> Out regular
										</div>
										<br />
										<h3>FTP</h3>
										<div><input type="text" name="domain" value="'.$params[2].'" style="width:120px;" /> Domain</div>
										<div><input type="text" name="ftphost" value="'.$params[3].'" style="width:120px;" /> FTP host</div>
										<div><input type="text" name="ftplogin" value="'.$params[4].'" style="width:120px;" /> FTP login</div>
										<div><input type="text" name="ftppass" value="'.$params[5].'" style="width:120px;" /> FTP password</div>
										<div><input type="checkbox" name="ftpssl" value="'.$params[6].'"'.($params[6]?' checked="checked"':'').' onclick="if(this.checked){this.value=1;}else{this.value=0;}" /> FTP SSL</div>
									<br />
									<div>
										<span class="js d-n" onclick="jQuery(this).hide();jQuery(\'.editadditional\').slideDown(500)">Additional</span>
										<textarea class="editadditional" name="inputs" style="height:100px;display:;">'.str_replace('[-quot-]',"'",str_replace('[-amp-]','&',str_replace('<br />'," \n",$project['description']))).'</textarea>
									</div>
								<div class="editadditional" style="display:;">
										<span class="btn f-l" onclick="FormDebug(jQuery(\'#formeditproject'.$project['id'].'\'),e(\'project'.$project['id'].'\'));jQuery(\'ul.projects>li\').removeClass(\'active\');Remove(e(\'messwindowin'.$this->r('num').'\'));"><span class="iconlblue icon-disk" title="Save"></span></span>
										<span class="btn f-l" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'));"><span class="iconred icon-close" title="Cancel"></span></span>
								</div>
							</div>';
						break;
					}
				}
				break;
			}
			case'task':{
				switch($this->r('act2')){
					case'save':{
						$this->q("UPDATE jos_whm SET name='".$this->r('name')."',date2='".$this->r('date')."',project='".$this->r('project')."',cost='".$this->r('cost')."',public='".$this->r('public')."' WHERE id='".$this->r('tid')."'");
						$idproject = $this->r('project');
						$_REQUEST = array();
						$html = $this->info('info','<strong>Changed!</strong> Task <b>#'.$this->r('idtask').'</b> saved').$pti->tasks($idproject);
						break;
					}
					default:{
						$projects = $this->q("SELECT * FROM jos_whm AS p WHERE type='project' AND status!=4 AND (user='".$this->uid."' OR users LIKE '%]".$this->uid."[-s-]%') ORDER BY p.name");
						$task = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS t WHERE id='".$this->r('tid')."'"));
						$_REQUEST['act'] = '';
						$pval = array();
						$prs = array();
						while($p = mysql_fetch_assoc($projects)){
							$prs[$p['id']] = $p['name'];
							$pval[$p['id']] = $p['val'];
							
						}
						$html = '<table cellpadding="0" cellspacing="0" border="0" id="formedittask'.$task['id'].'" width="100%">
									<tr>
										<td>
											<input type="hidden" name="act" value="task" />
											<input type="hidden" name="act2" value="save" />
											<input type="hidden" name="type" value="edit" />
											<input type="hidden" name="tid" value="'.$this->r('tid').'" />
											';
						foreach($pval as $k=>$v){
							$html .= '<input type="hidden" value="'.$this->val[$v].'" class="p'.$k.'" />';
						}
						$html .= '<select name="project" style="width:130px!important;" onchange="jQuery(\'.val'.$task['id'].'\').html(jQuery(\'.p\'+this.value).val())">';
						foreach($prs as $k=>$v){
							$html .= '<option value="'.$k.'"'.($k==$task['project']?' selected="selected"':'').'>'.$v.'</option>';;
						}
						$html .= '</select>
										</td>
										<td><input type="text" name="name" value="'.$task['name'].'" style="width:60px!important;" /></td>
										<td><input type="text" name="cost" style="width:60px!important;text-align:right;" value="'.$task['cost'].'" /></td>
										<td><span class="val'.$task['id'].'" style="width:60px;">'.$this->val[$pval[$task['project']]].'</span></td>
										<td><input type="text" name="date" onclick="jQuery(this).datepicker({changeMonth: true,changeYear: true,dateFormat: \'yy-mm-dd\'}).focus();" value="'.$task['date2'].'" style="width: 65px;" /></td>
										<td><input type="checkbox" name="public" value="'.$task['public'].'"'.($task['public']==1?' checked="checked"':'').' onclick="if(jQuery(this).is(\':checked\')){this.value = 1;}else{this.value = 0;}" />'.$this->lng['Public'].'</td>
										<td width="20"><span class="btn f-r" onclick="FormDebug(jQuery(\'#formedittask'.$task['id'].'\'),e(\'project_tasks'.$task['project'].'\'))"><span class="iconlblue icon-disk" title="Save task"></span></span></td>
										<td width="20"><span class="btn f-r" onclick="jQuery(\'#task'.$task['id'].'edit\').html(\'\')"><span class="iconred icon-closethick" title="'.$this->lng['Cancel'].'"></span></span></td>
									</tr>
								</table>';
						break;
					}
				}
				break;
			}
			case'createfile':{
				switch($this->r('act2')){
					case'save':{
						$this->q("UPDATE jos_whm SET name='".$this->r('name')."',date2='".$this->r('date')."',project='".$this->r('project')."',cost='".$this->r('cost')."' WHERE id='".$this->r('tid')."'");
						$idproject = $this->r('project');
						$_REQUEST = array();
						$html = $this->info('info','<strong>Changed!</strong> Task <b>#'.$this->r('idtask').'</b> saved').$this->tasks($idproject);
						break;
					}
					default:{
						$path = $this->r('path');
						$p = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE id=".$this->r('pid').""));
						$ftp_server = $p['ftphost'];
						$ftp_user_name = $p['ftplogin'];
						$ftp_user_pass = $p['ftppass'];
						$conn_id = ftp_connect($ftp_server); 
						$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
						ftp_pasv($conn_id,true);
						if($login_result){
							$html = '<table cellpadding="0" cellspacing="0" border="0" id="formedittask'.$p['id'].'" width="100%">
										<tr>
											<td>
												<input type="hidden" name="act" value="createfile" />
												<input type="hidden" name="act2" value="save" />
												<input type="hidden" name="type" value="edit" />
												<input type="hidden" name="tid" value="'.$this->r('tid').'" />
												';
							$html .= '
											
											<input type="text" name="name" value="'.$task['name'].'" /></td>
										</tr>
									</table>';
						}
						$_REQUEST['act'] = '';
						
						break;
					}
				}
				break;
			}
			case'item':{
				switch($this->r('act2')){
					case'add':{
						// print_r($_REQUEST);
						$this->q("UPDATE items SET 
								name='".$this->r('name')."',
								date='".$this->r('date1')."',
								date4='".$this->r('date2')."',
								tid='".$this->r('task')."'
							WHERE id='".$this->r('pid')."'");
						$html = $this->info('info','<strong>Updated!</strong> Item <b>#'.$this->r('pid').'</b> updated');
						break;
					}
					default:{
						$projects = $this->q("SELECT * FROM projects p WHERE sid!=4 AND uid='".$this->uid."' ORDER BY p.name");
						$item = mysql_fetch_assoc($this->q("SELECT * FROM items i WHERE i.id='".$this->r('idin')."'"));
						$html = '<form action="javascript:void(0)" onsubmit="FormDebug(this,e(\'i'.$this->r('idin').'e\'))">
								<input type="hidden" name="act" value="item" />
								<input type="hidden" name="type" value="edit" />
								<input type="hidden" name="act2" value="add" />
								<input type="hidden" name="pid" value="'.$this->r('idin').'" />
								<table border="0" cellspacing="0" cellpadding="5" id="messwindowcontent">
									<tr>
										<td colspan="2">
											<input type="text" name="name" value="'.$item['name'].'" style="width:200px;" />
										</td>
										<td>
											<input type="text" name="date1" value="'.$item['date'].'" style="width:100px;" />
										</td>
										<td>
											<input type="text" name="date2" value="'.$item['date4'].'" style="width:100px;" />
										</td>
										<td>
											<select name="task" style="width:200px;">';
						while($p = mysql_fetch_assoc($projects)){
							$tasks = $this->q("SELECT * FROM tasks WHERE pid='".$p['id']."'");
							$html .= '<optgroup label="'.$p['name'].'">';
							while($t = mysql_fetch_assoc($tasks)){
								$html .= '<option value="'.$t['id'].'"'.($t['id']==$item['tid']?' selected="selected"':'').'>'.$t['name'].'</option>';
							}
							$html .= '</optgroup>';
						}
						
						$html .= '</select>
										</td>
									</tr>
									<tr>
										<td align="center" colspan="4">
											<input type="submit" name="submit" value="Update" class="btn" />
											<input type="button" name="button" value="Cancel" onclick="jQuery(\'#i'.$this->r('idin').'e\').html(\'\');" class="btn" />
										</td>
									</tr>
								</table>
							</form>';
						break;
					}
				}
				break;
			}
			case'userto':{
				switch($this->r('act2')){
					case'add':{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$user = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.id='".$this->r('uid')."'"));
						$addstr = ($element['users']==''?']':$element['users']).$this->r('uid')."[-s-]";
						$atype = array(
							'task'=>'task',
							'project'=>'projet',
							'item'=>'task item'
						);
						$utype = array(
							'task'=>'tasks.html',
							'project'=>'projects.html',
							'item'=>'tasks/'.$element['task'].'.html'
						);
						
						$text = "You has been attached to ".$atype[$element['type']].' #'.$element['id']."\n Go to ".$atype[$element['type']]." http://web-help-me.com/".$utype[$element['type']]."";
						mail($user['email'],'WHM - You has been attached to '.$atype[$element['type']],$text,"From: no-reply@web-help-me.com\r\n");
						$this->q("UPDATE jos_whm SET users='".$addstr."' WHERE id='".$this->r('eid')."'");
						$_REQUEST['act'] = 'userto';
						$_REQUEST['act2'] = '';
						$html = $this->add();
						break;
					}
					case'delete':{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$this->q("UPDATE jos_whm SET users='".str_replace($this->r('uid').'[-s-]','',$element['users'])."' WHERE id='".$this->r('eid')."'");
						$_REQUEST['act'] = 'userto';
						$_REQUEST['act2'] = '';
						$html = $this->add();
						break;
					}
					default:{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$atype = array(
							'task'=>'task',
							'project'=>'project',
							'item'=>'task item'
						);
						$users = $this->q("SELECT * FROM jos_whm AS u WHERE u.type='user' AND u.id<>'".$element['user']."' ORDER BY u.login");
						$html = '
							<h2>Add user to '.$atype[$element['type']].'</h2>
							<form action="javascript:void(0)" onsubmit="">
								<input type="hidden" name="act" value="userto" />
								<table border="0" cellspacing="0" cellpadding="10" id="messwindowcontent">
									<tr>
										<td>User</td>
										<td><select name="user" class="userid">';
						while($u = mysql_fetch_assoc($users)){
							if($u['id']!=$element['users']){
								$html .= '<option value="'.$u['id'].'">'.$u['login'].'</option>';
							}
						}
						$html .= '</select>
									<input type="button" name="button" value="Add" onclick="Page(\'add&act=userto&act2=add&eid='.$element['id'].'&uid=\'+jQuery(this).parent().find(\'.userid\').val()+\'&num='.$this->r('num').'\',e(\'messwindowin_2'.$this->r('num').'\'),\'\')" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
								</td>
							</tr>
							</table>
							<hr />
							<h3>Added users <span class="icon-q" title="Click on user name to delete him" onmousehover="QTip(this,\'hover\')">?</span></h3>
							<div id="addedusers">';
						
						if($element['users']>0){
							$user = mysql_fetch_assoc($this->q("SELECT login,id FROM jos_whm WHERE id='".$element['users']."'"));
							$html .= '<ul class="addedusers">';
							$html .= '<li onclick="if(confirm(\'Are You sure You want to delete this user?\')){Page(\'add&act=userto&act2=delete&eid='.$element['id'].'&uid='.$user['id'].'&num='.$this->r('num').'\',e(\'messwindowin_2'.$this->r('num').'\',\'\');}">'.$user['login'].'</li>';
							$html .= '</ul>';
						}
						else{
							$html .= '<p>- No users -</p>';
						}
						$html .= '</div>
								<table border="0" cellspacing="0" cellpadding="10" width="100%">
									<tr>
										<td align="center">
											<input type="button" name="button" value="Close" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
										</td>
									</tr>
								</table>
							</form>';
						break;
					}
				}
				break;
			}
			case'settings':{
				switch($act2){
					case'save':{
						$html = '';
						$a = array();
						foreach($_REQUEST as $k=>$v){
							if($k!='type'&&$k!='act'&&$k!='act2'&&$k!='num'&&$k!='contacts'&&$k!='language'){
								$a[] = $k.':'.$v;
								$this->$k = $v;
							}
						}
						$a = implode('|',$a);
						$this->q("UPDATE jos_whm SET settings='".$a."',contacts='".$this->r('contacts')."',language='".$this->r('language')."',val='".$this->r('pval')."' WHERE id='".$this->uid."'");
						$user = $this->q("SELECT * FROM jos_whm WHERE id='".$this->uid."'");
						$u = mysql_fetch_assoc($user);
						$this->q("UPDATE jos_whm SET date2='".date('Y-m-d H:i:s')."' WHERE id='".$u['id']."'");
						$params = array(
								'uid'=>$u['id'],
								'ulogin'=>$u['login'],
								'uem'=>$u['email'],
								'prjct1'=>'all',
								'udatelast'=>$u['date2'],
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
								'uval'=>$u['val'],
								'uuser'=>$u['user'],
								'uusers'=>$u['users'],
								'worker'=>$u['worker'],
								'language'=>$u['language'],
								'version'=>$this->version
							);
						$s = explode('|',$a);
						$sets = array();
						$sets['dpclosed'] = 0;
						$sets['dparchive'] = 0;
						$sets['display'] = 0;
						if(count($s)>1){
							foreach($s as $s2){
								$s3 = explode(':',$s2);
								$sets[$s3[0]] = $s3[1];
							}
						}
						
						foreach($sets as $k=>$v){
							$params[$k] = $v;
						}
						$this->q("UPDATE jos_whm_cache SET params='".(str_replace('=','[=]',str_replace('&','[-s-]',http_build_query($params))))."' WHERE id='".$this->ses('sesid')."'");
						$html .= $this->info('info','<strong>Saved</strong> Settings saved');
						// $html .= '<input type="button" value="Edit" onclick="Page(\'edit&act=settings&num='.$this->r('num').'\',e(\'messwindowin_2'.$this->r('num').'\'))" />';
						// $html .= '<input type="button" value="Close" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))" />';
						break;
					}
					default:{
						$settings = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE id='".$this->uid."'"));
						$s = explode('|',$settings['settings']);
						$sets = array();
						$sets['dpclosed'] = 0;
						$sets['dparchive'] = 0;
						$sets['display'] = 0;
						if(count($s)>1){
							foreach($s as $s2){
								$s3 = explode(':',$s2);
								$sets[$s3[0]] = $s3[1];
							}
						}
						$html = '
							<h1>'.$this->lng['Settings'].'</h1>
							<table cellpadding="5" cellspacing="0" border="0" id="formeditsettings'.$settings['id'].'" width="100%">
								<tr>
									<td colspan="2">
										<h3>Edit account settings</h3>
										<input type="hidden" name="type" value="edit" />
										<input type="hidden" name="act" value="settings" />
										<input type="hidden" name="act2" value="save" />
										<input type="hidden" name="num" value="'.$this->r('num').'" />
									</td>
								</tr>
								<tr>
									<td>Width</td>
									<td><select name="display" style="width:60px!important;">
										<option value="800"'.($sets['display']=='800'?' selected="selected"':'').'>800px</option>
										<option value="auto"'.($sets['display']=='auto'?' selected="selected"':'').'>100%</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Display closed projects</td>
									<td><select name="dpclosed" style="width:60px!important;">
										<option value="0"'.($sets['dpclosed']=='0'?' selected="selected"':'').'>No</option>
										<option value="1"'.($sets['dpclosed']=='1'?' selected="selected"':'').'>Yes</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Display archive projects</td>
									<td><select name="dparchive" style="width:60px!important;">
										<option value="0"'.($sets['dparchive']=='0'?' selected="selected"':'').'>No</option>
										<option value="1"'.($sets['dparchive']=='1'?' selected="selected"':'').'>Yes</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>'.$this->lng['Language'].'</td>
									<td><select name="language" style="width:120px!important;">
										<option value="en"'.($settings['language']=='en'?' selected="selected"':'').'>English</option>
										<option value="ru"'.($settings['language']=='ru'?' selected="selected"':'').'>Русский</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Valute</td>
									<td><select name="uval" style="width:60px!important;">';
						foreach($this->val as $k=>$v){
							$html .= '<option value="'.$k.'"'.(isset($sets['uval'])&&$k==$sets['uval']?' selected="selected"':'').'>'.$v.'</option>';
						}
						$html .= '	</select>
									</td>
								</tr>
								<tr>
									<td>Contacts</td>
									<td><textarea name="contacts">'.$settings['contacts'].'</textarea></td>
								</tr>
								<tr>
									<td>
										<span class="btn f-r p-10" onclick="FormDebug(jQuery(\'#formeditsettings'.$settings['id'].'\'),e(\'messages\'));"><span class="iconlblue icon-disk" title="Save"></span></span>
									</td>
									<td>
										<span class="btn f-l p-10" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'));"><span class="iconred icon-close" title="Cancel"></span></span>
									</td>
								</tr>
							</table>';
						break;
					}
				}
				break;
			}
			case'post':{
				switch($this->r('act2')){
					case'add':{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$user = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.id='".$this->r('uid')."'"));
						$addstr = ($element['users']==''?']':$element['users']).$this->r('uid')."[-s-]";
						$atype = array(
							'task'=>'task',
							'project'=>'project',
							'item'=>'task item'
						);
						$utype = array(
							'task'=>'tasks.html',
							'project'=>'projects.html',
							'item'=>'tasks/'.$element['task'].'.html'
						);
						
						$text = "You were attached to the task ".$atype[$element['type']].' #'.$element['id']."\n
							Go to ".$atype[$element['type']]." http://web-help-me.com/".$utype[$element['type']]."
							";
						smail($user['email'],'WHM - You were attached to the task '.$atype[$element['type']],$text,"From: no-reply@web-help-me.com\r\n");
						$this->q("UPDATE jos_whm SET users='".$addstr."' WHERE id='".$this->r('eid')."'");
						$_REQUEST['act'] = 'userto';
						$_REQUEST['act2'] = '';
						$html = $this->add();
						break;
					}
					case'removeuser':{
						$user = mysql_fetch_assoc($this->q("SELECT login,worker FROM jos_whm WHERE id='".$this->r('eid')."'"));
						$post = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm WHERE id='".$user['worker']."'"));
						$this->q("UPDATE jos_whm SET
								date3='".date('Y-m-d H:i:s')."',
								cost='0',
								html='0',
								css='0',
								php='0',
								js='0',
								mysql='0',
								joomla='0',
								worker='0',
								val='0',
								user='0'
								WHERE id='".$this->r('eid')."'");
						$_REQUEST = array();
						$html = $this->info('info','<strong>Deleted!</strong> Employee <b>'.$user['login'].'</b> has been deleted from the position <b>'.$post['name'].'</b>').$this->users();
						break;
					}
					case'delete':{
						$post = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm WHERE id='".$this->r('eid')."'"));
						if(mysql_num_rows($this->q("SELECT name FROM jos_whm WHERE pid='".$this->r('eid')."'"))>0){
							$_REQUEST = array();
							$html = $this->info('alert','<strong>Error!</strong> Can not delete section <b>'.$post['name'].'</b>. First, remove all the departments within this department').$this->users();
						}
						elseif(mysql_num_rows($this->q("SELECT name FROM jos_whm WHERE worker='".$this->r('eid')."'"))>0){
							$_REQUEST = array();
							$html = $this->info('info','<strong>Error!</strong> Can not delete section <b>'.$post['name'].'</b>. First, remove all the departments within this department.').$this->users();
						}
						else{
							$this->q("DELETE FROM jos_whm WHERE id='".$this->r('eid')."'");
							$_REQUEST = array();
							$html = $this->info('info','<strong>Deleted!</strong> Section <b>'.$post['name'].'</b> is deleted.').$this->users();
						}
						break;
					}
					case'save':{
						$post = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm WHERE id='".$user['worker']."'"));
						$this->q("UPDATE jos_whm SET name='".$this->r('name')."',pid='".$this->r('pid')."' WHERE id='".$this->r('eid')."'");
						$_REQUEST = array();
						$html = '<div class="ui-state-highlight ui-corner-all"> 
								<p><span class="ui-icon ui-icon-info"></span>
								<strong>Saved!</strong> Должность <b>'.$post['name'].'</b> сохранена.</p>
							</div>'.$this->users();
						break;
					}
					default:{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$html = '
							<h2>Edit positions group <b>'.$element['name'].'</b></h2>
							<form id="formedit" action="javascript:void(0)" onsubmit="FormDebug(this);Remove(e(\'messwindowin'.$this->r('num').'\'));">
								<input type="hidden" name="type" value="edit" />
								<input type="hidden" name="act" value="post" />
								<input type="hidden" name="act2" value="save" />
								<input type="hidden" name="eid" value="'.$element['id'].'" />
								<table border="0" cellspacing="0" cellpadding="10">
						';
						$html .= '
							<tr>
								<td>Name</td>
								<td><input type="text" name="name" value="'.$element['name'].'" /></td>
							</tr>
							<tr>
								<td>Position</td>
								<td><select name="pid">
									<option value="0">- Zero level -</option>
									'.$this->posts('option').'</select></td>
							</tr>
							</table>
							</form>
							<div style="width:100px;margin:0 auto;">
								<span id="formaddsubmit" class="btn f-l p-10 m-5" onclick="jQuery(\'#formedit\').submit()"><span class="iconlblue icon-disk" title="Ок"></span></span>
								<span class="btn f-l p-10 m-5" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconred icon-closethick" title="Cancel"></span></span>
							</div>
							';
						break;
					}
				}
				break;
			}
			case'user':{
				switch($this->r('act2')){
					case'add':{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$user = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.id='".$this->r('uid')."'"));
						$addstr = ($element['users']==''?']':$element['users']).$this->r('uid')."[-s-]";
						$atype = array(
							'task'=>'task',
							'project'=>'project',
							'item'=>'task item'
						);
						$utype = array(
							'task'=>'tasks.html',
							'project'=>'projects.html',
							'item'=>'tasks/'.$element['task'].'.html'
						);
						
						$text = "Вас прикрепили к ".$atype[$element['type']].' #'.$element['id']."\n Go to ".$atype[$element['type']]." http://web-help-me.com/".$utype[$element['type']]."";
						mail($user['email'],'WHM - Вас прикрепили к '.$atype[$element['type']],$text,"From: no-reply@web-help-me.com\r\n");
						$this->q("UPDATE jos_whm SET users='".$addstr."' WHERE id='".$this->r('eid')."'");
						$_REQUEST['act'] = 'userto';
						$_REQUEST['act2'] = '';
						$html = $this->add();
						break;
					}
					case'delete':{
						$user = mysql_fetch_assoc($this->q("SELECT login,worker FROM jos_whm WHERE id='".$this->r('eid')."'"));
						$this->q("DELETE FROM jos_whm WHERE id='".$this->r('eid')."'");
						$_REQUEST = array();
						$html = $this->info('info','<strong>Deleted!</strong> User <b>'.$user['login'].'</b> has been deleted.').$this->users();
						break;
					}
					case'block':{
						$user = mysql_fetch_assoc($this->q("SELECT login,worker FROM jos_whm WHERE id='".$this->r('eid')."'"));
						$this->q("UPDATE jos_whm SET status='".$this->r('block')."' WHERE id='".$this->r('eid')."'");
						$_REQUEST = array();
						$html = $this->info('info','<strong>Block!</strong> User <b>'.$user['login'].'</b> has been '.($this->r('block')=='0'?'unblocked':'blocked').'.').$this->users();
						break;
					}
					default:{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$atype = array(
							'task'=>'task',
							'project'=>'project',
							'item'=>'task item'
						);
						if($element['type']=='task'){
							$ue = array();
							$allusers = '';
							$itemsusers = $this->q("SELECT users FROM jos_whm AS i WHERE i.task='".$element['id']."'");
							while($iu = mysql_fetch_assoc($itemsusers)){
								$allusers .= substr($iu['users'],1);
							}
							$allusers2 = array();
							foreach(explode('[-s-]',$allusers) as $au){
								if($au!=''&&!in_array($au,$allusers2)){
									$allusers2[] = $au;
								}
							}
						}
						else{
							$ue = explode('[-s-]',substr($element['users'],1));
						}
						
						$users = $this->q("SELECT * FROM jos_whm AS u WHERE u.type='user' AND u.id!='".$element['user']."' ORDER BY u.login");
						
						$html = '
							<h2>Add user to '.$atype[$element['type']].'</h2>
							<form action="javascript:void(0)" onsubmit="">
								<input type="hidden" name="act" value="userto" />
								<table border="0" cellspacing="0" cellpadding="10" id="messwindowcontent">
						';
						$html .= '
							<tr>
								<td>User</td>
								<td><select name="user" class="userid">';
						while($u = mysql_fetch_assoc($users)){
							if(!in_array($u['id'],$ue)){
								$html .= '<option value="'.$u['id'].'">'.$u['login'].'</option>';
							}
						}
						$html .= '</select>
									<input type="button" name="button" value="Add" onclick="Page(\'add&act=userto&act2=add&eid='.$element['id'].'&uid=\'+jQuery(this).parent().find(\'.userid\').val()+\'&num='.$this->r('num').'\',e(\'messwindowin_2'.$this->r('num').'\'),\'\')" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
								</td>
							</tr>
							</table>
							<hr />
							<h3>Added users <span class="icon-q" title="Click on user name to delete him" onmousehover="QTip(this,\'hover\')">?</span></h3>
							<div id="addedusers">';
						
						$ausers = explode('[-s-]',substr($element['users'],1));
						if(count($ausers)>1){
							$s = 'id='.substr(str_replace('','',implode(' OR id=',$ausers)),0,-7).'';
							$html .= '<ul class="addedusers">';
							$user = $this->q("SELECT login,id FROM jos_whm WHERE ".$s." ORDER BY login");
							while($u = mysql_fetch_assoc($user)){
								$html .= '<li onclick="if(confirm(\'Are You sure You want to delete this user?\')){Page(\'add&act=userto&act2=delete&eid='.$element['id'].'&uid='.$u['id'].'&num='.$this->r('num').'\',e(\'messwindowin_2'.$this->r('num').'\'),\'\');}">'.$u['login'].'</li>';
							}
							$html .= '</ul>';
						}
						else{
							$html .= '<p>- No users -</p>';
						}
						if($element['type']=='task'){
							$html .= '<h4 class="cl-b">Task items users</h4>';
							$ue = array();
							$allusers = '';
							$itemsusers = $this->q("SELECT * FROM jos_whm AS i WHERE i.task='".$element['id']."'");
							while($iu = mysql_fetch_assoc($itemsusers)){
								$html .= '<dl><dt>'.$iu['id'].'</dt><dd>';
								if(count(explode('[-s-]',substr($iu['users'],1)))>1){
									$ausers = explode('[-s-]',substr($iu['users'],1));
									$s = 'id='.substr(str_replace('','',implode(' OR id=',$ausers)),0,-7).'';
									$html .= '<ul class="addedusers">';
									$user = $this->q("SELECT login,id FROM jos_whm WHERE ".$s." ORDER BY login");
									while($u = mysql_fetch_assoc($user)){
										$html .= '<li onclick="if(confirm(\'Are You sure You want to delete this user?\')){Page(\'add&act=userto&act2=delete&eid='.$iu['id'].'&uid='.$u['id'].'&num='.$this->r('num').'\',e(\'messwindowin_2'.$this->r('num').'\'),\'\');}">'.$u['login'].'</li>';
									}
									$html .= '</ul>';
								}
								else{
									$html .= '<p>- No users -</p>';
								}
								$html .= '</dd></dl>';
							}
							foreach(explode('[-s-]',$allusers) as $au){
								if($au!=''&&!in_array($au,$allusers2)){
									$allusers2[] = $au;
								}
							}
						}
						$html .= '</div>
								<table border="0" cellspacing="0" cellpadding="10" width="100%">
									<tr>
										<td align="center">
											<input type="button" name="button" value="Close" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
										</td>
									</tr>
								</table>
							</form>';
						break;
					}
				}
				echo $html;
			}
			case'position':{
				switch($this->r('act2')){
					case'add':{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$user = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.id='".$this->r('uid')."'"));
						$addstr = ($element['users']==''?']':$element['users']).$this->r('uid')."[-s-]";
						$atype = array(
							'task'=>'task',
							'project'=>'project',
							'item'=>'task item'
						);
						$utype = array(
							'task'=>'tasks.html',
							'project'=>'projects.html',
							'item'=>'tasks/'.$element['task'].'.html'
						);
						
						$text = "You have been stucked to ".$atype[$element['type']].' #'.$element['id']."\n Go to ".$atype[$element['type']]." http://web-help-me.com/".$utype[$element['type']]."";
						mail($user['email'],'WHM - You have been stucked to '.$atype[$element['type']],$text,"From: no-reply@web-help-me.com\r\n");
						$this->q("UPDATE jos_whm SET users='".$addstr."' WHERE id='".$this->r('eid')."'");
						$_REQUEST['act'] = 'userto';
						$_REQUEST['act2'] = '';
						$html = $this->add();
						break;
					}
					case'delete':{
						$p = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm WHERE id='".$this->r('pid')."'"));
						$this->q("DELETE FROM jos_whm WHERE id='".$this->r('pid')."'");
						$_REQUEST = array();
						$html = $this->info('info','<strong>Deleted!</strong> Position <b>'.$p['name'].'</b> has been deleted.');
						break;
					}
					case'block':{
						$user = mysql_fetch_assoc($this->q("SELECT login,worker FROM jos_whm WHERE id='".$this->r('eid')."'"));
						$this->q("UPDATE jos_whm SET status='".$this->r('block')."' WHERE id='".$this->r('eid')."'");
						$_REQUEST = array();
						$html = $this->info('info','<strong>Block!</strong> User <b>'.$user['login'].'</b> has been '.($this->r('block')=='0'?'unblocked':'blocked').'.').$this->users();
						break;
					}
					case'save':{
						$this->q("UPDATE jos_whm SET pid='".$this->r('position')."',name='".$this->r('name')."' WHERE id='".$this->r('pid')."'");
						$qu = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS p WHERE p.type='post' AND p.id=".$this->r('pid')));
						$html = $this->info('info','<strong>Updated!</strong>');
						break;
					}
					default:{
						$p = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS p WHERE p.id='".$this->r('pid')."'"));
						$html .= '
								<td><select name="position" style="width:150px;">';
						$html .= $this->posts('option',$p['pid']);
						$html .= '</select>
							<td><input type="text" value="'.$p['name'].'" class="ta-r" style="width:50px;" name="name" /></td>
							<td>
								<input type="button" name="button" value="Save" onclick="Page(\'edit&act=position&act2=save&pid='.$p['id'].'&i='.$this->r('i').'&position=\'+jQuery(\'#position'.$p['id'].' select[name=position]\').val()+\'&name=\'+jQuery(\'#position'.$p['id'].' input[name=name]\').val()+\'\',e(\'position'.$p['id'].'\'))" class="bc-green c-fff" />
							</td>
							<td>
								<input type="button" name="button" value="Cancel" onclick="e(\'position'.$p['id'].'\').innerHTML=\'\';" class="bc-red c-fff" />
							</td>';
						break;
					}
				}
				break;
			}
			case'positionuser':{
				$usr = new usr();
				switch($this->r('act2')){
					case'add':{
						$element = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS e WHERE e.id='".$this->r('eid')."'"));
						$user = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.id='".$this->r('uid')."'"));
						$addstr = ($element['users']==''?']':$element['users']).$this->r('uid')."[-s-]";
						$atype = array(
							'task'=>'task',
							'project'=>'project',
							'item'=>'task item'
						);
						$utype = array(
							'task'=>'tasks.html',
							'project'=>'projects.html',
							'item'=>'tasks/'.$element['task'].'.html'
						);
						
						$text = "You have been stucked to ".$atype[$element['type']].' #'.$element['id']."\n Go to ".$atype[$element['type']]." http://web-help-me.com/".$utype[$element['type']]."";
						mail($user['email'],'WHM - You have been stucked to '.$atype[$element['type']],$text,"From: no-reply@web-help-me.com\r\n");
						$this->q("UPDATE jos_whm SET users='".$addstr."' WHERE id='".$this->r('eid')."'");
						$_REQUEST['act'] = 'userto';
						$_REQUEST['act2'] = '';
						$html = $this->add();
						break;
					}
					case'delete':{
						$user = mysql_fetch_assoc($this->q("SELECT login,worker FROM jos_whm WHERE id='".$this->r('uid')."'"));
						$this->q("UPDATE jos_whm SET worker='0',val='0',cost='0',user='0',users='0' WHERE id='".$this->r('uid')."'");
						$_REQUEST = array();
						$html = $this->info('info','<strong>Deleted!</strong> User <b>'.$user['login'].'</b> has been removed from company.');
						break;
					}
					case'block':{
						$user = mysql_fetch_assoc($this->q("SELECT login,worker FROM jos_whm WHERE id='".$this->r('eid')."'"));
						$this->q("UPDATE jos_whm SET status='".$this->r('block')."' WHERE id='".$this->r('eid')."'");
						$_REQUEST = array();
						$html = $this->info('info','<strong>Block!</strong> User <b>'.$user['login'].'</b> has been '.($this->r('block')=='0'?'unblocked':'blocked').'.').$usr->users();
						break;
					}
					case'save':{
						$this->q("UPDATE jos_whm SET worker='".$this->r('position')."',val='".$this->r('valute')."',cost='".$this->r('cost')."' WHERE id='".$this->r('uid')."'");
						$qu = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.type='user' AND u.id=".$this->r('uid')));
						
						$html = $this->info('info','<strong>Updated!</strong>').$usr->positionuser($qu);
						break;
					}
					default:{
						$u = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS u WHERE u.id='".$this->r('uid')."'"));
						$html .= '
								<td><select name="position" style="width:150px;">';
						$html .= $usr->posts('option',$u['worker']);
						$html .= '</select>
							<td><input type="text" value="'.$u['cost'].'" class="ta-r" style="width:50px;" name="cost" /></td>
							<td><select name="valute">';
						foreach($this->val as $k=>$v){
							$html .= '<option value="'.$k.'"'.($u['val']==$k?' selected="selected"':'').'>'.$v.'</option>';
						}
						$html .= '</select></td>
							<td>
								<input type="button" name="button" value="Save" onclick="Page(\'edit&act=positionuser&act2=save&uid='.$u['id'].'&i='.$this->r('i').'&position=\'+jQuery(\'#positionuser'.$u['id'].' select[name=position]\').val()+\'&cost=\'+jQuery(\'#positionuser'.$u['id'].' input[name=cost]\').val()+\'&valute=\'+jQuery(\'#positionuser'.$u['id'].' select[name=valute]\').val()+\'\',e(\'positionuser'.$u['id'].'\'))" class="bc-green c-fff" />
							</td>
							<td>
								<input type="button" name="button" value="Cancel" onclick="Page(\'positionuser&uid='.$u['id'].'&i='.$this->r('i').'\',e(\'positionuser'.$u['id'].'\'))" class="bc-red c-fff" />
							</td>';
						break;
					}
				}
				echo $html;
			}
			default:{
				$html = '';
				break;
			}
		}
		return $html;
	}
	function delete(){
		$html = '';
		switch($this->r('act')){
			case'finance':{
				$objects = explode('[-s-]',$this->r('idin'));
				foreach($objects as $ob){
					if($ob!=''){
						$this->q("DELETE FROM finance WHERE id='".$ob."'");
					}
				}
				$_REQUEST['idin'] = '';
				$html = $this->info('info','<strong>Deleted!</strong> Deleted <b>'.(count($objects)-1).'</b> finantial transaction'.((count($objects)-1)==1?'':'s').'').$this->finance();
				break;
			}
			case'project':{
				$objects = explode('[-s-]',$this->r('idin'));
				foreach($objects as $ob){
					if($ob!=''){
						$this->q("DELETE FROM projects WHERE id='".$ob."'");
					}
				}
				$_REQUEST['idin'] = '';
				$html = $this->info('info','<strong>Deleted!</strong> Deleted <b>'.(count($objects)-1).'</b> project'.((count($objects)-1)==1?'':'s').'').$this->projects();
				break;
			}
			case'task':{
				$objects = explode('[-s-]',$this->r('idin'));
				foreach($objects as $ob){
					if($ob!=''){
						$this->q("DELETE FROM tasks WHERE id='".$ob."'");
					}
				}
				$_REQUEST['idin'] = '';
				$html = $this->info('info','<strong>Deleted!</strong> Deleted <b>'.(count($objects)-1).'</b> task'.((count($objects)-1)==1?'':'s').'').$this->tasks();
				break;
			}
			case'item':{
				$objects = explode('[-s-]',$this->r('idin'));
				foreach($objects as $ob){
					if($ob!=''){
						$this->q("DELETE FROM items WHERE id='".$ob."'");
					}
				}
				$_REQUEST['idin'] = '';
				$html = $this->info('info','<strong>Deleted!</strong> Deleted '.(count($objects)-1).'</b> item'.((count($objects)-1)==1?'':'s').' from task <b>#'.$this->r('ntask').'</b>').$this->items($this->r('ntask'));
				break;
			}
			case'client':{
				$objects = explode('[-s-]',$this->r('idin'));
				foreach($objects as $ob){
					if($ob!=''){
						$this->q("DELETE FROM users WHERE id='".$ob."'");
					}
				}
				$_REQUEST['idin'] = '';
				$html = $this->info('info','<strong>Deleted!</strong> Deleted '.(count($objects)-1).'</b> client'.((count($objects)-1)==1?'':'s').'</b>').$this->clients();
				break;
			}
		}
		return $html;
	}
}
?>