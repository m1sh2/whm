<?php
session_start();
// require_once("db.php");
require_once("kernel.php");
$k = new kernel;
$k->start();
// f($_REQUEST['type']);
//$db = f('DB');
//print_r($db);
// print_r($_REQUEST);
//echo mysql_num_rows(mysql_query("SELECT * FROM jos_xaki_files"));
?>
<!DOCTYPE html>
<html>
<head>
	<script language="JavaScript" type="text/javascript">
	var chrm = '<?php echo $_SESSION['chrome']?>';
	// if(top!=self){
		// window.location = '<?php echo $k->site?>?chrome=0';
		// alert(1);
	// }
	if(chrm=='1'){
		window.location = '<?php echo $k->site?>?chrome=0';
		// alert(2);
	}
	</script>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name='yandex-verification' content='6b8e18e6e8a78547' />
	<meta name="webmoney.attestation.label" content="webmoney attestation label#CC5A1D62-2ABF-402B-BC81-1EBCCC6E3821" />
	<meta http-equiv="PRAGMA" content="NO-CACHE" />
	<meta http-equiv="CACHE-CONTROL" content="NO-CACHE" />
	<title><?php echo $k->title($k->action);?> -= WHM =- </title>
	<link href="<?php echo $k->site?>img/logo2.ico" rel="shortcut icon" />
	<link href="<?php echo $k->site?>css/main.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo $k->site?>css/style<?php echo $_SESSION['chrome']==1?'400':''?>.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo $k->site?>css/calendar.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo $k->site?>css/jquery.fancybox-1.3.4.css" type="text/css" rel="stylesheet" />
	<link href="<?php echo $k->site?>css/ui/jquery-ui-1.10.3.custom.min.css" type="text/css" rel="stylesheet" />
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/jquery-2.0.1.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/jquery-migrate-1.2.1.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/jquery-ui-1.10.3.custom.min.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/jquery.fancybox-1.3.4.pack.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/jquery.countdown.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/jquery.countdown-ru.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/ace/ace.js" charset="utf-8"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/ace/mode-javascript.js" charset="utf-8"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/ace/mode-css.js" charset="utf-8"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/ace/mode-html.js" charset="utf-8"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/ace/mode-php.js" charset="utf-8"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/ace/theme-twilight.js" charset="utf-8"></script>
	<script language="JavaScript" type="text/javascript">
	jQuery.noConflict();
	</script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/js.js" charset="utf-8"></script>
	<script language="JavaScript" type="text/javascript" src="<?php echo $k->site?>js/md5.js" charset="utf-8"></script>
	<script language="JavaScript" type="text/javascript">
	window.siteurl = "<?php echo $k->site?>";
	// setCookie('siteurl','<?php echo $k->site?>',14400);
	</script>
