<?php
require_once('base.php');

class ftp extends base{
	function __construct(){
		parent::__construct();
	}
	function ftp($path=''){
		$path = $this->r('path')!=''?$this->r('path'):$path;
		$rj = $this->r('j');
		$level = $this->r('level')==''?0:$this->r('level');
		$html = '';
		if($rj==''){
			$html .= '';
			$html .= '<h2>
					<span class="close d-n" onclick="jQuery(\'#pftp'.$this->r('pid').'\').hide();"><span class="icon3 icon-close" title="Close"></span></span>
					FTP connection
				</h2>';
		}
		
		$p = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE id=".$this->r('pid').""));
		$ftp_server = $p['ftphost'];
		$ftp_user_name = $p['ftplogin'];
		$ftp_user_pass = $p['ftppass'];
		$ftp_ssl = $p['ftpssl'];
		$domain = $p['domain'];
		$file = "";//tobe uploaded 
		$remote_file = ""; 

		// set up basic connection 
		if($ftp_ssl){
			$conn_id = ftp_ssl_connect($ftp_server);
			// $connection = ssh2_connect($ftp_server, 22);
			// ssh2_auth_password($connection,$ftp_user_name,$ftp_user_pass);
			// $conn_id = ssh2_sftp($connection);
		}
		else{
			$conn_id = ftp_connect($ftp_server);
		}
		// login with username and password 
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
		ftp_pasv($conn_id,true);
		// upload a file ftp_put($conn_id, $remote_file, $file, FTP_ASCII)
		if($login_result){
			// echo '<pre>';print_r(scandir('/'));echo '</pre>';
			// echo "successfully uploaded $file\n";
			// exit;
			$folders = array();
			$files = array();
			$fsize = array();
			$fdate = array();
			$list = ftp_rawlist($conn_id,'-a /'.$path.'');
			for($i=0;$i<sizeof($list);$i++){
				list($permissions,$next) = explode(" ",$list[$i],2);
				list($num,$next) = explode(" ",trim($next),2);
				list($owner,$next) = explode(" ",trim($next),2);
				list($group,$next) = explode(" ",trim($next),2);
				list($size,$next) = explode(" ",trim($next),2);
				list($month,$next) = explode(" ",trim($next),2);
				list($day,$next) = explode(" ",trim($next),2);
				list($year_time,$filename) = explode(" ",trim($next),2);
				if($filename!="."&&$filename!=".."){
					if(substr($permissions,0,1)=="d"){
						$folders[] = $filename;
						$fdate[$filename] = $year_time.'-'.$day.'-'.$month;
					} 
					else {
						$files[] = $filename;
						$fsize[$filename] = $size;
						$fdate[$filename] = $year_time.'-'.$day.'-'.$month;
					}
				}
			}
			sort($folders);
			sort($files);
			// $html .= implode('<br />',$folders);
			// }
			// вывод $contents
			$j = 0;
			$html .= '<ul class="projects">';
			if($level>0){
				// $html .= '
						// <li>
							// <table class="tbl" cellspacing="0" cellpadding="5" border="0">
								// <tr>
									// <td width="10"><span class="iconorange icon-folder-collapsed" title="Folder"></span></td>
									// <td></td>
								// </tr>
							// </table>
						// </li>
						// ';
			}
			else{
				$html .= '
						<li>
							<table class="tbl" cellspacing="0" cellpadding="3" border="0">
								<tr>
									<td><span class="btn f-l p-2" style="margin:0 2px 0 0;" onclick="Page(\'ftpsave&act=create&pid='.$p['id'].'&path=/&j='.$rj.$j.'&level='.($level+1).'\',\'0\');"><span class="icongreen icon-plus" title="Create file/folder"></span></span></td>
								</tr>
							</table>
						</li>
						';
			}
			foreach($folders as $k=>$v){
				$html .= '
						<li class="folder'.$p['id'].''.$rj.$j.'">
							<table class="tbl" cellspacing="0" cellpadding="3" border="0">
								<tr>
									<td width="10"><span class="iconorange icon-folder-collapsed" title="Folder"></span></td>
									<td>
										<a href="javascript:void(0)" class="fs-14" onclick="Page(\'ftp&pid='.$p['id'].'&path='.$path.'/'.$v.'&j='.$rj.$j.'&level='.($level+1).'\',e(\'folder'.$p['id'].''.$rj.$j.'\'));jQuery(this).parent().find(\'span\').removeClass(\'d-n\');">'.$v.'</a>
										<span class="cr-p fs-14 d-n" onclick="jQuery(\'#folder'.$p['id'].''.$rj.$j.'\').html(\'\');jQuery(this).addClass(\'d-n\');">&#8624;</span>
									</td>
									<td width="100" class="fs-12 ta-r ff-c">
										'.$fdate[$v].'
									</td>
									<td width="100">
										<span class="btn f-r p-2" style="margin:0 2px 0 0;" onclick="if(confirm(\'Are You sure You want to delete this folder?\')){jQuery(\'.folder'.$p['id'].''.$rj.$j.'\').hide();Page(\'ftpsave&pid='.$p['id'].'&act=delete&act2=folder&path='.$path.'/'.$v.'\',\'h\');}"><span class="iconred icon-trash" title="Delete"></span></span>
										<span class="btn f-r p-2" style="margin:0 2px 0 0;" onclick="Page(\'ftpsave&act=rename&pid='.$p['id'].'&path='.$path.'&j='.$rj.'&level='.($level+1).'&name='.$v.'\',\'0\');"><span class="iconblue icon-pencil" title="Rename"></span></span>
										<span class="btn f-r p-2" style="margin:0 2px 0 0;" onclick="Page(\'ftpsave&act=upload&pid='.$p['id'].'&path='.$path.'&j='.$rj.$j.'&level='.($level+1).'&name='.$v.'\',\'0\');"><span class="iconlblue icon-arrowthickstop-1-n" title="Upload"></span></span>
										<span class="btn f-r p-2" style="margin:0 2px 0 0;" onclick="Page(\'ftpsave&act=create&pid='.$p['id'].'&path='.$path.'/'.$v.'&j='.$rj.$j.'&level='.($level+1).'&folder='.$p['id'].$rj.$j.'\',\'0\');"><span class="icongreen icon-plus" title="Create file/folder"></span></span>
									</td>
								</tr>
							</table>
							<div id="folder'.$p['id'].''.$rj.$j.'" style="padding:0 0 0 20px;"></div>
						</li>
						';
				$j++;
			}
			foreach($files as $k=>$v){
				$filetype = explode('.',$v);
				switch($filetype[count($filetype)-1]){
					case'js':{
						$filetype = 'javascript';
						$color = 'c-green2';
						$url = 'javascript:void(0)';
						break;
					}
					case'html':{
						$filetype = 'html';
						$color = 'c-orange';
						$url = 'javascript:void(0)';
						break;
					}
					case'css':{
						$filetype = 'css';
						$color = 'c-rose';
						$url = 'javascript:void(0)';
						break;
					}
					case'tpl':
					case'php':{
						$filetype = 'php';
						$color = 'c-red';
						$url = 'javascript:void(0)';
						break;
					}
					case'gif':
					case'jpeg':
					case'psd':
					case'xcf':
					case'cdr':
					case'svg':
					case'tiff':
					case'ai':
					case'jpg':
					case'ico':
					case'png':{
						$filetype = 'image';
						$color = 'c-0c0';
						$url = 'http://'.$domain.''.$path.'/'.$v;
						break;
					}
					case'ttf':
					case'otf':
					case'pfb':
					case'pfm':
					case'eot':{
						$filetype = 'font';
						$color = 'c-06f';
						$url = 'http://'.$domain.''.$path.'/'.$v;
						break;
					}
					case'pdf':{
						$filetype = 'pdf';
						$color = 'c-00f';
						$url = 'http://'.$domain.''.$path.'/'.$v;
						break;
					}
					case'xml':{
						$filetype = 'html';
						$color = 'c-lblue';
						$url = 'javascript:void(0)';
						break;
					}
					case'ini':
					case'txt':{
						$filetype = 'text';
						$color = 'c-666';
						$url = 'javascript:void(0)';
						break;
					}
					default:{
						$filetype = 'text';
						$color = 'c-666';
						$url = 'javascript:void(0)';
						break;
					}
				}
				$html .= '
						<li class="file'.$rj.$j.'">
							<table class="tbl" cellspacing="0" cellpadding="3" border="0">
								<tr>
									<td width="10"><span class="iconblue icon-document" title="File '.$filetype.'"></span></td>
									<td><a href="'.$url.'"'.($filetype=='image'||$filetype=='font'||$filetype=='pdf'?' target="_blank"':' onclick="Editor(\''.$rj.$j.'\',\'type=ftpsave&pid='.$p['id'].'&act=edit&file='.$path.'/'.$v.'&j='.$rj.$j.'&level='.$level.'\',\''.$filetype.'\',\''.$p['id'].'\',\''.$path.'/'.$v.'\',this,\''.$level.'\',\''.$v.'\');"').' class="file'.$rj.$j.' '.$color.' fs-14">'.$v.'</a></td>
									<td width="150" class="fs-12 ta-r ff-c">
										'.$filetype.'
									</td>
									<td width="150" class="fs-12 ta-r ff-c">
										'.number_format($fsize[$v],0,'',' ').' bytes
									</td>
									<td width="100" class="fs-12 ta-r ff-c">
										'.$fdate[$v].'
									</td>
									<td width="100">
										<span class="btn f-r p-2" style="margin:0 2px 0 0;" onclick="if(confirm(\'Are You sure You want to delete this file?\')){jQuery(\'.file'.$rj.$j.'\').hide();Page(\'ftpsave&pid='.$p['id'].'&act=delete&act2=file&path='.$path.'/'.$v.'\',\'h\');}"><span class="iconred icon-trash" title="Delete"></span></span>
										<span class="btn f-r p-2 d-n" style="margin:0 2px 0 0;" onclick="Page(\'ftpsave&act=copy&pid='.$p['id'].'&path='.$path.'&j='.$rj.'&level='.($level+1).'&name='.$v.'\',\'0\');"><span class="iconorange icon-copy" title="Copy"></span></span>
										<span class="btn f-r p-2" style="margin:0 2px 0 0;" onclick="Page(\'ftpsave&act=rename&pid='.$p['id'].'&path='.$path.'&j='.$rj.'&level='.($level+1).'&name='.$v.'\',\'0\');"><span class="iconblue icon-pencil" title="Rename"></span></span>
									</td>
								</tr>
							</table>
							<div id="file'.$rj.$j.'" style="padding:0 0 0 0px;display:none;"></div>
						</li>
					';
				$j++;
			}
			$html .= '</ul>';
			// foreach($contents as $k=>$v){
				// if(is_file($v)){
					// $html .= $v.'<br />';
				// }
			// }
			// echo '<pre>';print_r($contents);echo '</pre>';
			// var_dump($contents);
		}
		else{
			$html .= $this->info('alert','<b>Connection error!</b>');
			// exit; 
		} 
		// close the connection 
		ftp_close($conn_id);
		return $html;
	}
	function ftpsave(){
		$act = $this->r('act');
		$path = $this->r('path');
		$file = str_replace('[-amp-]','&',str_replace('[-p-]','+',$this->r('file')));
		$j = $this->r('j');
		$level = $this->r('level');
		$remotefile = $this->r('remotefile');
		// $conn_id = $this->r('conn_id');
		$html = '';
		$p = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE id=".$this->r('pid').""));
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
			switch($act){
				case'edit':{
					// $html .= '
						// <div class="o-h bc-lgrey">
							// <span class="btn f-l p-7" onclick="EditorSave(\''.$j.'\',\''.$p['id'].'\',\''.$file.'\',\'\');"><span class="iconlblue icon-disk" title="Save"></span></span>
							// <span class="btn f-r p-7" onclick="jQuery(\'#file'.$j.'\').hide();jQuery(\'.file'.$j.'\').removeClass(\'fw-b td-n\');jQuery(\'body\').removeClass(\'o-h\');"><span class="icon3 icon-close" title="Close"></span></span>
							// <span class="btn f-r p-7" onclick="jQuery(\'#file'.$j.'\').toggleClass(\'expand\');if(jQuery(\'body\').hasClass(\'o-h\')){jQuery(\'body\').removeClass(\'o-h\');}else{jQuery(\'body\').addClass(\'o-h\');}"><span class="icon3 icon-arrow-4-diag" title="Expand"></span></span>
						// </div>
						// <div id="filesave'.$j.'" style="position:absolute;"></div>
						// <div id="filein'.$j.'" class="filein" wrap="off" style="width:'.(770-$level*20).'px;height:300px;font-family:Courier;resize:none;">';
					
					// iconv_set_encoding("internal_encoding", "UTF-8");
					// iconv_set_encoding("output_encoding", "UTF-8");
					// ob_start("ob_iconv_handler");
					ob_start();
					$result = ftp_get($conn_id,"php://output", $file, FTP_BINARY);
					$data = ob_get_contents();
					ob_end_clean();
					// $html .= mb_detect_encoding(htmlentities($data),"UTF-8")!="UTF-8"?utf8_encode(htmlentities($data)):htmlentities($data);
					if(mb_detect_encoding($data)=='UTF-8'){
						// $html .= htmlentities($data);
						$html .= htmlentities($data, ENT_QUOTES, 'UTF-8');
					}
					else{
						$html .= utf8_encode(htmlentities($data));
					}
					// $html .= htmlentities(mb_convert_encoding($data,mb_detect_encoding($data),'UTF-8'));
					// $html .= '</div>';
					
					break;
				}
				case'save':{
					$tempHandle = fopen('php://temp', 'r+');
					fwrite($tempHandle, $file);
					rewind($tempHandle);
					if(ftp_fput($conn_id,$remotefile,$tempHandle,FTP_ASCII)){
						// $html .= strlen($text);
						$info = $this->lng['Saved'].'!';
						$html .= $this->info('info',$info,'fixed');
					}
					else{
						$info = 'Not saved, error!';
						$html .= $this->info('alert',$info,'fixed');
						// exit; 
					}
					
					break;
				}
				case'create':{
					switch($this->r('act2')){
						case'save':{
							$info = '';
							switch($this->r('ftype')){
								case'file':{
									// $ftp_path = '';
									$file = $path.'/'.$this->r('name');
									// $file = $this->r('name').'.'.$this->r('filetype');
									$fp = tmpfile();
									// $html .= $file;//ftp_put($conn_id, $remote_file, $file, FTP_ASCII)
									if(ftp_fput($conn_id,$file,$fp,FTP_ASCII)){
										$info .= '<p class="c-green2">File <b>'.$file.'</b> created successfuly!</p>';
									}
									else{
										$info .= '<p class="c-f00">Error! File Not created!</p>';
									}
									break;
								}
								case'folder':{
									if(ftp_mkdir($conn_id,$path.'/'.$this->r('name'))){
										$info .= '<p class="c-green2">Folder <b>'.$this->r('name').'</b> created successfuly!</p>';
									}
									else{
										$info .= '<p class="c-f00">Error! Folder Not created!</p>';
									}
									break;
								}
							}
							$html .= $this->info('info',$info);
							$html .= $this->ftp($path);
							break;
						}
						default:{
							$html = '
								<h2>Create in <b>'.$path.'</b></h2>
								<form id="formedit" action="javascript:void(0)" onsubmit="FormDebug(this,e(\'folder'.$p['id'].''.$this->r('j').'\'));Remove(e(\'messwindowin'.$this->r('num').'\'));">
									<input type="hidden" name="type" value="ftpsave" />
									<input type="hidden" name="act" value="create" />
									<input type="hidden" name="act2" value="save" />
									<input type="hidden" name="path" value="'.$path.'" />
									<input type="hidden" name="level" value="'.$this->r('level').'" />
									<input type="hidden" name="j" value="'.$this->r('j').'" />
									<input type="hidden" name="pid" value="'.$p['id'].'" />
									<table border="0" cellspacing="0" cellpadding="10">
										<tr>
											<td><select name="ftype" style="width:100px!important;" onchange="if(this.value==\'file\'){jQuery(\'select.selectfiletype'.$this->r('num').'\').show();}else{jQuery(\'select.selectfiletype'.$this->r('num').'\').hide();}">
													<option value="file">File</option>
													<option value="folder">Folder</option>
												</select>
												<input type="text" name="name" value="" />
											</td>
										</tr>
									</table>
								</form>
								<div style="width:100px;margin:0 auto;">
									<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green" onclick="jQuery(\'#formedit\').submit()"><span class="icon0 icon-check" title="Ок"></span></span>
									<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="Cancel"></span></span>
								</div>
								';
							break;
						}
					}
						
						
					break;
				}
				case'rename':{
					switch($this->r('act2')){
						case'save':{
							ftp_rename($conn_id,$path.'/'.$this->r('old'),$path.'/'.$this->r('name'));
							$html .= $this->ftp($path);
							break;
						}
						default:{
							
							$html = '
								<h2>Rename <b>'.$this->r('name').'</b> to</h2>
								<form id="formedit" action="javascript:void(0)" onsubmit="FormDebug(this,e(\'folder'.$p['id'].''.$this->r('j').'\'));Remove(e(\'messwindowin'.$this->r('num').'\'));">
									<input type="hidden" name="type" value="ftpsave" />
									<input type="hidden" name="act" value="rename" />
									<input type="hidden" name="act2" value="save" />
									<input type="hidden" name="old" value="'.$this->r('name').'" />
									<input type="hidden" name="path" value="'.$path.'" />
									<input type="hidden" name="level" value="'.$this->r('level').'" />
									<input type="hidden" name="j" value="'.$this->r('j').'" />
									<input type="hidden" name="pid" value="'.$p['id'].'" />
									<table border="0" cellspacing="0" cellpadding="10">
										<tr>
											<td>
												<input type="text" name="name" value="'.$this->r('name').'" />
											</td>
										</tr>
									</table>
								</form>
								<div style="width:100px;margin:0 auto;">
									<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green" onclick="jQuery(\'#formedit\').submit()"><span class="icon0 icon-check" title="Ок"></span></span>
									<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="Cancel"></span></span>
								</div>
								';
							break;
						}
					}
						
						
					break;
				}
				case'upload':{
					switch($this->r('act2')){
						case'save':{
							$html .= $this->r('files');
							$files = $this->r('files');
							for($i=0;$i<$files;$i++){
								// $file = fopen($this->r('file'),'r');
								ftp_put($conn_id,$path.'/'.$this->r('name').'/'.$this->r('file'.$i),$this->r('file'.$i),FTP_ASCII);
							}
							
							$html .= $this->ftp($path.'/'.$this->r('name'));
							break;
						}
						default:{
							
							$html = '<div class="files">
									<iframe src="'.$this->site.'php/file_ftp.php?pid='.$p['id'].'&uid='.$this->uid.'&name='.$this->r('name').'&path='.$path.'&j='.$this->r('j').'" width="380" height="300" frameborder="0"></iframe>
								</div>
								<div style="width:100px;margin:0 auto;">
									<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'));"><span class="iconf icon-closethick" title="Cancel"></span></span>
								</div>
								';
							break;
						}
					}
						
						
					break;
				}
				case'copy':{
					switch($this->r('act2')){
						case'save':{
							ftp_rename($conn_id,$path.'/'.$this->r('old'),$path.'/'.$this->r('name'));
							$html .= $this->ftp($path);
							break;
						}
						default:{
							$html = '
								<h2>Copy <b>'.$this->r('name').'</b> to</h2>
								<form id="formedit" action="javascript:void(0)" onsubmit="FormDebug(this,e(\'folder'.$p['id'].''.$this->r('j').'\'));Remove(e(\'messwindowin'.$this->r('num').'\'));">
									<input type="hidden" name="type" value="ftpsave" />
									<input type="hidden" name="act" value="copy" />
									<input type="hidden" name="act2" value="save" />
									<input type="hidden" name="old" value="'.$this->r('name').'" />
									<input type="hidden" name="path" value="'.$path.'" />
									<input type="hidden" name="level" value="'.$this->r('level').'" />
									<input type="hidden" name="j" value="'.$this->r('j').'" />
									<input type="hidden" name="pid" value="'.$p['id'].'" />
									<table border="0" cellspacing="0" cellpadding="10">
										<tr>
											<td>
												';
							
							$html .= '<p><input name="new" type="radio" value="/" /> /</p>';
							$html .= $this->ftplist($conn_id,'',$p['id']);
							$html .= '
											</td>
										</tr>
									</table>
								</form>
								<div style="width:100px;margin:0 auto;">
									<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green" onclick="jQuery(\'#formedit\').submit()"><span class="icon0 icon-check" title="Ок"></span></span>
									<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="Cancel"></span></span>
								</div>
								';
							break;
						}
					}
					break;
				}
				case'delete':{
					switch($this->r('act2')){
						case'folder':{
							ftp_rmdir($conn_id,$path);
							break;
						}
						case'file':{
							ftp_delete($conn_id,$path);
							break;
						}
					}
				}
				default:{
					
					break;
				}
			}
		}
		else{
			$html .= 'Connection error'; 
			// exit; 
		}
		return $html;
	}
	function ftp_copy($conn_distant , $pathftp , $pathftpimg ,$img){
        if(ftp_get($conn_distant, TEMPFOLDER.$img, $pathftp.'/'.$img ,FTP_BINARY)){
			if(ftp_put($conn_distant, $pathftpimg.'/'.$img ,TEMPFOLDER.$img , FTP_BINARY)){
				unlink(TEMPFOLDER.$img) ;                                              
			}
			else{                               
				return false; 
			} 
		}
		else{
			return false;
		}
		return true;
	}
	function ftplist($conn_id=0,$path='',$pid=0){
		$html = '';//echo '1';
		if($conn_id==0){$html .= '2';
			$p = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE id=".$this->r('pid').""));
			$pid = $p['id'];
			$path = $this->r('path');
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
		}
		$folders = array();
		$files = array();
		$fsize = array();
		$list = ftp_rawlist($conn_id,'/'.$path.'');
			for($i=0;$i<sizeof($list);$i++){
				list($permissions,$next) = explode(" ",$list[$i],2);
				list($num,$next) = explode(" ",trim($next),2);
				list($owner,$next) = explode(" ",trim($next),2);
				list($group,$next) = explode(" ",trim($next),2);
				list($size,$next) = explode(" ",trim($next),2);
				list($month,$next) = explode(" ",trim($next),2);
				list($day,$next) = explode(" ",trim($next),2);
				list($year_time,$filename) = explode(" ",trim($next),2);
				if($filename!="."&&$filename!=".."){
					if(substr($permissions,0,1)=="d"){
						$folders[] = $filename;
						$html .= '<p><input name="new" type="radio" value="'.$path.'/'.$filename.'" /> <a href="javasript:void(0)" onclick="Page(\'ftplist&pid='.$pid.'&path='.$path.'/'.$filename.'\',e(\'fldr'.$pid.''.str_replace(' ','_',str_replace('.','_',str_replace('/','_',$path.'/'.$filename))).'\'))">'.$path.'/'.$filename.'</a></p>';
						$html .= '<div id="fldr'.$pid.''.str_replace(' ','_',str_replace('.','_',str_replace('/','_',$path.'/'.$filename))).'"></div>';
						// $html .= $this->ftplist($conn_id,'/'.$path.$filename);
					} 
					else {
						$files[] = $filename;
						$fsize[$filename] = $size;
					}
				}
			}
		return $html;
	}
	function sites($act=''){
		$html = '';
		$act = $this->r('act')==''?$act:$this->r('act');
		$act2 = $this->r('act2');
		switch($act){
			case'download':{
				// $server_ip="web-help-me.com";
				// $server_login="admin";
				// $server_pass="3pZyfZ28";
				// $server_ssl="N";
				
				// $furl = '195.64.154.77';
				// $fuser = 'admin';
				// $fpass = '3pZyfZ28';
				
				$id = $this->r('aid');
				$html .= '<h2>Download Site</h2><br />';
				$html .= '<h3>Files backups</h3>';
				$html .= '<a href="javascript:void(0)" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'));Page(\'sites&act=createfilesbackup&aid='.$id.'\',\'0\',\'\');">Create files backup</a><hr />';
				$i = 1;
				if($handle = opendir('/home/admin/domains/web-help-me.com/public_html/sites/'.$id)) {
					while(false !== ($entry = readdir($handle))) {
						if($entry != "." && $entry != ".."&&(substr($entry,0,9)=='sitefiles'&&substr($entry,-4)=='.zip')) {
							$html .= $i.' <a href="/home/admin/domains/web-help-me.com/public_html/sites/'.$id.'/'.$entry.'">'.$entry.'</a><br />';
							$i++;
						}
					}
					closedir($handle);
				}
				else{
					$html .= 'No backup files';
				}
				$html .= '<br /><br />';
				$html .= '<h3>Database backups</h3>';
				$html .= '<a href="javascript:void(0)" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'));Page(\'sites&act=createdatabasebackup&aid='.$id.'\',\'0\',\'\');">Create database backup</a><hr />';
				$i = 1;
				if($handle = opendir('/home/admin/domains/web-help-me.com/public_html/sites/'.$id)) {
					while(false !== ($entry = readdir($handle))) {
						if($entry != "." && $entry != ".."&&(substr($entry,0,12)=='sitedatabase'&&substr($entry,-4)=='.sql')) {
							$html .= $i.' <a href="/home/admin/domains/web-help-me.com/public_html/sites/'.$id.'/'.$entry.'">'.$entry.'</a><br />';
							$i++;
						}
					}
					closedir($handle);
				}
				else{
					$html .= 'No backup database';
				}
				
				break;
			}
			case'createfilesbackup':{
				$id = $this->r('aid');
				$num = $this->r('num');
				
				$this->zzip('/home/admin/domains/web-help-me.com/public_html/sites/'.$id,'/home/admin/domains/web-help-me.com/public_html/sites/'.$id.'/sitefiles'.date('Y-m-d-H-i-s').'.zip');
				
				$_REQUEST = array();
				$_REQUEST['aid'] = $id;
				$_REQUEST['num'] = $num;
				$html = $this->info('info','<strong>Created!</strong> Files Backup is Created.').$this->sites('download');
				break;
			}
			case'installcms':{
				switch($act2){
					case'install':{
						$html = 'Installing...';
						$html .= '<br />'.$this->r('cms');
						$html .= '<br />'.$this->r('aid');
						$id = $this->r('aid');
						$site = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE type='site' AND id='".$id."'"));
						unlink('/home/admin/domains/web-help-me.com/public_html/sites/'.$id.$site['level'].'/index.php');
						$name = $id;
						$zip = new ZipArchive;
						if($zip->open('/home/admin/domains/web-help-me.com/public_html/php/files/cms/'.$this->r('cms').'.zip')===true){
							$zip->extractTo('/home/admin/domains/web-help-me.com/public_html/sites/'.$id.$site['level'].'/');
							$zip->close();
							// echo 'ok';
						}
						else{
							echo 'Extracted failed';
						}
						$this->rchmod('/home/admin/domains/web-help-me.com/public_html/sites/'.$id.$site['level'],0777);
						$html .= $this->info('info','<strong>Installed!</strong> CMS was installed.').$this->sites();
						break;
					}
					default:{
						$html = '<h2>Install CMS</h2><br />';
						$html .= '<form action="javascript:void(0)" id="forminstallcms" onsubmit="Remove(e(\'messwindowin'.$this->r('num').'\'));Load(e(\'content\'),\'type=sites&act=installcms&act2=install&cms=\'+getRadio(this.cms)+\'&aid='.$this->r('aid').'\');">';
						$html .= '<p><input type="radio" name="cms" value="joomla25" checked="checked" /> <label>Joomla 2.5</label></p>';
						$html .= '<p><input type="radio" name="cms" value="joomla15" /> <label>Joomla 1.5</label></p>';
						$html .= '<br />';
						$html .= '<span id="formaddsubmit" class="btn f-l p-10 m-5 bg-green" onclick="jQuery(\'#forminstallcms\').submit()"><span class="icongreen icon-check" title="Ок"></span></span>
							<span class="btn f-l p-10 m-5 bg-red" onclick="Remove(e(\'messwindowin'.$this->r('num').'\'))"><span class="iconf icon-closethick" title="Cancel"></span></span>';
						$html .= '</form>';
						break;
					}
				}
				
				break;
			}
			case'createdatabasebackup':{
				$id = $this->r('aid');
				$num = $this->r('num');
				
				$site = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE type='site' AND id='".$id."'"));
				$database = explode('MySQL',$site['contacts']);
				$host = explode('хост:',$database[1]);
				$host1 = explode(' ',$host[1]);
				$host = strip_tags($host1[1]);
				$db = explode('база:',$database[1]);
				$db1 = explode(' ',$db[1]);
				$db = strip_tags($db1[1]);
				$user = explode('пользователь:',$database[1]);
				$user1 = explode(' ',$user[1]);
				$user = strip_tags($user1[1]);
				$pass = explode('пароль:',$database[1]);
				$pass1 = explode(' ',$pass[1]);
				$pass = strip_tags($pass1[1]);
				
				$this->backup_tables($id,$host,$user,$pass,$db);
				// $html .= '<p>'.$host.'</p>';
				// $html .= '<p>'.$db.'</p>';
				// $html .= '<p>'.$user.'</p>';
				// $html .= '<p>'.$pass.'</p>';
				
				
				
				/* DBC($host,$db,$user,$pass);
				
				$result = $this->q("SHOW TABLES FROM ".$db);
				
				$html .= '<textarea style="height:300px;">';
				
				$sql = '';
				$sql .= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";';
				$sql .= 'SET time_zone = "+00:00";';
				while ($row = mysql_fetch_row($result)) {
					$result2 = $this->q("SELECT * FROM ".$row[0]." WHERE 1");
					$nbc = mysql_num_fields($result2);
					// $type	= mysql_field_type($result, $i);
					// $name	= mysql_field_name($result, $i);
					// $len	 = mysql_field_len($result, $i);
					// $flags = mysql_field_flags($result, $i);
					// echo $type . " " . $name . " " . $len . " " . $flags . "\n";
					$primary_key = '';
					$sql .= "CREATE TABLE IF NOT EXISTS `".$row[0]."` (";
					for($i=0;$i<$nbc;$i++){
						if($i>0){$sql .= ",\n";}
						$flag = explode(' ',mysql_field_flags($result2,$i));
						// if(strpos(mysql_field_flags($result2,$i),'primary_key')!== false){
							$f = $flag[0].' '.$flag[2];
						// }
						// else{
							// $f = implode(' ',$flag);
						// }
						$sql .= "`".mysql_field_name($result2,$i)."` ".mysql_field_type($result2,$i)." ".mysql_field_len($result2,$i)." ".$f."";
						if(strpos(mysql_field_flags($result2,$i),'primary_key')!== false){
							$sql .= ",PRIMARY KEY (`".mysql_field_name($result2,$i)."`)";
						}
					}
					
					$sql .= ") DEFAULT CHARSET=utf8;\n";
					
					while($r = mysql_fetch_assoc($result2)){
						$sql .= "INSERT INTO ".$row[0]." (";
						
						// $flags = mysql_field_flags($result2,0);
						// $f = explode(' ', $flags);
						
						// while($r = mysql_fetch_assoc($result2)){
						for($i=0;$i<$nbc;$i++){
							if($i>0){$sql .= ",";}
							// if(!isset($flags[$i])){$html .= "NULL";}
							$sql .= "'".mysql_field_name($result2,$i)."'";
						}
						// }
						$sql .= ") VALUES (";
						for($i=0;$i<$nbc;$i++){
							if($i>0){$sql .= ",";}
							// if(!isset($flags[$i])){$html .= "NULL";}
							$sql .= "'".mysql_real_escape_string($r[mysql_field_name($result2,$i)])."'";
						}
						// $html .= mysql_real_escape_string($r[]);
						$sql .= ");\n";
					}
					$sql .= "\n";
				}
				$html .= $sql.'</textarea>';
				
				
				
				
				
				 */
				$_REQUEST = array();
				$_REQUEST['aid'] = $id;
				$_REQUEST['num'] = $num;
				$html .= $this->info('info','<strong>Created!</strong> Database Backup is Created.').$this->sites('download');
				break;
			}
			case'delete':{
				$id = $this->r('aid');
				$site = mysql_fetch_assoc($this->q("SELECT * FROM jos_whm WHERE type='site' AND id='".$id."'"));
				
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
				
				$conn = ftp_connect($furl) or die('Not Working '.$furl);
				$login = ftp_login($conn,$fuser,$fpass);
				
				$sock->query('/CMD_API_DATABASES',array('action' => 'delete','username_dbname' => 'admin_'.$id));
				$result = $sock->fetch_body();
				$sock->query('/CMD_API_FTP',array('action' => 'delete','domain' => 'web-help-me.com','select0' => $id));
				$this->deletedir('/home/admin/domains/web-help-me.com/public_html/sites/'.$id.$site['level']);
				$_REQUEST = array();
				$this->q("DELETE FROM jos_whm WHERE type='site' AND id='".$id."'");
				$html .= $this->info('info','<strong>Deleted!</strong> Site http://sites.web-help-me.com/'.$id.$site['level'].'	deleted.').$this->sites('');
				break;
			}
			default:{
				$html = '';
				$html .= $this->info('info','<strong>Sorry!</strong> This section is under construction. Some functionality can be not working');
				$html .= '<h1>Sites</h1>
					<ul class="sites">';
				$all = $this->q("SELECT * FROM jos_whm WHERE type='site' AND user='".$this->uid."' ORDER BY id DESC");
				$y = 1;
				while($a = mysql_fetch_assoc($all)){
					$html .='
						<li'.($y==1?' class="c"':'').'>
							<table cellpadding="5" cellspacing="0" border="0" width="100%">
								<tr>
									<td width="10">
										<input type="checkbox" class="chbox" value="'.$a['id'].'" />
									</td>
									<td width="250">
										<a href="http://sites.web-help-me.com/'.$a['id'].$a['level'].'" target="_blank">sites.web-help-me.com/'.$a['id'].$a['level'].'</a>
									</td>
									<td>
										<span class="btn f-r p-1" onclick="if(confirm(\'Вы уверенны, что хотите удалить этот site?\')){Load(e(\'content\'),\'type=sites&act=delete&aid='.$a['id'].'\')}"><span class="iconred icon-close" title="Delete a site"></span></span>
										<span class="btn f-r p-1" onclick="Page(\'sites&act=download&aid='.$a['id'].'\',\'0\',\'\');"><span class="icongreen icon-arrowreturnthick-1-s" title="Download the site"></span></span>
										<span class="btn f-r p-1" onclick="Page(\'sites&act=installcms&aid='.$a['id'].'\',\'0\',\'\');"><span class="iconlblue icon-arrowthickstop-1-s" title="Install CMS"></span></span>
										<span class="btn f-r p-1" onclick="jQuery(\'#data'.$a['id'].'\').toggle(250)"><span class="iconlblue icon-info" title="Info"></span></span>
									</td>
								</tr>
								<tr id="data'.$a['id'].'" style="display:none;">
									<td colspan="4" class="fs-12">'.$a['contacts'].'</td>
								</tr>
							</table>
						</li>';
					$y = 1-$y;
				}
				$html .= '</ul>';
				break;
			}
		}
		
		return $html;
	}
	function deletedir($dir){
		 if (substr($dir, strlen($dir)-1, 1) != '/') 
			 $dir .= '/'; 

		 // echo $dir; 

		 if ($handle = opendir($dir)) 
		 {
			 while ($obj = readdir($handle)) 
			 {
				 if ($obj != '.' && $obj != '..') 
				 {
					 if (is_dir($dir.$obj)) 
					 {
						 if (!deleteDir($dir.$obj)) 
							 return false; 
					 } 
					 elseif (is_file($dir.$obj)) 
					 {
						 if (!unlink($dir.$obj)) 
							 return false; 
					 } 
				 } 
			 } 

			 closedir($handle); 

			 if (!@rmdir($dir)) 
				 return false; 
			 return true; 
		 } 
		 return false; 
	}
	function rchmod($mypath,$uid=755,$gid=0){
		$d = opendir ($mypath) ;
		while(($file = readdir($d)) !== false) {
			if ($file != "." && $file != "..") {
				$typepath = $mypath . "/" . $file ;
				if (filetype ($typepath) == 'dir') {
					$this->rchmod($typepath, $uid, $gid);
				}
				chmod($typepath,$uid);
			}
		}
	}
}
?>