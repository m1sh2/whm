<?php
require_once('base.php');

class nws extends base{
	function __construct(){
		parent::__construct();
	}
	function nws(){
		$html = '';
		switch($this->r('act')){
			case'add':{
				$this->q("INSERT INTO jos_whm_news (date,user,publish,title,short,full) VALUES ('".($this->r('date')==''?date('Y-m-d H:i:s'):$this->r('date'))."','".$this->uid."','".$this->r('publish')."','".$this->r('title')."','".$this->r('short')."','".$this->r('full')."')");
				$_REQUEST = array();
				$html .= $this->news();
				break;
			}
			case'publish':{
				//$html .= $this->r('p').' - '.$this->r('id');
				$this->q("UPDATE jos_whm_news SET publish='".$this->r('p')."' WHERE id='".$this->r('id')."'");
				$_REQUEST = array();
				$html .= $this->news();
				break;
			}
			case'delete':{
				$this->q("DELETE FROM jos_whm_news WHERE id='".$this->r('id')."'");
				$_REQUEST = array();
				$html .= $this->news();
				break;
			}
			default:{
				if(isset($this->uid)&&$this->uid>0&&$this->utype==120345891){
					$html .= '<h5 onclick="h5(this,\'addnews\')">'.$this->lng['add news'].'</h5>
						<div id="addnews" style="display:none;">
							<form method="post" action="javascript:void(0)" onsubmit="Page(\'news&act=\'+this.act.value+\'&date=\'+this.date.value+\'&title=\'+this.title.value+\'&short=\'+this.short.value+\'&full=\'+this.full.value,e(\'content\'),\'\')">
								<input type="hidden" name="act" value="add" />
								<table cellpadding="5" cellspacing="0" border="0">
									<tr><td>'.$this->lng['date'].'</td><td><input type="text" name="date" /></td></tr>
									<tr><td>'.$this->lng['title'].'</td><td><input type="text" name="title" /></td></tr>
									<tr><td>'.$this->lng['short description'].'</td><td><textarea cols="50" rows="3" name="short"></textarea></td></tr>
									<tr><td>'.$this->lng['full description'].'</td><td><textarea cols="50" rows="5" name="full"></textarea></td></tr>
									<tr><td>'.$this->lng['not public'].'</td><td><input type="checkbox" name="publish" value="1" /></td></tr>
									<tr><td></td><td><input type="submit" value="'.$this->lng['add'].'" name="submit" /></td></tr>
								</table>
							</form>
						</div>
						';
				}
				$query = $this->q("SELECT id,date,title,full,publish FROM jos_whm_news".(isset($this->uid)&&$this->uid>0&&$this->utype==120345891?'':' WHERE publish=\'1\'')." ORDER BY date DESC");
				$html .= '<h1>News</h1>';
				while($a = mysql_fetch_assoc($query)){
					$html .= '<h2>'.date('d.m.y',strtotime(substr($a['date'],0,10))).' '.$a['title'].''.(isset($this->uid)&&$this->uid>0&&$this->utype==120345891?'<a class="deletetobasket f-r" title="'.$this->lng['delete news'].'" href="javascript:void(0)" onclick="Page(\'news&act=delete&id='.$a['id'].'\',e(\'content\'),\'\')" style="margin-right:10px;"></a><a class="'.($a['publish']=='1'?'offline':'online').' f-r" title="'.($a['publish']=='1'?$this->lng['remove from publication']:$this->lng['public']).' '.$this->lng['new news'].'" href="javascript:void(0)" onclick="Page(\'news&act=publish&p='.($a['publish']=='1'?'0':'1').'&id='.$a['id'].'\',e(\'content\'),\'\')" style="margin-right:0px;"></a>':'').'</h2>
						<div id="news'.$a['id'].'" style="display:;">'.$a['full'].'</div>
						<p>&nbsp;</p>
						';
				}
				$html .= '';
				break;
			}
		}
		return $html;
	}
	function subscribe(){
		$html = '';
		switch($this->r('act')){
			case'send':{
				$html .= '<h2>Report</h2>';
				// $html .= '<p>'.$this->r('subject').'</p>';
				$txt = explode("/n",$this->r('text'));
				$text = '';
				foreach($txt as $t){
					$text .= '<p>'.$t.'</p>';
				}
				$subscribers = $this->q("SELECT * FROM jos_whm WHERE type='user' AND subscribe=1");
				while($s = mysql_fetch_assoc($subscribers)){
					$text2 = $text.'<table>
							<tr>
								<td><a href="'.$this->site.'subscribe.html?act=unsubscribe&id='.$s['id'].'">Unsubscribe</td>
							</tr>
						</table>';
					$this->email($s['email'],'News in service!',$text2);
					$html .= '<p>'.$s['login'].' '.$s['email'].' <b>sent!</b></p>';
				}
				$this->email('misha@asdat.org','Subscribe report',$html);
				break;
			}
			case'unsubscribe':{
				// $html .= '<p>'.$this->r('subject').'</p>';
				// $html .= '<p>'.$this->r('text').'</p>';
				if(mysql_num_rows($this->q("SELECT * FROM jos_whm WHERE type='user' AND id=".$this->r('id')))==1){
					$this->q("UPDATE jos_whm SET subscribe=0 WHERE id=".$this->r('id'));
					$html .= '<h1>Subscribe</h1>';
					$html .= '<div class="ui-state-highlight ui-corner-all"> 
									<p><span class="icon icon-info"></span>
									<strong>Success! You are unsubscribed successfully.</strong></p>
								</div>';
				}
				else{
					$_REQUEST = array();
					$html .= $this->subscribe();
				}
				break;
			}
			default:{
				$html .= '<h1>Subscribe</h1>';
				if($this->uid>0){
					if($this->uid==818){
						$html .= '<h3>Send subscribtion</h3>';
						$html .= '<form id="subscribeform" onsubmit="FormDebug(this,this);return false">';
						$html .= '<input type="hidden" name="type" value="subscribe" />';
						$html .= '<input type="hidden" name="act" value="send" />';
						// $html .= '<div><h4>Subject</h4><input type="text" name="subject" value="" /></div>';
						$html .= '<div><h4>Message</h4><textarea name="text" style="width:90%;height:200px;"></textarea></div>';
						$html .= '<div><input type="submit" name="submit" value="Send" /></div>';
						$html .= '</form>';
					}
					$u = mysql_fetch_assoc($this->q("SELECT subscribe FROM jos_whm WHERE id=".$this->uid));
					if($u['subscribe']){
						$html .= '<h3><a href="'.$this->site.'subscribe.html?act=unsubscribe&id='.$this->uid.'">Unsubscribe</a></h3>';
					}
					else{
						$html .= '<h3><a href="'.$this->site.'subscribe.html?act=subscribe&id='.$this->uid.'">Subscribe</a></h3>';
						
					}
					$html .= '<p>If You do not getting email, please check spam box. Maybe they are there.</p>';
				}
				else{
					$html .= '<h2>To subscribe for news You need to be a registered user.</h2>';
					$html .= '<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="45%"><h3 class="btn bg-green br-24 fs-30 p-15" onclick="window.location=\''.$this->site.'login.html\';">Log in</h3></td>
								<td width="100">&nbsp;</td>
								<td width="45%"><h3 class="btn bg-red br-24 fs-30 p-15" onclick="window.location=\''.$this->site.'registration.html\';">Registration</h3></td>
							</tr>
						</table>
						';
				}
				break;
			}
		}
		return $html;
	}
}
?>