</head>
<body>
<?php
// echo $k->uid;
if(isset($_REQUEST['uid'])&&$_REQUEST['uid']>0)
{
	// if($_REQUEST['act']=='demo')
	// {
	// echo '<h2 style="color:#f00;">Загрузка файлов отключена в DEMO режиме</h2>';
	// }
	// else
	// {
	$html = '';
	?>
	<form enctype="multipart/form-data" method="post" action="file_form.php">
		<input type="hidden" name="type" value="<?php echo $_REQUEST['type']?>" />
		<input type="hidden" name="iid" value="<?php echo $_REQUEST['iid']?>" />
		<input type="hidden" name="uid" value="<?php echo $_REQUEST['uid']?>" />
		<input type="file" name="file" /> <input type="submit" value="Add" class="button" />
	</form>
	<?php
	// }
	if(isset($_FILES['file'])&&$_FILES['file']['size']==0){
		echo '<div class="ui-state-error ui-corner-all"> 
								<p><span class="icon icon-info"></span>
								<strong>Select a file!</strong></p>
							</div>';
	}
	elseif(isset($_FILES['file'])&&$_FILES['file']['size']>10000000){
		echo '<div class="ui-state-error ui-corner-all"> 
								<p><span class="icon icon-info"></span>
								<strong>File size is more than 10MB!</strong></p>
							</div>';
	}
	elseif(isset($_FILES['file'])&&$_FILES['file']['size']<10000000&&$_FILES['file']['size']>0)
	{
		$t = explode('.',$_FILES['file']['name']);
		$type = $t[count($t)-1];
		$error = 0;
		//$html .= $type.'<br />';
		switch($type)
		{
			case'png':
			case'gif':
			case'jpeg':
			case'psd':
			case'xcf':
			case'svg':
			case'jpg':{
				$f = 1;
				$error = 0;
				$target_path = "../upload/";
				$target_path2 = "upload/";
				$filetypename = 'image';
				$filetype = 'img';
				break;
			}
			case'gz':
			case'tar':
			case'zip':
			case'rar':{
				$f = 2;
				$error = 0;
				$target_path = "../upload/";
				$target_path2 = "upload/";
				$filetypename = 'archive';
				$filetype = 'arch';
				break;
			}
			case'ods':
			case'xls':
			case'xlsx':
			case'xlt':{
				$f = 3;
				$error = 0;
				$target_path = "../upload/";
				$target_path2 = "upload/";
				$filetypename = 'table xls';
				$filetype = 'xls';
				break;
			}
			case'doc':
			case'docx':
			case'odt':
			case'txt':{
				$f = 4;
				$error = 0;
				$target_path = "../upload/";
				$target_path2 = "upload/";
				$filetypename = 'txt file';
				$filetype = 'doc';
				break;
			}
			default:{
				$error = 1;
				$html .= '<div class="ui-state-error ui-corner-all"> 
								<p><span class="icon icon-info"></span>
								<strong>Unsupported file type!</strong> Support file types: png, gif, jpeg, jpg, gz, tar, zip, rar, ods, xls, xlsx, xlt, doc, docx, odt, txt.</p>
							</div>';
				break;
			}
		}
		if($error==0){
				mysql_query("INSERT INTO jos_whm (".$_REQUEST['type'].",type,level,user,val,date1,contacts) VALUES ('".$_REQUEST['iid']."','file','".$filetype."','".$_REQUEST['uid']."','".$_FILES['file']['size']."','".date('Y-m-d H:i:s')."','".$_FILES['file']['name']."')");
				$id = mysql_insert_id();
				// echo $id;
				// $_SESSION['umoney'] = $_SESSION['umoney']-(round($_FILES['file']['size']/10000000,2)<$_SESSION['filecost']?$_SESSION['filecost']:round($_FILES['file']['size']/10000000,2));
				// mysql_query("UPDATE jos_xaki_users SET money='".$_SESSION['umoney']."' WHERE id='".$_SESSION['uid']."'");
				mysql_query("UPDATE jos_whm SET name='".$target_path2.$id.'.'.$type."' WHERE id='".$id."'");
				$target_path = $target_path.basename($id.'.'.$type);
				if(move_uploaded_file($_FILES['file']['tmp_name'],$target_path))
				{
					//$html = $this->redirect('projects.html#'.$_REQUEST['code']);
					//header('Location:'.$_SESSION['siteurl'].'/);
					//$html = $this->redirect('home.html#myprojects/'.$_REQUEST['code'].'/'.$_REQUEST['liid']);
					
					$html .= '<div class="ui-state-highlight ui-corner-all"> 
								<p><span class="icon icon-info"></span>
								<strong>File is uploaded</strong></p>
							</div>';
				}
				else
				{
					mysql_query("DELETE FROM jos_whm WHERE id='".$id."'");
					$html .= '<div class="ui-state-error ui-corner-all"> 
								<p><span class="icon icon-info"></span>
								<strong>File is NOT uploaded!</strong></p>
							</div>';
				}
		}
		echo $html;
	}
}
//echo $_REQUEST['id'].' - ';
// echo '<input type="button" value="zip" onclick="" />';
$files = mysql_query("SELECT * FROM jos_whm WHERE type='file' AND ".$_REQUEST['type']."='".$_REQUEST['iid']."'");
//echo mysql_num_rows($files);
//echo '<hr />';
if(mysql_num_rows($files)>0){
	$files_string = '';
	while($f = mysql_fetch_array($files)){
		//str_replace('1or/','../',$f['name'])
		$name = explode('/',$f['name']);
		switch($name[2]){
			case'img':{
				$src = $k->site.'upload/'.$name[count($name)-1];
				break;
			}
			default:{
				$src = $k->site.'upload/file_'.$name[2].'.png';
				break;
			}
		}
		echo '<div style="display:block;float:left;width:100px;height:130px;text-align:center;padding:5px 0;border:1px solid #ccc;margin:5px 0 0 5px;">
				<a href="'.$k->site.'i/'.str_replace('.','-',$name[count($name)-1]).'.html" target="_blank"><img id="file'.$f['id'].'" alt="" src="'.$src.'" border="0" style="max-height:90px;max-width:90px;" /></a>
				<br />
				<span class="fs-11">'.$f['id'].'</span>
				<br />
				<span class="c-999 fs-11">('.ceil($f['val']/1000).'kB)</span>
				<br />
				<input type="text" style="width:90px;" value="'.$k->site.'i/'.str_replace('.','-',$name[count($name)-1]).'.html" onclick="select()" />
			</div>';
	}
}
else{
	echo '- No files -';
}
//echo $files_string;
?>
</body>
</html>