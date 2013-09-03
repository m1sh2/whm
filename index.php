<?php
$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");



$worksclose = 0;
if($worksclose==0){
// header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// header("Cache-Control: no-cache, must-revalidate");
// header("Pragma: no-cache");
// header("Last-Modified: ".gmdate("D, d M Y H:i:s")."GMT");

session_start();
if(isset($_REQUEST['chrome'])){
	$_SESSION['chrome'] = $_REQUEST['chrome'];
}
elseif(isset($_SESSION['chrome'])){
	$_SESSION['chrome'] = $_SESSION['chrome'];
}
else{
	$_SESSION['chrome'] = 0;
}


// $_SESSION = array();
// print_r($_REQUEST);
// echo $_SESSION['chrome'];
// ini_set('session.gc_maxlifetime',8*60*60);
// ini_set('session.gc_probability',1);
// ini_set('session.gc_divisor',1);

// echo $_COOKIE['newid'];
// if(isset($_COOKIE['newid'])&&isset($_COOKIE['id'])&&$_COOKIE['id']!==$_COOKIE['newid']){
	// $_COOKIE['id'] = $_COOKIE['newid'];
	// setcookie('id',$_COOKIE['newid'],time()+360000);
// }
require_once('php/kernel.php');
$k = new kernel;

$at = $k->r('action')!='!--empty--!'?explode('-',$k->r('action')):array();

$k->action = count($at)>0&&$at[0]!==''?$at[0]:'home';
$_REQUEST['idin'] = count($at)>1?$at[1]:'none';
$_REQUEST['idin2'] = count($at)>2?$at[2]:'none';
$_REQUEST['idin3'] = count($at)>3?$at[3]:'none';
if(count(explode('/',$k->r('action')))>1){
	$a = explode('/',$k->r('action'));
	$k->action = $a[0];
	$_REQUEST['idin'] = $a[1];
}
// print_r($_REQUEST);
//echo $k->action;
//echo $k->action.' '.$k->request('action');



?>
	<!DOCTYPE html>
	<html>
		<head>
			<?php echo f('header');?>
		</head>
		<body>
			<script type="text/javascript">

			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', 'UA-30968927-1']);
			  _gaq.push(['_trackPageview']);

			  (function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();

			</script>
			<span class="free"></span>
			<div id="page" class="width<?php echo $k->display&&$k->display>0?$k->display:'auto';?>">
				<div id="waiting"></div>
				<a name="top"></a>
				<?php echo f('pnl');?>
				<div id="messages"></div>
				<div id="content"><?php echo f($k->action);

$fields = array('id','priority','pid','type','client','project','task','status','name','cost','costplanin','costplanout','costplaninreg','costplanoutreg','span','worker','contacts','level','date1','date2','date3','date4','date5','date6','date7','time','val','user','users','trash','email','login','password','html','css','php','js','mysql','joomla','settings','ftphost','ftplogin','ftppass','ftpssl','subscribe','domain','language','public');
// echo count($fields).'<br /><br />';
// $query = $k->q("SELECT * FROM jos_whm a WHERE a.type='task' ORDER BY a.id ASC");
// $q = "";
/* while($result = mysql_fetch_assoc($query)){
	switch($result['type']){
		case 'user':
			$q .= "
				INSERT INTO `users` (`id`,`type`,`name`,`login`,`password`,`date`,`datelastlogin`) VALUES ('".$result['id']."',2,'".$result['name']."','".$result['login']."','".$result['password']."','".$result['date1']."','".$result['date2']."');
				--separator--";
			break;
		case 'project':
			$q .= "
				INSERT INTO `projects` (`id`,`priority`,`name`,`uid`,`vid`,`date`,`date2`,`sid`,`sets`,`cid`,`desc`) VALUES ('".$result['id']."','".$result['priority']."','".$result['name']."','".$result['user']."','".$result['val']."','".$result['date1']."','".$result['date2']."','".$result['status']."','".$result['cost'].":0:".$result['domain'].":".$result['ftphost'].":".$result['ftplogin'].":".$result['ftppass'].":".$result['ftpssl']."','".$result['client']."','".$result['contacts']."');
				--separator--";
			break;
		case 'task':
			// $q .= "
				// INSERT INTO `tasks` (`id`,`name`,`pid`,`sets`,`uid`,`user`,`sid`,`date`,`date2`) VALUES ('".$result['id']."','".$result['name']."','".$result['project']."','".$result['cost']."','".$result['user']."','".$result['users']."','".$result['status']."','".$result['date1']."','".$result['date2']."');
				// --separator--";
			$q .= "
				UPDATE `tasks` SET `cost`='".$result['cost']."' WHERE `id`='".$result['id']."';
				--separator--";
			break;
		case 'item':
			$q .= "
				INSERT INTO `items` (`id`,`iid`,`tid`,`sid`,`name`,`lvl`,`time`,`date`,`date2`) VALUES ('".$result['id']."','".$result['pid']."','".$result['task']."','".$result['status']."','".str_replace("'","\'",$result['name'])."','".$result['level']."','".$result['time']."','".$result['date1']."','".$result['date7']."');
				--separator--";
			break;
		case 'operation':
			$q .= "
				INSERT INTO `operations` (`id`,`name`) VALUES ('".$result['id']."','".$result['name']."');
				--separator--";
			break;
		case 'valute':
			$q .= "
				INSERT INTO `valute` (`id`,`name`) VALUES ('".$result['id']."','".$result['name']."');
				--separator--";
			break;
		case 'client':
			$q .= "
				INSERT INTO `users` (`id`,`type`,`name`,`uid`,`date`) VALUES ('".$result['id']."',".($result['status']==0?3:4).",'".$result['name']."','".$result['user']."','".$result['date1']."');
				--separator--";
			break;
		case 'finance':
			$query2 = mysql_fetch_assoc($k->q("SELECT * FROM jos_whm a WHERE a.id='".$result['project']."'"));
			$q .= "
				INSERT INTO `finance` (`id`,`name`,`vid`,`uid`,`cost`,`date`,`oid`".($result['project']>0?($query2['type']=='task'?",`tid`":",`pid`"):"").") VALUES ('".$result['id']."','".$result['name']."','".$result['val']."','".$result['user']."','".$result['cost']."','".$result['date1']."','".$result['pid']."'".($result['project']>0?",'".$result['project']."'":"").");
				--separator--";
			break;
		case 'post':
			// $q .= "
				// INSERT INTO `users` (`id`,``) VALUES ('".$result['id']."','".$result['type']."');
				// ";
			break;
	}
	
} */
// echo '<textarea cols="100" rows="25">'.$q.'</textarea>';
// $q = explode('--separator--',$q);
// for($i=0;$i<count($q);$i++){
	// $query = $k->q($q[$i]);
	// echo '<p>'.$i.'. '.$query.'</p>';
// }
// echo '<p>'.$i.'</p>';











				?></div>
				<?php echo f('footer');?>
				<div id="browserdetect"></div>
				<script type="text/javascript">
					jQuery(function(){
						var w = jQuery(document).width();
						var h = <?php echo $_SESSION['chrome']=='vk'?'500':'jQuery(document).height()'?>;
						jQuery('#page').css('height',h+'px');
						jQuery(document).scroll(function(){
							// goToByScroll('control');
						});
						datepick();
						if(jQuery('#panel')){
							jQuery(window).scroll(function(){
								// jQuery('#debug').html(jQuery(document).scrollTop()+'<br />');
								// if(jQuery(window).scrollTop()==0){
									// jQuery('#panel').stop().animate({"marginTop":"0px"},0);
								// }
								// else{
									// jQuery('#panel').stop().animate({"marginTop":(jQuery(window).scrollTop())-32+"px"},0);
								// }
								
								// jQuery('#panel').stop().animate({"marginTop":(jQuery(window).scrollTop()) + "px"}, "fast" );			
							});
						}
						// window.scrollTo(0,0)
						jQuery("a.thickbox").fancybox({'autoDimensions':true,'overlayColor':'#000','hideOnContentClick':true,'autoScale':false});
						jQuery(".timelinearea").draggable({ axis: "x" });
					});
					var val = new Array();
					<?php
					$valute = $k->q("SELECT * FROM jos_whm AS v WHERE type='valute' ORDER BY v.id ASC");
					while($v = mysql_fetch_assoc($valute)){
						echo 'val['.$v['id'].'] = '.$v['cost'].';';
					}
					?>
					// VK.init(function() {
						// VK.api('users.get',{"uids":<?php echo $k->r('viewer_id');?>},function(data){
							//alert(JSON.stringify(data, null, 4));
						// });
					// });
				</script>
				<div id="debug" class="d-">
				<?php
				// print_r($_REQUEST);
				// $response = $k->r('api_result');
				// print_r($response);
				?>
				</div>
			</div>
		</body>
	</html>
<?php

}else{?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<meta name='yandex-verification' content='6b8e18e6e8a78547' />
			<meta name="webmoney.attestation.label" content="webmoney attestation label#CC5A1D62-2ABF-402B-BC81-1EBCCC6E3821" /> 
			<title>&#9733; WHM &#9733;</title>
		</head>
		<body>
			<img src="img/stop-works<?php echo rand(1,7);?>.jpg" style="position:fixed;left:50%;top:50%;margin:-125px 0 0 -250px;" />
		</body>
	</html>
<?php }?>
