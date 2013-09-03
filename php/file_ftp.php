<?php
// session_start();
// require_once("db.php");
require_once("kernel.php");
$k = new kernel;
// $b = $k->f('base');
// $k->start();
// f($_REQUEST['type']);
//$db = f('DB');
// print_r($k);
// print_r($b);
// print_r($_REQUEST);
// echo '<pre>';print_r($_FILES);echo '</pre>';
//echo mysql_num_rows(mysql_query("SELECT * FROM jos_xaki_files"));
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo f('header')?>
	<style>
	html,body{background:none;min-width: 380px;}
	</style>
</head>
<body>
<?php
// echo $k->r('uid');
if($k->r('uid')>0){
	// if($_REQUEST['act']=='demo')
	// {
	// echo '<h2 style="color:#f00;">Загрузка файлов отключена в DEMO режиме</h2>';
	// }
	// else
	// {
	$html = '';
	?>
	<h2>Upload files to <b><?php echo $k->r('name')?></b></h2>
	<form enctype="multipart/form-data" method="post" action="file_ftp.php" id="formedit">
		<input type="hidden" name="type" value="ftpsave" />
		<input type="hidden" name="pid" value="<?php echo $k->r('pid')?>" />
		<input type="hidden" name="uid" value="<?php echo $k->r('uid')?>" />
		<input type="hidden" name="name" value="<?php echo $k->r('name')?>" />
		<input type="hidden" name="path" value="<?php echo $k->r('path')?>" />
		<input type="file" name="file[]" multiple onchange="handleFiles(this.files)" /> <input type="submit" value="Upload" class="btn bg-green" />
	</form>
	<?php
	// }
	// if(isset($_FILES['file'])&&$_FILES['file']['size']==0){
		// echo '<div class="ui-state-error ui-corner-all"> 
								// <p><span class="icon icon-info"></span>
								// <strong>Select a file!</strong></p>
							// </div>';
	// }
	// elseif(isset($_FILES['file'])&&$_FILES['file']['size']>20000000){
		// echo '<div class="ui-state-error ui-corner-all"> 
								// <p><span class="icon icon-info"></span>
								// <strong>File size is more than 20MB!</strong></p>
							// </div>';
	// }
	// elseif(isset($_FILES['file'])&&$_FILES['file']['size']<20000000&&$_FILES['file']['size']>0)
	// {
		/* $t = explode('.',$_FILES['file']['name']);
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
				$target_path = "../files/img/";
				$target_path2 = "1or/files/img/";
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
				$target_path = "../files/arch/";
				$target_path2 = "1or/files/arch/";
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
				$target_path = "../files/xls/";
				$target_path2 = "1or/files/xls/";
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
				$target_path = "../files/doc/";
				$target_path2 = "1or/files/doc/";
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
		} */
		$files = $k->r('files');
		if($files){
			$k->start();
			$p = mysql_fetch_assoc($k->q("SELECT * FROM jos_whm WHERE id=".$k->r('pid').""));
			$ftp_server = $p['ftphost'];
			$ftp_user_name = $p['ftplogin'];
			$ftp_user_pass = $p['ftppass'];
			$ftp_ssl = $p['ftpssl'];
			if($ftp_ssl){
				$conn_id = ftp_ssl_connect($ftp_server);
			}
			else{
				$conn_id = ftp_connect($ftp_server);
			}
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
			ftp_pasv($conn_id,true);
			if($login_result){
				// $html2 = '<p>'.$files.'</p>';
				// echo '<pre>';print_r($_FILES);echo '</pre>';
				// for($i=0;$i<$files;$i++){
				$i=0;
				foreach($_FILES["file"]['name'] as $file){
					// $file = fopen($k->r('file'),'r');
					
					$html2 .= '<p>'.$k->r('file'.$i).'</p>';
					ftp_put($conn_id,$k->r('path').'/'.$k->r('name').'/'.$file,$_FILES["file"]['tmp_name'][$i],FTP_ASCII);
					$i++;
				}
				$html .= $k->info('info','<strong>'.$files.' file'.($files>1?'s are':' is').' uploaded</strong>').$html2;
			}
			else{
				$html .= $k->info('alert','<strong>Connection error!</strong>');
			}
		}
	// }
}
echo $html;
//echo $_REQUEST['id'].' - ';
// echo '<input type="button" value="zip" onclick="" />';
// $files = mysql_query("SELECT * FROM jos_whm WHERE type='file' AND ".$_REQUEST['type']."='".$_REQUEST['iid']."'");
//echo mysql_num_rows($files);
//echo '<hr />';
/* if(mysql_num_rows($files)>0){
	$files_string = '';
	while($f = mysql_fetch_array($files)){
		//str_replace('1or/','../',$f['name'])
		$name = explode('/',$f['name']);
		switch($name[2]){
			case'img':{
				$src = $k->siteurl.'1or/files/img/'.$name[count($name)-1];
				break;
			}
			default:{
				$src = $k->siteurl.'1or/img/file_'.$name[2].'.png';
				break;
			}
		}
		echo '<div style="display:block;float:left;width:100px;height:130px;text-align:center;padding:5px 0;border:1px solid #ccc;margin:5px 0 0 5px;">
				<a href="'.$k->siteurl.'i/'.str_replace('.','-',$name[count($name)-1]).'.html" target="_blank"><img id="file'.$f['id'].'" alt="" src="'.$src.'" border="0" style="max-height:90px;max-width:90px;" /></a>
				<br />
				<span class="fs-11">'.$f['id'].'</span>
				<br />
				<span class="c-999 fs-11">('.ceil($f['val']/1000).'kB)</span>
				<br />
				<input type="text" style="width:90px;" value="'.$k->siteurl.'i/'.str_replace('.','-',$name[count($name)-1]).'.html" onclick="select()" />
			</div>';
	}
}
else{
	echo '- No files -';
} */
//echo $files_string;
?>
</body>
</html>