<?php
require_once('base.php');

class fin extends base{
	function __construct(){
		parent::__construct();
	}
	function finance($act='',$date1='',$date2='',$cstatus=0){
		$html = '';
		$project = $this->r('project');
		$user = $this->r('user');
		// $idin2 = $this->r('idin2');
		$act = $act==''?($project>0?'projects':$this->r('act')):$act;
		$fdate1 = $date1==''?$this->r('date1'):$date1;
		$fdate2 = $date2==''?$this->r('date2'):$date2;
		$cstatus = $this->r('cstatus')>0?$this->r('cstatus'):$cstatus;
		// print_r($_REQUEST);
		
		switch($act){
			case'valute':{
				$_REQUEST['act']='';
				$v = mysql_fetch_assoc($this->q("SELECT contacts,name FROM jos_whm AS v WHERE v.id='".$this->r('val')."'"));
				$html = '<div class="ui-state-highlight ui-corner-all"> 
						<p><span class="ui-icon ui-icon-info"></span>
						<strong>Valute changed!</strong> to '.$v['name'].'</p>
					</div>'.$this->finance();
				break;
			}
			case'range':{
				$_REQUEST['act']='';
				$this->setses('fdate1',$this->r('fdate1'));
				$this->setses('fdate2',$this->r('fdate2'));
				$html = $this->info('info','<strong>Date range is changed!</strong> Now: '.$this->ses('fdate1').' - '.$this->ses('fdate2').'').$this->finance();
				break;
			}
			case'quantity':{
				$_REQUEST['act']='';
				$this->setses('fquantity',$this->r('val'));
				$html = $this->info('info','<strong>Changed display quantity!</strong> '.$this->r('val').'.').$this->finance();
				break;
			}
			case'order':{
				switch($this->r('act2')){
					case'add':{
						$html = '';
						$sum = $this->r('sum');
						$val = $this->r('val');
						$this->q("INSERT INTO jos_whm (type,name,date1,cost,val,user,contacts,span,level,project,users) VALUES ('order','".$this->r('reqs')."','".date('Y-m-d H:i:s')."','".$this->r('sum')."','".$this->r('val')."','".$this->uid."','".$this->r('payer')."','".$this->r('kol')."','".$this->r('ed')."','".$this->r('project')."','".$this->r('name')."')");
						$_REQUEST = array();
						// $v = array('768'=>'$','769'=>'€','770'=>'р.','771'=>'грн.');
						$html = '<div class="ui-state-highlight ui-corner-all"> 
							<p><span class="ui-icon ui-icon-info"></span>
							<strong>Invoice done!</strong> Amount '.$sum.' '.$this->val[$val].'</p>
							</div>'.$this->finance();
						break;
					}
					default:{
						$html = '<h2>Add invoice</h2>';
						$_REQUEST['act']='';
						$settings = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm AS s WHERE s.type='user' AND s.id='".$this->uid."'"));
						// $valute = $this->q("SELECT * FROM jos_whm AS v WHERE type='valute' ORDER BY v.id ASC");
						$sets = explode('[-s-]',$settings['contacts']);//echo '<pre>';print_r($sets);echo '</pre>';
						$html .= '<table cellspacing="0" cellpadding="5" border="0" class="reqs">';
						// $req = array();
						foreach($sets as $s){
							// echo '<pre>';print_r($s);echo '</pre>';
							$s1 = explode('[=]',$s);
							if($s1[0]=='req'){
								$req = $s1[1];
							}
						}
						$html .= '<tr><td>Recipient</td><td><textarea name="reqs">'.$req.'</textarea></td></tr>';
						$html .= '<tr><td>Payer</td><td><textarea name="payer"></textarea></td></tr>';
						$html .= '<tr><td>Description</td><td><input type="text" name="name" /></td></tr>';
						$html .= '<tr><td>Quantity</td><td><input type="text" name="kol" size="3" /><select name="ed" style="width:70px!important;">
								<option value="pcs">pcs</option>
								<option value="kg">kg</option>
								<option value="tonnes">tonnes</option>
								<option value="meters">meters</option>
								<option value="packages">packages</option>
								<option value="liters">liters</option>
							</select></td></tr>';
						$projects = $this->q("SELECT * FROM jos_whm AS p WHERE p.type='project' AND p.status!='4' AND (p.user='".$this->uid."' OR p.users LIKE '%]".$this->uid."[-s-]%') ORDER BY p.name ASC");
						$html .= '<tr><td>Project</td><td><select name="project"><option value="0">- No project -</option>';
						while($p = mysql_fetch_assoc($projects)){
							$html .= '<option value="'.$p['id'].'">'.$p['name'].'</option>';
						}
						$html .= '</select>
							<input type="hidden" name="act" value="order" />
							<input type="hidden" name="act2" value="add" />
							<input type="hidden" name="type" value="finance" />
							</td></tr>';
						$html .= '<tr><td>Amount</td><td><input type="text" name="sum" size="3" /><select name="val" style="width:70px!important;">';
						$i = 0;
						foreach($this->val as $k=>$v){
							$html .= '<option value="'.$k.'"'.($i==3?' selected="selected"':'').'>'.$v.'</option>';
							$i++;
						}
						$html .= '
							</select></td></tr>
							</table>
							<p align="center">
								<input type="button" name="button" value="Get invoice" onclick="FormDebug(jQuery(\'.reqs\'));Remove(e(\'messwindow'.$this->r('num').'\'))" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
								<input type="button" name="button" value="Close" onclick="Remove(e(\'messwindow'.$this->r('num').'\'))" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
							</p>';
						break;
					}
				}
				break;
			}
			case'save':{
				$s = explode('[-|-]',$this->r('str'));
				$project = mysql_fetch_assoc($this->q("SELECT * FROM projects WHERE id=".$s[5].""));
				$task = mysql_fetch_assoc($this->q("SELECT * FROM tasks WHERE id=".$s[5].""));
				//print_r($s);
				$q = "
					UPDATE finance SET
					name='".$s[2]."',
					cost='".$s[0]."',
					vid='".$s[1]."',
					date='".$s[4]."',
					oid='".$s[5]."',
					".($s[7]>60?"
					pid='62',
					cost1='".$s[8]."',
					cost2='".$s[9]."'
					":"
					".($project['id']?'pid':'tid')."='".$s[3]."'
					")."
					WHERE
					id='".$s[6]."'
					";
				//echo $q;
				$this->q($q);
				$_REQUEST['act']='';
				$a = mysql_fetch_assoc($this->q("SELECT f.* FROM finance f WHERE f.id='".$s[6]."'"));
				$html = $this->financeoperationone($a);
				break;
			}
			case'status':{
				$all_59_768 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='768' AND a.span='59'"));
				$all_59_769 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='769' AND a.span='59'"));
				$all_59_770 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='770' AND a.span='59'"));
				$all_59_771 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='771' AND a.span='59'"));
				
				$all_60_768 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.user='".$this->uid."' AND a.val='768' AND ((a.type='finance' AND a.span='60') OR a.type='user')"));
				$all_60_769 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.user='".$this->uid."' AND a.val='769' AND ((a.type='finance' AND a.span='60') OR a.type='user')"));
				$all_60_770 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.user='".$this->uid."' AND a.val='770' AND ((a.type='finance' AND a.span='60') OR a.type='user')"));
				$all_60_771 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.user='".$this->uid."' AND a.val='771' AND ((a.type='finance' AND a.span='60') OR a.type='user')"));
				
				$all_in_768 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='768' AND (a.pid='59' OR a.pid='774' OR a.pid='775') AND a.span='0'"));
				$all_in_769 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='769' AND (a.pid='59' OR a.pid='774' OR a.pid='775') AND a.span='0'"));
				$all_in_770 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='770' AND (a.pid='59' OR a.pid='774' OR a.pid='775') AND a.span='0'"));
				$all_in_771 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='771' AND (a.pid='59' OR a.pid='774' OR a.pid='775') AND a.span='0'"));
				
				$all_out_768 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='768' AND (a.pid='60' OR a.pid='776' OR a.pid='777') AND a.span='0'"));
				$all_out_769 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='769' AND (a.pid='60' OR a.pid='776' OR a.pid='777') AND a.span='0'"));
				$all_out_770 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='770' AND (a.pid='60' OR a.pid='776' OR a.pid='777') AND a.span='0'"));
				$all_out_771 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='771' AND (a.pid='60' OR a.pid='776' OR a.pid='777') AND a.span='0'"));
				
				$zp = mysql_fetch_assoc($this->q("SELECT a.cost AS s,a.val as v,a.user FROM jos_whm AS a WHERE a.id='".$this->uid."'"));
			
				$html = '';
				$html .= '<table cellpadding="0" cellspacing="0" border="0" style="font-size:10px;line-height:normal;"><tr>';
				if($zp['user']>0){$html .= '<th align="right">З/п</th><td width="5">&nbsp;</td><td align="left" width="70">'.($zp['v']==768?$this->val[$zp['v']].' <b class="fs-14 c-green2">'.$zp['s'].'</b>':'<b class="fs-14 c-green2">'.$zp['s'].'</b> '.$this->val[$zp['v']]).'</td>';}
				$html .= '<th align="right">Amount</th><td>
					<table cellpadding="5" cellspacing="0" border="1">
						<tr>
							<td>USD:<span class="c-'.(($all_in_768['s']-$all_out_768['s'])>0?'green':'f00').'">'.($all_in_768['s']-$all_out_768['s']).'</span></td>
							<td>EUR:<span class="c-'.(($all_in_769['s']-$all_out_769['s'])>0?'green':'f00').'">'.($all_in_769['s']-$all_out_769['s']).'</span></td>
							<td>RUR:<span class="c-'.(($all_in_770['s']-$all_out_770['s'])>0?'green':'f00').'">'.($all_in_770['s']-$all_out_770['s']).'</span></td>
							<td>UAH:<span class="c-'.(($all_in_771['s']-$all_out_771['s'])>0?'green':'f00').'">'.($all_in_771['s']-$all_out_771['s']).'</span></td>
						</tr>
					</table></td><td>&nbsp;&nbsp;</td>
					<th align="right">Balance</th><td>
					<table cellpadding="5" cellspacing="0" border="1">
						<tr>
							<td>USD:<span class="c-'.(($all_59_768['s']-$all_60_768['s'])>0?'green':'f00').'">'.($all_59_768['s']-$all_60_768['s']).'</span></td>
							<td>EUR:<span class="c-'.(($all_59_769['s']-$all_60_769['s'])>0?'green':'f00').'">'.($all_59_769['s']-$all_60_769['s']).'</span></td>
							<td>RUR:<span class="c-'.(($all_59_770['s']-$all_60_770['s'])>0?'green':'f00').'">'.($all_59_770['s']-$all_60_770['s']).'</span></td>
							<td>UAH:<span class="c-'.(($all_59_771['s']-$all_60_771['s'])>0?'green':'f00').'">'.($all_59_771['s']-$all_60_771['s']).'</span></td>
						</tr>
					</table>
					</td></tr></table>';
				break;
			}
			case'project':{
				$project = $date1;
				$fq = array();
				$fq['s1'] = 0;
				$fq['s2'] = 0;
				$fq['s3'] = 0;
				$fq['s4'] = 0;
				$f = $this->q("SELECT a.cost AS s,o.id FROM tasks f INNER JOIN finance a ON a.uid='".$this->uid."' AND (a.pid='".$project."' OR a.tid=f.id) LEFT JOIN operations o ON o.id=a.id
						AND a.oid=774 WHERE f.pid='".$project."' GROUP BY a.id");
				while($f1 = mysql_fetch_assoc($f)){$fq['s1'] += $f1['s'];}
				$f = $this->q("SELECT a.cost AS s,o.id FROM tasks f INNER JOIN finance a ON a.uid='".$this->uid."' AND (a.pid='".$project."' OR a.tid=f.id) LEFT JOIN operations o ON o.id=a.id
						AND a.oid=776 WHERE f.pid='".$project."' GROUP BY a.id");
				while($f1 = mysql_fetch_assoc($f)){$fq['s2'] += $f1['s'];}
				$f = $this->q("SELECT a.cost AS s,o.id FROM tasks f INNER JOIN finance a ON a.uid='".$this->uid."' AND (a.pid='".$project."' OR a.tid=f.id) LEFT JOIN operations o ON o.id=a.id
						AND a.oid=59 WHERE f.pid='".$project."' GROUP BY a.id");
				while($f1 = mysql_fetch_assoc($f)){$fq['s3'] += $f1['s'];}
				$f = $this->q("SELECT a.cost AS s,o.id FROM tasks f INNER JOIN finance a ON a.uid='".$this->uid."' AND (a.pid='".$project."' OR a.tid=f.id) LEFT JOIN operations o ON o.id=a.id
						AND a.oid=60 WHERE f.pid='".$project."' GROUP BY a.id");
				while($f1 = mysql_fetch_assoc($f)){$fq['s4'] += $f1['s'];}
				return $fq;
				break;
			}
			case'operations':{
				$html = '<h2>Transactions</h2>';
				$fdate1 = $this->r('date1');
				$fdate2 = $this->r('date2');
				$pid = $this->r('pid')!=''?$this->r('pid'):0;
				$val = $this->r('val')!=''?$this->r('val'):0;
				if($this->r('limit')==''){
					$html .= '';
					$html .= '
						<div class="control">
							<span id="selall" class="btn f-l d-n" onclick="jQuery(\'.financecheck\').attr(\'checked\',true);jQuery(\'#financeoperations,#deselall\').show();jQuery(\'#selall\').hide();"><span class="icongreen icon-check" title="Select all"></span></span></li>
							<span id="deselall" style="display:none;" class="btn f-l" onclick="jQuery(\'.financecheck\').attr(\'checked\',false);jQuery(\'#financeoperations,#deselall\').hide();jQuery(\'#selall\').show();"><span class="iconred icon-cancel" title="Deselect"></span></span></li>
							<span id="financeoperations" style="display:none;">
								
							</span>
							<span class="btn f-l d-n" onclick="Page(\'wcalendar&act=items&idin=\',\'0\',\'\')"><span class="icon3 icon-folder-collapsed" title="Calendar"></span></span>
							<select style="width:70px!important;" onchange="FinanceFilter(\'val\',this.value)">
								<option value="0">All</option>';
					foreach($this->val as $k=>$v){
						$html .= '<option value="'.$k.'">'.$v.'</option>';
					}
					$html .= '</select>
							<select style="width:150px!important;" onchange="FinanceFilter(\'income\',this.value)">
								<option value="0">All</option>
								<option value="inall">All income</option>
								<option value="774">Projects income</option>
								<option value="59">Regular income</option>
								<option value="775">Else income</option>
								<option value="exall">All expense</option>
								<option value="776">Projects expense</option>
								<option value="60">Regular expense</option>
								<option value="61">Employee expense</option>
								<option value="777">Else expense</option>
								<option value="62">Convertation</option>
							</select>
						</div>
						';
				}
				switch($pid){
					default:
					case'0':{$sql = '';break;}
					case'valute':{$sql = " AND vid='".$val."'";break;}
					case'inall':{$sql = " AND (oid='774' OR oid='775' OR oid='59')".($val>0?" AND vid='".$val."'":'');break;}
					case'inprojects':{$sql = " AND (oid='774' OR oid='775') AND (pid>0 OR tid>0)".($val>0?" AND vid='".$val."'":'');break;}
					case'inregular':{$sql = " AND oid='59'".($val>0?" AND vid='".$val."'":'');break;}
					case'inelse':{$sql = " AND (oid='774' OR oid='775') AND pid=0 AND tid=0".($val>0?" AND vid='".$val."'":'');break;}
					case'outall':{$sql = " AND (oid='776' OR oid='777' OR oid='60')".($val>0?" AND vid='".$val."'":'');break;}
					case'outprojects':{$sql = " AND (oid='776' OR oid='777') AND (pid>0 OR tid>0)".($val>0?" AND vid='".$val."'":'');break;}
					case'outregular':{$sql = " AND oid='60'".($val>0?" AND vid='".$val."'":'');break;}
					case'outelse':{$sql = " AND (oid='776' OR oid='777') AND pid=0 AND tid=0".($val>0?" AND vid='".$val."'":'');break;}
					case'outemployees':{$sql = " AND oid='61'".($val>0?" AND vid='".$val."'":'');break;}
				}
				$limit = $this->r('limit')==''?'0,20':$this->r('limit').',20';
				
				
				$all = $this->q("SELECT f.* FROM finance f WHERE f.uid='".$this->uid."' AND DATE(f.date) BETWEEN '".$fdate1."' AND '".$fdate2."'".$sql." ORDER BY f.date DESC LIMIT ".$limit);
				// $html .= '<li>'.mysql_num_rows($all).'</li>';
				$oall = mysql_num_rows($this->q("SELECT f.* FROM finance f WHERE f.uid='".$this->uid."' AND DATE(f.date) BETWEEN '".$fdate1."' AND '".$fdate2."'".$sql." ORDER BY f.date DESC"));
				if($this->r('limit')==''){
					
					$html .= '<p>All: '.$oall.'</p>';
				}
				$html .= '<ul class="finance">';
				if(mysql_num_rows($all)>0){
					$i = 0;
					while($a = mysql_fetch_assoc($all)){
						// echo '<pre>';print_r($a);echo '</pre>';
						switch($a['oid']){
							case'775':
							case'774':
							case'59':{
								$filter = 'inall '.$a['oid'];
								break;
							}
							case'777':
							case'776':
							case'61':
							case'60':{
								$filter = 'exall '.$a['oid'];
								break;
							}
							case'62':{
								$filter = ''.$a['oid'];
								break;
							}
						}
						$html .= '
							<li id="operation'.$a['id'].'" class="bc-'.($i%2==0?'lgrey':'llgrey').' v'.$a['vid'].' '.$filter.'">
								'.$this->financeoperationone($a).'
							</li>';
						$i++;	
					}
				}
				else{
					$html .= '[- No operations -]';
				}
				$html .= '</ul>';
				if($this->r('limit')==''||($oall-((int)$this->r('limit')+20))>0){
					$html .= '<div>
							<input type="button" value="More '.($this->r('limit')==''?'20 ('.($oall-20).')':(($oall-((int)$this->r('limit')+20))<20?($oall-((int)$this->r('limit')+20)):'20').' ('.($oall-((int)$this->r('limit')+20)).')').'" onclick="Page(\'finance&act=operations&date1='.$fdate1.'&date2='.$fdate2.'&limit='.($this->r('limit')==''?'20':(int)$this->r('limit')+20).'\',jQuery(this).parent()[0],\'\')" />
						</div>';
				}
				break;
			}
			case'operationsregular':{
				$html = '<h2>Regular transactions</h2>';
				$html .= '<input type="button" value="Hide" onclick="jQuery(\'#foperations\').html(\'\')" />';
				$html .= '
					<div class="control">
						<span id="selall" class="btn f-l" onclick="jQuery(\'.financecheck\').attr(\'checked\',true);jQuery(\'#financeoperations,#deselall\').show();jQuery(\'#selall\').hide();"><span class="icongreen icon-check" title="Select all"></span></span></li>
						<span id="deselall" style="display:none;" class="btn f-l" onclick="jQuery(\'.financecheck\').attr(\'checked\',false);jQuery(\'#financeoperations,#deselall\').hide();jQuery(\'#selall\').show();"><span class="iconred icon-cancel" title="Deselect"></span></span></li>
						<span id="financeoperations" style="display:none;">
								<span class="btn f-l" onclick="FinanceDelete()"><span class="iconred icon-trash" title="Delete"></span></span>
								<span id="change" class="btn f-l" onclick="FinanceChange(\'change\');jQuery(\'#save\').removeClass(\'d-n\');jQuery(this).addClass(\'d-n\');"><span class="iconlblue icon-pencil" title="Изменить"></span></span>
								<span id="save" class="btn f-l d-n" onclick="FinanceChange(\'save\');jQuery(\'#change\').removeClass(\'d-n\');jQuery(this).addClass(\'d-n\');"><span class="iconlblue icon-disk" title="Сохранить"></span></span>
						</span>
						<span class="btn f-l d-n" onclick="Page(\'wcalendar&act=items&idin=\',\'0\',\'\')"><span class="icon3 icon-folder-collapsed" title="Календарь"></span></span>
					</div>
					';
				$sql = '';
				$html .= '<ul class="finance">';
				$all = $this->q("SELECT f.* FROM jos_whm AS f WHERE f.type='finance' AND (f.user='".$this->uid."' OR f.users LIKE '%]".$this->uid."[-s-]%') AND (f.span=59 OR f.span=60)".$sql." ORDER BY f.name");
				if(mysql_num_rows($all)>0){
					while($a = mysql_fetch_assoc($all)){
						$html .= '
							<li id="operation'.$a['id'].'">
								'.$this->financeoperationone($a).'
							</li>';
					}
				}
				else{
					$html .= '[- No operations -]';
				}
				$html .= '</ul>';
				break;
			}
			case'graph':{
				$html = '';
				$w = 100;
				
				if($this->months($fdate1,$fdate2)>1||date('m',strtotime($fdate2))!=date('m',strtotime($fdate1))){
					$html .= '<h2>Analytics</h2>';
					$asum1 = array();
					$asum0 = array();
					$adate = array();
					$j = 0;
					$m = date('m',strtotime($fdate1));
					$y = date('Y',strtotime($fdate1));
					for($i=0;$i<$this->months($fdate1,$fdate2);$i++){
						$fd1 = date($y.'-'.((strlen($m)==1?'0':'').$m).'-').''.($i==0?date('d',strtotime($fdate1)):'01');
						$fd2 = date($y.'-m-'.($i==($this->months($fdate1,$fdate2)-1)?date('d',strtotime($fdate2)):'t'),strtotime(
								date($y.'-'.((strlen($m)==1?'0':'').$m).'-01')));
						$all1 = $this->q("SELECT a.cost,a.name,a.project,a.val FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND (a.pid='59' OR a.pid='774' OR a.pid='775') AND DATE(a.date1) BETWEEN '".$fd1."' AND '".$fd2."' AND a.span=0");
						$all0 = $this->q("SELECT a.cost,a.name,a.project,a.val FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND (a.pid='60' OR a.pid='776' OR a.pid='777') AND DATE(a.date1) BETWEEN '".$fd1."' AND '".$fd2."' AND a.span=0");
						$sum = array();
						$s = 0;
						while($a = mysql_fetch_assoc($all1)){
							$s += $a['cost']*$this->valcost[$a['val']];
						}
						$sum[1] = $s;
						$s = 0;
						while($a = mysql_fetch_assoc($all0)){
							$s += $a['cost']*$this->valcost[$a['val']];
						}
						$sum[0] = $s;
						$asum1[] = $sum[1];
						$asum0[] = $sum[0];
						if($i==($this->months($fdate1,$fdate2)-1)){
							$adate[] = $fd2;
						}
						else{
							$adate[] = $fd1;
						}
						if($m+1>12){
							$m = 1;
							$y = $y+1;
						}
						else{
							$m = $m + 1;
						}
						$j++;
					}
					$max = max($asum1)>max($asum0)?max($asum1):max($asum0);
					$ah = 150;
					
					$html .= '<div style="height:'.($ah+50).'px;" class="fanalytics">';
					$x = 0;
					$y = 0;
					$step = 700/(count($adate));
					$i = 0;
					foreach($adate as $d){
						$html .= '<div class="date" style="left:'.$y.'px;">'.date('m.y',strtotime($d)).'</div>';
						$x = $asum0[$i]/$max*$ah;
						$html .= '<div class="point out" style="left:'.($y-5).'px;bottom:'.($x-5).'px;"><span>$'.number_format($asum0[$i],0,'.',' ').'</span></div>';
						if($i<(count($adate)-1)){
							$x1 = $asum0[$i+1]/$max*$ah;
							for($j=0;$j<$step;$j++){
								$x2 = ($y+$j-$y)*($x1-$x)/($y+$step-$y)+$x;
								$html .= '<div style="display:block;position:absolute;width:1px;height:1px;background:#f00;left:'.($y+$j).'px;bottom:'.$x2.'px;"></div>';
							}
						}
						$x = $asum1[$i]/$max*$ah;
						$html .= '<div class="point in" style="left:'.($y-5).'px;bottom:'.($x-5).'px;"><span>$'.number_format($asum1[$i],0,'.',' ').'</span></div>';
						if($i<(count($adate)-1)){
							$x1 = $asum1[$i+1]/$max*$ah;
							for($j=0;$j<$step;$j++){
								$x2 = ($y+$j-$y)*($x1-$x)/($y+$step-$y)+$x;
								$html .= '<div style="display:block;position:absolute;width:1px;height:1px;background:#0a0;left:'.($y+$j).'px;bottom:'.$x2.'px;"></div>';
							}
						}
						$y = $y+$step;
						$i++;
					}
					$html .= '</div>';
				}
				$html .= '<h2>'.$this->lng['Graph'].'</h2>';
				// $all1 = $this->q("SELECT a.cost,a.name,a.project,a.val FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND (a.pid='59' OR a.pid='774' OR a.pid='775') AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."' AND a.span=0");
				// $all0 = $this->q("SELECT a.cost,a.name,a.project,a.val FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND (a.pid='60' OR a.pid='776' OR a.pid='777') AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."' AND a.span=0");
				// $sum = array();
				// $s = 0;
				// while($a = mysql_fetch_assoc($all1)){
					// $s += $a['cost']*$this->valcost[$a['val']];
				// }
				// $sum[1] = $s;
				// $s = 0;
				// while($a = mysql_fetch_assoc($all0)){
					// $s += $a['cost']*$this->valcost[$a['val']];
				// }
				// $sum[0] = $s;
				// $j = 0;
				// $w = 500;
				// $h = 20;
				// if($sum[1]>$sum[0]){
					// $w1 = $w;
					// $w0 = $sum[0]*$w/$sum[1];
					// $w2 = $w1-$w0;
					// $s = $sum[1]-$sum[0];
				// }
				// elseif($sum[1]==$sum[0]){
					// $w1 = $w;
					// $w0 = $w;
					// $w2 = $w;
					// $s = $sum[0]-$sum[1];
				// }
				// elseif($sum[1]<$sum[0]){
					// $w1 = $sum[1]*$w/$sum[0];
					// $w0 = $w;
					// $w2 = $w0-$w1;
					// $s = $sum[1]-$sum[0];
				// }
				// $html .= '<ul style="display:block;border-bottom:0px solid #666;">';
				// $html .= '<li class="bc-green" style="left:80px;height:'.$h.'px;width:'.$w1.'px;"><span style="left:-80px;width:80px;display:block;line-height: '.$h.'px;border-bottom: 1px solid #ccc;">$'.number_format($sum[1],0,'.',' ').'</span></li>';
				// $html .= '<li class="bc-red" style="left:80px;height:'.$h.'px;width:'.$w0.'px;"><span style="left:-80px;width:80px;display:block;line-height: '.$h.'px;border-bottom: 1px solid #ccc;">$'.number_format($sum[0],0,'.',' ').'</span></li>';
				// $html .= '<li class="bc-blue" style="left:80px;height:'.$h.'px;width:'.$w2.'px;"><span style="left:-80px;width:80px;display:block;line-height: '.$h.'px;border-bottom: 0px solid #ccc;">$'.number_format($s,0,'.',' ').'</span></li>';
				// $html .= '</ul>';
				
				$all = $this->q("SELECT a.cost,a.name,a.project,a.val,a.pid FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."' AND (a.span=0 OR a.span>60)");
				$data = array();
				while($a = mysql_fetch_assoc($all)){
					// if(!is_array($data[$a['val']])){
						// $data[$a['val']] = array();
					// }
					if($a['pid']==62){
						$data[$a['val']][$a['pid']] = $data[$a['val']][$a['pid']]+$a['cost'];
					}
					else{
						$data[$a['val']][$a['pid']] = $data[$a['val']][$a['pid']]+$a['cost'];
					}
				}
				// echo '<pre>';print_r($data);echo '</pre>';
				foreach($this->val as $k=>$v){
					if(count($data[$k])>0){
						$html .= '<div style="display:block;float:left;padding:20px;">';
						$html .= '<h3>Valute: '.$v.'</h3>';
						$html1 = '<div class="chart">';
						$html2 = '<ul style="display: block;height: 150px;">';
						foreach($data[$k] as $k2=>$v2){
							if($v2>0){
								$html1 .= $v2.',';
								$html2 .= '<li>'.$this->o[$k2].': '.($k==768?$v.''.$v2:$v2.''.$v).'</li>';
							}
						}
						$html1 = substr($html1,0,-1);
						$html2 .= '</ul>';
						$html .= $html2.$html1.'</div>';
						$html .= '</div>';
					}
				}
				
				
				// $all = $this->q("SELECT a.cost,a.name,a.project,a.val,a.pid,a.id FROM jos_whm AS a WHERE a.type='operation' ORDER BY name ASC");
				// $html .= '<div class="chart_datas">';
				
				// while($a = mysql_fetch_assoc($all)){
					// foreach($this->val as $k=>$v){
						// if($data[$k][$a['id']]){
							// $html .= '<input type="hidden" class="chart_data chart_'.$k.' chart_'.$a['id'].'" value="'.$data[$k][$a['id']].'" alt="'.$k.'" />';
						// }
					// }
				// }
				// $html .= '</div>';
				// $html .= '<div id="chart_div" style="width: 500px; height: 300px;"></div>';
				$html .= '
					<br class="cl-b" />
					<script>
					drawChart();
					</script>
					';
				
				
				break;
			}
    		case'projects':{
    			$sql = '';
				$sum = array(0=>0,1=>0);
    			if($project>0){$sql = " AND a.id='".$project."'";}
    			
				if($this->ses('fdate1')!=false){$fdate1 = $this->ses('fdate1');}
    			else{$fdate1 = date('Y-m').'-01';}
				if($this->ses('fdate2')!=false){$fdate2 = $this->ses('fdate2');}
				else{$fdate2 = date('Y-m-d');}
				$all = $this->q("SELECT * FROM jos_whm AS c INNER JOIN jos_whm AS a ON a.client=c.id AND a.type='project' AND a.user='".$this->uid."' AND a.status!=4 AND a.status!=3".$sql." WHERE ".($project>0?'1':'c.status='.$cstatus)." ORDER BY a.name");
				$html .= '<h2>Project'.($project>0?'':'s').'</h2>';
				$html .= '<div class="control">
						<span class="btn f-l c-green" style="width:auto;padding:5px 15px;" onclick="Page(\'finance&act=projects&cstatus=0\',e(\'foperations\'),\'\')">Clients</span>
						<span class="btn f-l c-red" style="width:auto;padding:5px 15px;" onclick="Page(\'finance&act=projects&cstatus=1\',e(\'foperations\'),\'\')">Contractors</span>
					</div>';
				$html .= '<table cellpadding="5" cellspacing="0" border="1" style="margin:0 0 1px;" width="100%" rules="cols">
						<tr style="background:#fff;">
							<th width="10" class="ta-c" rowspan="2">#</th>
							<th class="" rowspan="2">Name of the project</th>
							<th width="80" class="">Once transactions</th>
							<th width="80" class="">Regular transactions</th>
							<th width="80" class="" style="border-left:1px solid #00f;" rowspan="2">Total once</th>
							<th width="80" class="" rowspan="2">Total reg.</th>
						</tr>
						<tr style="background:#fff;">
							<th class="">In/Out</th>
							<th class="">In/Out</th>
						</tr>';
				$i = 1;
				$pval = 768;
				while($a = mysql_fetch_assoc($all)){
					if($a['val']<768||$a['val']==''){$a['val'] = '768';}
					// echo $fdate1.' '.$fdate2;
					$pval = $a['val'];
					$fq = $this->finance('project',$a['id']);
					// $fq['s1'] = $f['s'];
					// echo '<pre>';print_r($fq);echo '</pre>';
					// foreach($this->val as $k=>$v){
						// $fq = mysql_fetch_assoc($this->q("
							// SELECT SUM(a.cost) AS s1,SUM(a.costplanin) AS s2 FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.project='".$a['id']."' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'
									// AND a.val='".$k."' AND a.pid='774'"));
						// $f[$k]['s1'] = $fq['s1'];
						// $f['s3'] += $fq['s2'];
						// $fq = mysql_fetch_assoc($this->q("
							// SELECT SUM(b.cost) AS s3,SUM(b.costplanin) AS s4 FROM jos_whm AS c INNER JOIN jos_whm AS b ON b.type='finance' AND b.project=c.id AND b.user='".$this->uid."' AND DATE(b.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'
									// AND b.val='".$k."' AND b.pid='774' WHERE c.type='task' AND c.project=".$a['id'].""));
						// $f[$k]['s2'] = $fq['s3'];
						// $f['s3'] += $fq['s4'];
						
						// $fq = mysql_fetch_assoc($this->q("
							// SELECT SUM(a.cost) AS s1,SUM(a.costplanin) AS s2 FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.project='".$a['id']."' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'
									// AND a.val='".$k."' AND a.pid='776'"));
						// $f[$k]['s4'] = $fq['s1'];
						// $f['s6'] += $fq['s2'];
						// $fq = mysql_fetch_assoc($this->q("
							// SELECT SUM(b.cost) AS s3,SUM(b.costplanin) AS s4 FROM jos_whm AS c INNER JOIN jos_whm AS b ON b.type='finance' AND b.project=c.id AND b.user='".$this->uid."' AND DATE(b.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'
									// AND b.val='".$k."' AND b.pid='776' WHERE c.type='task' AND c.project=".$a['id'].""));
						// $f[$k]['s5'] = $fq['s3'];
						// $f['s6'] += $fq['s4'];
						
						// $fq = mysql_fetch_assoc($this->q("
							// SELECT SUM(a.cost) AS s1,SUM(a.costplanin) AS s2 FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.project='".$a['id']."' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'
									// AND a.val='".$k."' AND a.pid='59'"));
						// $f[$k]['s7'] = $fq['s1'];
						// $f['s9'] += $fq['s2'];
						// $fq = mysql_fetch_assoc($this->q("
							// SELECT SUM(b.cost) AS s3,SUM(b.costplanin) AS s4 FROM jos_whm AS c INNER JOIN jos_whm AS b ON b.type='finance' AND b.project=c.id AND b.user='".$this->uid."' AND DATE(b.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'
									// AND b.val='".$k."' AND b.pid='59' WHERE c.type='task' AND c.project=".$a['id'].""));
						// $f[$k]['s8'] = $fq['s3'];
						// $f['s9'] += $fq['s4'];
						
						// $fq = mysql_fetch_assoc($this->q("
							// SELECT SUM(a.cost) AS s1,SUM(a.costplanin) AS s2 FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.project='".$a['id']."' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'
									// AND a.val='".$k."' AND a.pid='60'"));
						// $f[$k]['s10'] = $fq['s1'];
						// $f['s12'] += $fq['s2'];
						// $fq = mysql_fetch_assoc($this->q("
							// SELECT SUM(b.cost) AS s3,SUM(b.costplanin) AS s4 FROM jos_whm AS c INNER JOIN jos_whm AS b ON b.type='finance' AND b.project=c.id AND b.user='".$this->uid."' AND DATE(b.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'
									// AND b.val='".$k."' AND b.pid='60' WHERE c.type='task' AND c.project=".$a['id'].""));
						// $f[$k]['s11'] = $fq['s3'];
						// $f['s12'] += $fq['s4'];
					// }
					
					$costplan = $a['costplanin']+$a['cost']-$a['costplanout'];
					$costplanreg = $a['costplaninreg']-$a['costplanoutreg'];
					$html .= '<tr style="background:#'.($i%2==0?'fff':'eee').';">
							<td width="10" class="ta-c va-t">'.$i.'</td>
							<td class="va-t fs-11">
								'.$a['name'].'
								<span style="color:#'.($costplan>0?'4F7942':'f00').';">
									'.number_format($costplan,0,'.',' ').' '.$this->val[$a['val']].'
								</span>
								'.($costplanreg!=0?'
								<span style="color:#'.($costplanreg>0?'4F7942':'f00').';">
									('.number_format($costplanreg,0,'.',' ').' '.$this->val[$a['val']].')
								</span>':'').'
							</td>
							<td class="ta-r va-t">
								<div style="color:#4F7942;">'.number_format($fq['s1'],0,'.',' ').' '.$this->val[$a['val']].'</div>
								<div style="color:#f00;">'.number_format($fq['s2'],0,'.',' ').' '.$this->val[$a['val']].'</div>
							</td>
							<td class="ta-r va-t">
								<div style="color:#4F7942;">'.number_format($fq['s3'],0,'.',' ').' '.$this->val[$a['val']].'</div>
								<div style="color:#f00;">'.number_format($fq['s4'],0,'.',' ').' '.$this->val[$a['val']].'</div>
							</td>
							<td class="ta-r va-t">
								<div style="color:#'.(($fq['s1']-$fq['s2'])>0?'4F7942':'f00').';">'.number_format(($fq['s1']-$fq['s2']),0,'.',' ').' '.$this->val[$a['val']].'</div>
							</td>
							<td class="ta-r va-t">
								<div style="color:#'.(($fq['s3']-$fq['s4'])>0?'4F7942':'f00').';">'.number_format(($fq['s3']-$fq['s4']),0,'.',' ').' '.$this->val[$a['val']].'</div>
							</td>
						</tr>';
					$sum[$a['val']][0] += $fq['s1']-$fq['s2'];
					$sum[$a['val']][1] += $fq['s3']-$fq['s4'];
					$i++;
				}
				$html .= '<tr style="background:#'.($i%2==0?'fff':'eee').';">
							<td colspan="4" class="ta-c va-t">Summary</td>
							<td class="ta-r va-t">';
				foreach($sum as $k=>$v){
					if(abs($v[0])>0){
						if($k==768){
							$html .= '<div style="color:#'.($v[0]>0?'4F7942':'f00').';">'.$this->val[$k].''.number_format($v[0],0,'.',' ').'</div>';
						}
						else{
							$html .= '<div style="color:#'.($v[0]>0?'4F7942':'f00').';">'.number_format($v[0],0,'.',' ').' '.$this->val[$k].'</div>';
						}
					}
				}
				$html .= '</td>
							<td class="ta-r va-t">';
				foreach($sum as $k=>$v){
					if(abs($v[1])>0){
						if($k==768){
							$html .= '<div style="color:#'.($v[1]>0?'4F7942':'f00').';">'.$this->val[$k].''.number_format($v[1],0,'.',' ').'</div>';
						}
						else{
							$html .= '<div style="color:#'.($v[1]>0?'4F7942':'f00').';">'.number_format($v[1],0,'.',' ').' '.$this->val[$k].'</div>';
						}
					}
				}
				$html .= '</td>
						</tr>';
				$html .= '</table>';
				break;	 
			}
			case'users':{
    			$sql = '';
    			if($user>0){$sql = " AND a.id='".$user."'";}
    			if($this->ses('fdate1')!=false){$fdate1 = $this->ses('fdate1');}
    			else{$fdate1 = date('Y-m').'-01';}
				if($this->ses('fdate2')!=false){$fdate2 = $this->ses('fdate2');}
				else{$fdate2 = date('Y-m-d');}
				$all = $this->q("SELECT * FROM jos_whm AS a WHERE a.type='user' AND a.user='".$this->uid."'".$sql." ORDER BY login");
				$html .= '<h2>User'.($user>0?'':'s').'</h2>';
				$html .= '<table cellpadding="5" cellspacing="0" border="1" style="margin:0 0 1px;" width="100%">
						<tr>
							<th width="10" class="ta-c">#</th>
							<th class="">User login</th>
							<th width="80" class="">Salary per month</th>
							<th width="80" class="">Months</th>
							<th width="80" class="">Summary for all months</th>
							<th width="80" class="">Summary for all months in fact</th>
						</tr>';
				$i = 1;
				while($a = mysql_fetch_assoc($all)){
					if($a['val']<768||$a['val']==''){$a['val'] = '768';}
					
					$f_768 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s,costplanin FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='768' AND a.project='".$a['id']."' AND a.pid='61' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'".$sql));
					$f_769 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s,costplanin FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='769' AND a.project='".$a['id']."' AND a.pid='61' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'".$sql));
					$f_770 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s,costplanin FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='770' AND a.project='".$a['id']."' AND a.pid='61' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'".$sql));
					$f_771 = mysql_fetch_assoc($this->q("SELECT SUM(a.cost) AS s,costplanin FROM jos_whm AS a WHERE a.type='finance' AND a.user='".$this->uid."' AND a.val='771' AND a.project='".$a['id']."' AND a.pid='61' AND DATE(a.date1) BETWEEN '".$fdate1."' AND '".$fdate2."'".$sql));
					// $f_768_ = $f_769['costplanin']+$f_770['costplanin']+$f_771['costplanin'];
					$html .= '<tr style="background:'.($i%2==0?'#eee':'#ddd').';">
							<td width="10" class="ta-c va-t">'.$i.'</td>
							<td class="va-t fs-11">'.$a['login'].'</td>
							<td class="ta-r va-t">
								<div style="">'.number_format(($a['costplanin']+$a['cost']),0,'.',' ').' '.$this->val[$a['val']].'</div>
							</td>
							<td class="ta-c va-t">'.$this->months($fdate1,$fdate2).'</td>
							<td class="ta-r va-t">'.number_format(($this->months($fdate1,$fdate2)*($a['costplanin']+$a['cost'])),0,'.',' ').' '.$this->val[$a['val']].'</td>
							<td class="ta-r va-t">
								'.($f_768['s']?'<div style="color:'.($f_768['s']>0?'#4F7942':'#666').';">'.$this->val[768].''.number_format($f_768['s'],0,'.',' ').'</div>':'').'
								'.($f_769['s']?'<div style="color:'.($f_769['s']>0?'#4F7942':'#666').';">'.number_format($f_769['s'],0,'.',' ').' '.$this->val[769].'</div>':'').'
								'.($f_770['s']?'<div style="color:'.($f_770['s']>0?'#4F7942':'#666').';">'.number_format($f_770['s'],0,'.',' ').' '.$this->val[770].'</div>':'').'
								'.($f_771['s']?'<div style="color:'.($f_771['s']>0?'#4F7942':'#666').';">'.number_format($f_771['s'],0,'.',' ').' '.$this->val[771].'</div>':'').'
							</td>
						</tr>';
					$i++;
				}
				$html .= '</table>';
				break;	 
			}
			case'balance':{
				$ahired = array();
				foreach($this->val as $k=>$v){
					$ahired[$k] = 0;
				}
				if($this->userid>0){
					$sql = "SELECT ";
					foreach($this->val as $k=>$v){
						$sql .= "
							(
							IF(SUM(b".$k.".cost),SUM(b".$k.".cost),0)
							) AS sum".$k.",
							";
					}
					$sql .= "a.* ";
					$sql .= "FROM jos_whm a ";
					foreach($this->val as $k=>$v){
						$sql .= "
							LEFT JOIN jos_whm b".$k."
							ON
								b".$k.".id=a.id
								AND b".$k.".pid='61'
								AND b".$k.".val=".$k."
								AND b".$k.".project='".$this->uid."' ";
					}
					$sql .= " ";
					$sql .= "WHERE
							a.type='finance'
							AND a.user='".$this->userid."'";
				
					// $html .= '<pre>'.$sql.'</pre>'; 
					$all = $this->q($sql);
					$a = mysql_fetch_assoc($all);
	#				echo '<pre>';
	#				var_dump($a);
	#				echo '</pre>';
					$ahired = array();
					foreach($this->val as $k=>$v){
						if(abs(round($a['sum'.$k]))>0){
							$ahired[$k] = $ahired[$k]+$a['sum'.$k];
						}
					}
					
				}
				$sql = "SELECT ";
				foreach($this->val as $k=>$v){
					$sql .= "
						(
						IF(SUM(a".$k.".cost),SUM(a".$k.".cost),0)
						-IF(SUM(b".$k.".cost),SUM(b".$k.".cost),0)
						-IF(SUM(c".$k.".costplaninreg),SUM(c".$k.".costplaninreg),0)
						+IF(SUM(d".$k.".costplanoutreg),SUM(d".$k.".costplanoutreg),0)
						) AS sum".$k.",
						";
				}
				$sql .= "(SUM(aall.costplanin)-SUM(ball.costplanin)) AS sumall ";
				$sql .= "FROM jos_whm a ";
				foreach($this->val as $k=>$v){
					$sql .= "LEFT JOIN jos_whm a".$k."
						ON
							a".$k.".id=a.id
							AND (a".$k.".pid='774'
								OR a".$k.".pid='775'
								OR a".$k.".pid='59')
							AND a".$k.".val=".$k."
						LEFT JOIN jos_whm b".$k."
						ON
							b".$k.".id=a.id
							AND ((b".$k.".pid='776'
								OR b".$k.".pid='777'
								OR b".$k.".pid='60'
								OR b".$k.".pid='61')
								OR
								(b".$k.".pid='62'))
							AND b".$k.".val=".$k."
						LEFT JOIN jos_whm c".$k."
						ON
							c".$k.".id=a.id
							AND c".$k.".pid='62'
							AND c".$k.".val=".$k."
						LEFT JOIN jos_whm d".$k."
						ON
							d".$k.".id=a.id
							AND d".$k.".pid='62'
							AND d".$k.".span=".$k." ";
				}
				$sql .= "LEFT JOIN jos_whm aall
						ON
							aall.id=a.id
							AND (aall.pid='774'
								OR aall.pid='775'
								OR aall.pid='59')
						LEFT JOIN jos_whm ball
						ON
							ball.id=a.id
							AND (ball.pid='776'
								OR ball.pid='777'
								OR ball.pid='60'
								OR ball.pid='61') ";
				$sql .= "WHERE
						a.type='finance'
						AND a.user='".$this->uid."'";
				
				// $html .= '<pre>'.$sql.'</pre>'; 
				$all = $this->q($sql);
				$a = mysql_fetch_assoc($all);
#				$a = json_decode('[' . $a . ']', true);
#				$a = array_map('intval',$a);

#				echo '<pre>';
#				var_dump($a);
#				var_dump($ahired);
#				echo '</pre>';
				$ahtml = array();
				foreach($this->val as $k=>$v){
					if(abs(round($a['sum'.$k]))>0){
						if($k==768){
							$ahtml[] = ''.$v.''.number_format((floatval($a['sum'.$k])+$ahired[$k]),0,'',' ').'';
						}
						else{
							$ahtml[] = number_format((floatval($a['sum'.$k])+$ahired[$k]),0,'',' ').' '.$this->val[$k].' ';
						}
					}
				}
				// print_r($ahtml);
				$html .= ''.$this->lng['Balance'].': '.implode('&nbsp;&nbsp;&nbsp;',$ahtml);
				// $html .= '<b>$'.number_format($a['sumall'],0,'',' ').'</b>';
				break;
			}
			default:{
				$sql = '';
				if($this->r('idin')=='project'){$sql = " AND a.project='".$this->r('idin2')."'";}
				if($this->ses('fdate1')!=false){$fdate1 = $this->ses('fdate1');}
				else{$fdate1 = date('Y-m').'-01';}
				if($this->ses('fdate2')!=false){$fdate2 = $this->ses('fdate2');}
				else{$fdate2 = date('Y-m-d');}
				
				$all = $this->q("SELECT * FROM finance a WHERE a.uid='".$this->uid."'".$sql." AND a.date BETWEEN '".$fdate1."' AND '".$fdate2."' ORDER BY date DESC");
				// $html .= $fdate1.' '.$fdate2.' '.mysql_num_rows($all);
				$html .= '<h1>'.$this->lng['Finance'].' &rarr;
						<span class="fs-14 link active" onclick="Page(\'finance&act=graph&date1='.$fdate1.'&date2='.$fdate2.'\',e(\'foperations\'),\'\')">'.$this->lng['Graph'].'</span>		
						<span class="fs-14 link" onclick="Page(\'finance&act=projects\',e(\'foperations\'),\'\')">'.$this->lng['Projects'].'</span>
						<span class="fs-14 link d-n" onclick="Page(\'finance&act=order\',e(\'foperations\'),\'\')">'.$this->lng['Invoice'].'</span>
						<span class="fs-14 link d-n" onclick="Page(\'finance&act=operationsregular\',e(\'foperations\'),\'\')">'.$this->lng['Regular transactions'].'</span>
						<span class="fs-14 link" onclick="Page(\'finance&act=operations&date1='.$fdate1.'&date2='.$fdate2.'\',e(\'foperations\'),\'\')">'.$this->lng['Transactions'].'</span>
						<span class="fs-14 link" onclick="Page(\'finance&act=users&date1='.$fdate1.'&date2='.$fdate2.'\',e(\'foperations\'),\'\')">'.$this->lng['Users'].'</span>
						<span class="fs-14 link d-n" onclick="Page(\'orders\',e(\'foperations\'),\'\')">'.$this->lng['Orders'].'</span>
						</h1>';
				$html .= '';
				$html .= '<div class="control">
						
						<table border="0" cellspacing="0" cellpadding="0" class="f-l">
							<tr>
								<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
								<td><input id="fdate1" type="text" size="15" value="'.$fdate1.'" class="datepicker" /></td>
								<td>&nbsp;-&nbsp;</td>
								<td><input id="fdate2" type="text" size="15" value="'.$fdate2.'" class="datepicker" /></td>
								<td><input class="btn" type="button" value="'.$this->lng['Apply'].'" onclick="Load(e(\'content\'),\'type=finance&act=range&fdate1=\'+e(\'fdate1\').value.replaceAll(\'NaN\',\'0\')+\'&fdate2=\'+e(\'fdate2\').value.replaceAll(\'NaN\',\'0\'))" /></td>
							</tr>
						</table>
						
					</div>
					<div id="foperations">';
				$html .= $this->finance('graph',$fdate1,$fdate2);
				// $html .= $this->finance('operations',$fdate1,$fdate2);
				$projects = $this->q("SELECT p.* FROM projects p WHERE p.sid!=4 AND p.sid!=3 AND p.uid='".$this->uid."' ORDER BY p.name");
				$html .= '<script>var valarray = new Array();';
				
				while($p = mysql_fetch_assoc($projects)){
					$html .= 'valarray['.$p['id'].'] = '.$p['vid'].';';
				}
				$html .= '</script>';
				$sql = '';
				
				$all = $this->q("SELECT f.* FROM finance f WHERE f.uid='".$this->uid."' AND DATE(f.date) BETWEEN '".$fdate1."' AND '".$fdate2."'".$sql." ORDER BY f.date DESC");
				$arr = array();
				$arrsum = array();
				while($a = mysql_fetch_assoc($all)){
					if(!array_key_exists($a['name'],$arr)){
						$arr[] = $a['name'];
						$arr[$a['name']] = array();
					}
					
					if(!array_key_exists($a['vid'],$arr[$a['name']])){
						$arr[$a['name']] = array();
						$arr[$a['name']][$a['vid']] = 0;
					}
					
					if(!array_key_exists($a['val'],$arrsum)){
						$arrsum[$a['val']] = 0;
					}
					
					if($a['oid']==774||$a['oid']==775||$a['oid']==59){
						$arr[$a['name']][$a['vid']] = $arr[$a['name']][$a['vid']]+$a['cost'];
						$arrsum[$a['vid']] = $arrsum[$a['vid']]+$a['cost'];
					}
					else{
						$arr[$a['name']][$a['vid']] = $arr[$a['name']][$a['vid']]-$a['cost'];
						$arrsum[$a['vid']] = $arrsum[$a['vid']]-$a['cost'];
					}
				}
				$html .= '<br />';
				foreach($arrsum as $k=>$v){
					$html .= ($k==768?$this->val[$k].$v:$v.$this->val[$k]).'<br /><br />';
				}
				$html .= '<br />';
				$html .= '<br />';
				$html .= '<table cellpadding="2" cellspacing="0" border="1" rules="rows">';
				foreach($arr as $k=>$v){
					if(is_array($v)){
						// echo '<pre>';print_r($v);echo '</pre>';
						$html .= '<tr><td>'.$k.'</td>';
						foreach($v as $k2=>$v2){
							$html .= '<td align="right" width="100">'.($k2==768?$this->val[$k2].$v2:$v2.''.$this->val[$k2]).'</td>';
						}
						$html .= '</tr>';
					}
				}
				$html .= '</table>';
				// $html .= '<table cellpadding="5" cellspacing="0" border="1" style="margin:0 0 1px;" width="100%">
						// <tr>
							// <th width="10" class="ta-c">#</th>
							// <th class="">Name of the project</th>
							// <th width="80" class="">Off transactions</th>
						// </tr>';
				// $i = 1;
				// while($a = mysql_fetch_assoc($all)){
					// if($a['val']<768||$a['val']==''){$a['val'] = '768';}
					// $html .= '<tr style="background:'.($i%2==0?'#eee':'#ddd').';">
							// <td width="10" class="ta-c va-t">'.$i.'</td>
							// <td class="va-t fs-11">'.$a['date1'].'</td>
							// <td class="va-t fs-11">'.$a['name'].'</td>
						// </tr>';
					// $i++;
				// }
				// $html .= '</table>';
				$html .= '</div>';
				
				$finance = $this->q("SELECT DISTINCT f.name FROM finance f WHERE f.name!='' ORDER BY f.name ASC");
				$words = array();
				while($f = mysql_fetch_assoc($finance)){
					$words[] = "'".$f['name']."'";
				}
				$html .= '
					
					<script>
					var autocomplite = ['.implode(',',$words).'];
					// google.setOnLoadCallback(drawChart);
					
					</script>';
				break;
			}
		}
		return $html;
	}
	function financeoperationone($a){
		
		
		if($a['tid']>0){
			$item = mysql_fetch_assoc($this->q("SELECT name,id,pid FROM tasks WHERE id='".$a['tid']."'"));
			$project = mysql_fetch_assoc($this->q("SELECT name,id FROM jos_whm AS p WHERE p.id='".$item['pid']."'"));
			$name = 'TASK &rarr; <b>'.$project['name'].'</b> &rarr; '.$item['name'].''.($a['name']!=''?':':'').' '.$a['name'];
		}
		elseif($a['pid']>0&&$a['oid']!=61&&$a['oid']!=62){
			$item = mysql_fetch_assoc($this->q("SELECT name,id FROM projects WHERE id='".$a['pid']."'"));
			$name = 'PROJECT &rarr; <b>'.$item['name'].'</b>'.' '.$a['name'];
		}
		elseif($a['oid']==61){
			$item = mysql_fetch_assoc($this->q("SELECT name,id,login FROM users WHERE id='".$a['pid']."'"));
			$name = 'USER &rarr; <b>'.$item['name'].' '.$item['login'].'</b>'.' '.$a['name'];
		}
		elseif($a['oid']==62){
			$item = mysql_fetch_assoc($this->q("SELECT name,id FROM operations WHERE id='".$a['oid']."'"));
			$name = 'OPERATION &rarr; <b>'.$item['name'].'</b>'.' '.$a['name'];
		}
		else{
			$name = $a['name'];
		}
		$projects = $this->q("SELECT p.* FROM projects p WHERE p.sid!=4 AND p.sid!=3 AND p.uid='".$this->uid."' ORDER BY p.name");
		$types = $this->q("SELECT name,id FROM operations WHERE 1 ORDER BY name");
		$users = $this->q("SELECT * FROM users u WHERE u.type=2 AND u.uid='".$this->uid."' ORDER BY u.login ASC");
		$html = '<div id="fo'.$a['id'].'">
				<table cellpadding="3" cellspacing="0" border="0" width="100%">
					<tr class="saved">
						<td width="10" class="d-n"><input class="financecheck eid" type="checkbox" onclick="FinanceCheck();" value="'.$a['id'].'" /></td>
						<td width="60" class="fs-12 ta-c">'.date('d.m.y',strtotime($a['date'])).'</td>
						<td class="fs-12">'.$name.'</td>
						<td width="150" class="cost" align="right">
						<span class="fs-14 c-'.($a['oid']==59||$a['oid']==774||$a['oid']==775?'090':'f00').'">'.($a['vid']==768?$this->val[$a['vid']].' '.number_format($a['cost'],2,'.',' '):number_format($a['cost'],2,'.',' ').' <span class="fs-11" style="width:auto;">'.$this->val[$a['vid']]).'</span></span>';
		$html .= '
						</td>
						<td width="65px">
							<span id="change'.$a['id'].'" class="btn f-l" onclick="FinanceChange(\'change\',\''.$a['id'].'\');"><span class="iconlblue icon-pencil" title="Change"></span></span>
							
							<span class="btn f-l" onclick="FinanceDelete(\''.$a['id'].'\')"><span class="iconred icon-trash" title="Delete"></span></span>
						</td>
					</tr>
					<tr class="edit d-n">
						<td colspan="4">
							<input type="hidden" class="eid" value="'.$a['id'].'" />
							<input type="hidden" class="espan" value="'.$a['span'].'" />
							<input class="ename" type="text" value="'.$a['name'].'" style="width:100px;" />
							<input class="edate datepicker" type="text" value="'.date('Y-m-d',strtotime($a['date'])).'" style="width:65px;" />
							<input class="ecost" type="text" value="'.$a['cost'].'" style="width:60px;text-align:right;" />
							<select class="eval" style="width:60px;">';
		foreach($this->val as $k=>$v){
			$html .= '			<option value="'.$k.'"'.($a['vid']==$k?' selected="selected"':'').'>'.$v.'</option>';
		}
		$html .= '			</select>';
		$html .= '		<select class="etype" style="width:150px;" onchange="FinanceOperation(this.value,this)">';
		while($t = mysql_fetch_assoc($types)){
			$html .= '		<option value="'.$t['id'].'"'.($a['oid']==$t['id']?' selected="selected"':'').'>'.$t['name'].'</option>';
		}
		$html .= '		</select>';
		if($a['oid']>60){
			
			$html .= '		<select class="epro op774776" style="width:150px;'.($a['pid']==776||$a['pid']==774||$a['pid']==59||$a['pid']==60?'':'display:none;').'"><option value="0"'.($a['project']==0?' selected="selected"':'').'>- No project -</option>';
			while($p = mysql_fetch_assoc($projects)){
				// $html .= '<option value="'.$p['id'].'"'.($item['id']==$p['id']?' selected="selected"':'').'>'.$p['name'].'</option>';
				$tasks = $this->q("SELECT t.* FROM tasks t WHERE t.pid='".$p['id']."' AND t.sid!=4 ORDER BY t.name");
				if(mysql_num_rows($tasks)>0){
					$html .= '	<optgroup label="'.$p['name'].'">';
					while($t = mysql_fetch_assoc($tasks)){
						$html .= '	<option value="'.$t['id'].'"'.($item['id']==$p['id']?' selected="selected"':'').'>'.$t['name'].'</option>';
					}
					$html .= '	</optgroup>';
				}
				else{
					$html .= '	<option value="'.$p['id'].'"'.($item['id']==$p['id']?' selected="selected"':'').'>[ '.$p['name'].' ]</option>';
				}
			}
			$html .= '		</select>
							<select name="op61" class="op61" style="width:100px!important;'.($a['pid']==61?'':'display:none;').'" title="Employee">';
			while($u = mysql_fetch_assoc($users)){
				$html .= '<option value="'.$u['id'].'"'.($item['id']==$u['id']?' selected="selected"':'').'>'.$u['login'].'</option>';
			}
			$html .= '		</select>';
			if($a['oid']==62){
				$html .= '		<input class="ecostfee" type="text" value="'.$a['costplaninreg'].'" style="width:60px;text-align:right;" />';
				$html .= '		<input class="ecostconv" type="text" value="'.$a['costplanoutreg'].'" style="width:60px;text-align:right;" />';
				$html .= '		<select class="evalspan" style="width:60px;">';
				foreach($this->val as $k=>$v){
					$html .= '		<option value="'.$k.'"'.($a['span']==$k?' selected="selected"':'').'>'.$v.'</option>';
				}
				$html .= '		</select>';
			
			}
		}
		elseif($a['oid']==59||$a['oid']==60){
			$html .= '
							<select class="etype2" style="width:100px;">
								<option value="59"'.($a['span']==59?' selected="selected"':'').'>Income</option>
								<option value="60"'.($a['span']==60?' selected="selected"':'').'>Expense</option>
							</select>';
		}
		
		$html .= '		</td>
						<td width="65">
							<span class="btn f-l" onclick="FinanceChange(\'save\',\''.$a['id'].'\');"><span class="iconlblue icon-disk" title="Save"></span></span>
							<span class="btn f-l" onclick="FinanceChange(\'cancel\',\''.$a['id'].'\');"><span class="iconred icon-close" title="Cancel"></span></span>
						</td>
					</tr>
				</table>
			</div>';
		
		return $html;
	}
	function orders(){
		$html = '';
		if($this->r('act')!=''){
			switch($this->r('act')){
				case'status':{
					$this->q("UPDATE jos_whm SET status='".$this->r('value')."' WHERE id='".$this->r('oid')."'");
					break;
				}
				case'trash':{
					$this->q("UPDATE jos_whm SET trash='1' WHERE id='".$this->r('oid')."'");
					break;
				}
			}
		}
		$html = '<h1>Invoices</h1>';
		$all = $this->q("SELECT * FROM jos_whm WHERE type='order' AND user='".$this->uid."' AND trash='0' ORDER BY id DESC");
		$i = mysql_num_rows($all);
		if($i>0){
			$html .= '<ul class="finance">';
			while($a = mysql_fetch_assoc($all)){
				$project = mysql_fetch_assoc($this->q("SELECT name FROM jos_whm WHERE type='project' AND id='".$a['project']."'"));
				$html .='<li class="st'.($a['status']==1?'1':'0').'">
						<table cellpadding="5" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="10" align="right">
									'.$i.'
								</td>
								<td width="20" align="right">
									'.$a['id'].'
								</td>
								<td>
									<a href="javascript:void(0)" class="js c-333" onclick="jQuery(this).parent().find(\'.more\').toggle()">'.$a['contacts'].'</a><div class="more" style="display:none;">'.$a['name'].''.($project['name']!=''?'<div style="border-top:1px dashed #000;">Project: <b>'.$project['name'].'</b></div>':'').'<div style="border-top:1px dashed #000;">'.$a['users'].'</div></div>
								</td>
								<td width="200" class="ta-r ff-c fs-14">
									'.($a['val']==768?$this->val[$a['val']].' '.number_format($a['cost'],2,'.',' '):number_format($a['cost'],2,'.',' ').' '.$this->val[$a['val']]).'
								</td>
								<td width="70" align="center">
									'.$a['span'].' '.$a['level'].'
								</td>
								<td width="20">
									'.date('d.m.y',strtotime($a['date1'])).'
								</td>
								<td width="90">
									<a class="btn f-l br-15 p-5" href="javascript:void(0)" onclick="Page(\'orders&act=status&value=1&oid='.$a['id'].'\',e(\'content\'))"><span title="Payed" class="icongreen icon-check"></span></a>
									<a class="btn f-l br-15 p-5" href="pdf.php?col='.$a['level'].'&receiver='.$a['name'].'&payer='.$a['contacts'].'&fname='.$a['users'].'&mer='.$a['span'].'&price='.number_format($a['cost'],2,'.',' ').'&valute='.$this->val[$a['val']].'&oid='.$a['id'].'" target="_blank"><span title="Print (export to PDF)" class="iconblue icon-print"></span></a>
									<a class="btn f-l br-15 p-5" href="javascript:void(0)" onclick="if(confirm(\'Are You sure You want to delete the invoice #'.$a['id'].' ?\')){Page(\'orders&act=trash&oid='.$a['id'].'\',e(\'content\'));}"><span title="Delete" class="iconred icon-closethick"></span></a>
								</td>
							</tr>
						</table>
					</li>';
				$i--;
			}
			$html .= '</ul>';
		}
		else{
			$html .= '[ - No invoices - ]';
		}
		return $html;
	}
}
?>
