<?php
require_once('base.php');

class mkp extends base{
	function __construct(){
		parent::__construct();
	}
	function mkp(){
		// print_r($_REQUEST);
		switch($this->r('act')){
			case'save':{
				$html = '<h1>Makeup</h1>';
				// $html .= implode('-=-',$_REQUEST).'<br />';
				// foreach($_REQUEST as $k=>$v){
					// echo '<p>'.$k.' '.$v.'</p>';
				// }
				$s = explode(',',$this->r('s'));
				// $a = array();
				$div = '';
				$divhtml = '';
				$imagesrc = str_replace('url(','',str_replace(')','',$this->r('image')));
				// $html .= $imagesrc;
				$image1 = explode('/',$imagesrc);
				$image2 = explode('.',$image1[count($image1)-1]);
				$image = imagecreatefromjpeg($imagesrc);
				imagecolorallocate($image, 255,255,255);
				$width = $this->r('width');
				$height = $this->r('height');
				
				$folder = 'asdat'.$image2[0];
				mkdir('../upload/'.$folder,0755);
				mkdir('../upload/'.$folder.'/images',0755);
				mkdir('../upload/'.$folder.'/css',0755);
				$style = "*{outline:none;position:relative;}\n";
				$style .= "body{font:14px Arial, Helvetica, sans-serif;background:#ccc;line-height:20px;padding:0;margin:0;}\n";
				$style .= "h1,h2,h3,h4,h5,h6{color:#800040;margin-top:0px;padding:10px 0 5px;border-bottom:1px solid #ccc;}\n";
				$style .= "a{text-decoration:underline;color:#f00;}\n";
				$style .= "a:hover{text-decoration:none;color:#333;}\n";
				$style .= "p{padding:0;margin:0 0 10px;}\n";
				$style .= ".page{margin:0 auto;border:0px solid #333;display:block;width:".$width."px;min-height:".$height."px;overflow:hidden;}\n";
				$pos = "";
				for($i=0;$i<count($s);$i++){
					$a = explode('|',$s[$i]);
					// $img = imagecreatefromjpeg($_GET['src']);
					
					$filename = ''.$image2[0].'-'.$i.'.jpg';
					$filenamesrc = '../upload/'.$folder.'/images/'.$filename;

					$thumb_width = 200;
					$thumb_height = 150;

					// $iwidth = imagesx($image);
					// $iheight = imagesy($image);

					// $original_aspect = $width/$height;
					// $thumb_aspect = $thumb_width/$thumb_height;

					// if($original_aspect>=$thumb_aspect){
					   // If image is wider than thumbnail (in aspect ratio sense)
					   // $new_height = $thumb_height;
					   // $new_width = $width / ($height / $thumb_height);
					// }
					// else{
					   // If the thumbnail is wider than the image
					   // $new_width = $thumb_width;
					   // $new_height = $height / ($width / $thumb_width);
					// }

					$thumb = imagecreatetruecolor( $a[0], $a[1] );
					imagecolorallocate($thumb, 255,255,255);
					$bg = imagecolorat($thumb, 0, 0);
					// Set the backgrund to be blue
					imagecolorset($thumb, $bg, 255, 255, 255);
					// Resize and crop
					// $thumb = imagecrop($image,array($a[3],$a[2],$a[0],$a[1]));
					imagecopyresampled($thumb,
									   $image,
									   0,
									   0,
									   $a[3],
									   $a[2],
									   $a[0],
									   $a[1],
									   $a[0],
									   $a[1]);
					imagejpeg($thumb, $filenamesrc, 100);
					$div .= "<div class=\"block".$i."\"><jdoc:include type=\"modules\" name=\"pos".$i."\" style=\"xhtml\" /></div>\n";
					$divhtml .= "<div class=\"block".$i."\"></div>\n";
					$pos .= "<position>pos".$i."</position>/n";
					$style .= ".block".$i."{border:0px solid #333;display:block;width:".$a[0]."px;height:".$a[1]."px;position:relative;float:left;background:url(../images/".$filename.") no-repeat 0 0;}\n";
				}
				
				$div = "<?php
defined('_JEXEC') or die;
?>
<!DOCTYPE html>
<html>
	<head>
		<jdoc:include type=\"head\" />
		<link href=\"".'<?php echo $this->baseurl ?>'."/templates/asdat".$image2[0]."/images/favicon.ico\" rel=\"shortcut icon\" />
		<link type=\"text/css\" rel=\"stylesheet\" media=\"all\" href=\"".'<?php echo $this->baseurl ?>'."/templates/asdat".$image2[0]."/css/style.css\" />
	</head>
	<body>
		<div class=\"page\">".$div."</div>
	</body>
</html>";
				$divhtml = "<!DOCTYPE html>
<html>
	<head>
		<link href=\"images/favicon.ico\" rel=\"shortcut icon\" />
		<link type=\"text/css\" rel=\"stylesheet\" media=\"all\" href=\"css/style.css\" />
	</head>
	<body>
		<div class=\"page\">".$divhtml."</div>
	</body>
</html>";
				
				$ajax = "<?php\n
defined('_JEXEC') or die;\n
?>\n
<jdoc:include type=\"component\" />";
				$templateDetails = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<!DOCTYPE install PUBLIC \"-//Joomla! 2.5//DTD template 1.0//EN\" \"http://www.joomla.org/xml/dtd/2.5/template-install.dtd\">
<extension version=\"3.0\" type=\"template\" client=\"site\">
	<name>asdat".$image2[0]."</name>
	<version>1.0</version>
	<creationDate>07.01.2013</creationDate>
	<author>Misha-Sasha</author>
	<authorEmail>info@asdat.org</authorEmail>
	<copyright>Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.</copyright>
	<description>TPL_ASDAT_XML_DESCRIPTION</description>
	<files>
		<filename>ajax.php</filename>
		<filename>favicon.ico</filename>
		<filename>index.php</filename>
		<filename>templateDetails.xml</filename>
		<folder>css</folder>
		<folder>html</folder>
		<folder>images</folder>
	</files>
	<positions>".$pos."</positions>
	<config>
		<fields name=\"params\">
			<fieldset name=\"advanced\">
				<field name=\"templateColor\" class=\"\" type=\"color\" default=\"#08C\"
					label=\"TPL_ASDAT_COLOR_LABEL\"
					description=\"TPL_ASDAT_COLOR_DESC\" />

				<field name=\"templateBackgroundColor\" class=\"\" type=\"color\" default=\"#F4F6F7\"
					label=\"TPL_ASDAT_BACKGROUND_COLOR_LABEL\"
					description=\"TPL_ASDAT_BACKGROUND_COLOR_DESC\" />

				<field name=\"logoFile\" class=\"\" type=\"media\" default=\"\"
					label=\"TPL_ASDAT_LOGO_LABEL\"
					description=\"TPL_ASDAT_LOGO_DESC\" />
					
				<field name=\"sitetitle\"  type=\"text\" default=\"\"
					label=\"JGLOBAL_TITLE\"
					description=\"JFIELD_ALT_PAGE_TITLE_LABEL\"
					filter=\"string\" />

				<field name=\"sitedescription\"  type=\"text\" default=\"\"
					label=\"JGLOBAL_DESCRIPTION\"
					description=\"JGLOBAL_SUBHEADING_DESC\"
					filter=\"string\" />

				<field name=\"googleFont\"
					type=\"radio\"
					class=\"btn-group\"
					default=\"1\"
					label=\"TPL_ASDAT_FONT_LABEL\"
					description=\"TPL_ASDAT_FONT_DESC\"
				>
					<option value=\"0\">JNO</option>
					<option value=\"1\">JYES</option>
				</field>

				<field name=\"googleFontName\" class=\"\" type=\"text\" default=\"Open+Sans\"
					label=\"TPL_ASDAT_FONT_NAME_LABEL\"
					description=\"TPL_ASDAT_FONT_NAME_DESC\" />

				<field name=\"fluidContainer\"
					type=\"radio\"
					class=\"btn-group\"
					default=\"0\"
					label=\"TPL_ASDAT_FLUID_LABEL\"
					description=\"TPL_ASDAT_FLUID_DESC\"
				>
					<option value=\"0\">TPL_ASDAT_STATIC</option>
					<option value=\"1\">TPL_ASDAT_FLUID</option>
				</field>
			</fieldset>
		</fields>
	</config>
</extension>
";
				file_put_contents('../upload/'.$folder.'/ajax.php',$ajax);
				file_put_contents('../upload/'.$folder.'/index.html',$divhtml);
				file_put_contents('../upload/'.$folder.'/templateDetails.xml',$templateDetails);
				file_put_contents('../upload/'.$folder.'/index.php',$div);
				file_put_contents('../upload/'.$folder.'/css/style.css',$style);
				file_put_contents('../upload/'.$folder.'/css/style.css',$style);
				$path = realpath(substr(__DIR__,0,-10)).'/upload/'.$image1[count($image1)-1];
				unlink($path);
				$path = realpath(substr(__DIR__,0,-10)).'/upload/'.$image2[0].'';
				$arch = 'asdat'.$image2[0].'.zip';
				// echo $path.' '.$arch.'<br />';
				// $zip = new Zipper();
				// $zip->addDir($path);
				$this->zzip($path,$path.'/asdat.zip');
				// Zipper::addDir($path);
				// if($zip->open($path.'/'.$arch, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)===true){
					// $zip->addFile($path.'/ajax.php');
					// $zip->close();
					// echo 'ok';
				// }
				// else{
					// echo 'failed';
				// }
				// $zip->close();
				$html .= '<h2>Done!</h2>';
				$html .= '<p>Test preview below.</p>';
				$html .= '<iframe src="'.$this->site.'/upload/'.$folder.'/index.html" frameborder="1" width="'.$width.'" height="'.$height.'"></iframe>';
				
				break;
			}
			case'convert':{
				$html = '<h1>Makeup</h1>';
				// $html .= 'Conv!';
				foreach($_FILES['file']['name'] as $k=>$a){
					$t = explode('.',$a);
					$type = $t[count($t)-1];
					// header('Content-Type:'.$type);
					// header('Content-Length: ' . filesize($file));
					// header('Content-type: image/jpeg');
					// readfile($_FILES['file']['tmp_name'][$k]);
					
					// print_r(scandir(substr(__DIR__,0,-3) .'files\psd'));
					// echo $_FILES['file']['tmp_name'][$k];
					$file = count(scandir(substr(__DIR__,0,-10) .'upload')).'.'.$type;
					$target_path = substr(__DIR__,0,-10) .'upload/'.$file;
					if(move_uploaded_file($_FILES['file']['tmp_name'][$k],$target_path)){
						list($width, $height, $type, $attr) = getimagesize('upload/'.$file.'');
						$html .= '<div class="makeuppanel" style="display:block;height:27px;border:0px solid #333;">
								<span class="btn f-l" onclick="Makeup(\'square\')">Square</span>
								<span class="btn f-l" onclick="Makeup(\'save\')">Save</span>
								<ul>
									<li></li>
									<li></li>
								</ul>
							';
						
						$html .= '</div>';
						$html .= '<div id="makeupwindow" style="background:url(upload/'.$file.') no-repeat 0 0;border: 1px solid #333;width:'.$width.'px;height:'.$height.'px;">';
						// $html .= '<img src="" />';
						$html .= '</div>';
					}
					// else{
						// $html .= '<p class="error"></p>';
					// }
					// $file = substr(__DIR__,0,-3) .'files\psd'."\ " .$file;
					// header("Content-type: image/jpeg");
					// echo imagejpeg(imagecreatefrompsd(substr(__DIR__,0,-3) .'files\psd\\'.$file));
				}
				
				// echo '<pre>';print_r($_FILES);echo '</pre>';
				// echo "1or/img/1.jpg:<br />\n";
				
				// $exif = exif_read_data('1or/img/1.jpg', 'IFD0');
				// echo $exif===false ? "No header data found.<br />\n" : "Image contains headers<br />\n";

				// $exif = exif_read_data('1or/img/1.jpg', 0, true);
				// echo "1or/img/1.jpg:<br />\n";
				// foreach ($exif as $key => $section) {
					// foreach ($section as $name => $val) {
						// echo "$key.$name: $val<br />\n";
					// }
				// }
				// $image = 'my.psd';
				// $image = $_FILES['file']['tmp_name'];

				// $im = new Imagick( '1or/img/1.jpg' );
				// resize by 200 width and keep the ratio
				// $im->thumbnailImage( 200, 0);
				// write to disk
				// $im->writeImage( 'a_thumbnail.jpg' );
				break;
			}
			default:{
				$html = '<h1>Makeup</h1>';
				// $html .= '<iframe src="'.$this->siteurl.'1or/php/psd.php" width="680" height="300" frameborder="0"></iframe>';
				$html .= '<form enctype="multipart/form-data" method="post" action="'.$this->site.'makeup.html" id="formedit">
						<input type="hidden" name="type" value="makeup" />
						<input type="hidden" name="act" value="convert" />
						<input type="file" name="file[]" multiple onchange="handleFiles(this.files)" /> <input type="submit" value="Upload" class="btn bg-green br-20" />
					</form>';
				$path = realpath(substr(__DIR__,0,-10)).'/upload';
				// $html .= $path;
				$dir = scandir($path);
				$html .= '<h2>Already generated makeups</h2>';
				if(count($dir)){
					// print_r($dir);
					$html .= '<ul>';
					asort($dir);
					foreach($dir as $v){
						if(is_dir($path.'/'.$v)&&$v!='.'&&$v!='..'){
							$html .= '<li><a href="'.$this->site.'upload/'.$v.'/index.html" target="_blank">'.$v.'</a></li>';
						}
					}
					$html .= '</ul>';
				}
				else{
					$html .= '<h3>- Empty -</h3>';
				}
				break;
			}
		}
		
		return $html;
	}
}
?>