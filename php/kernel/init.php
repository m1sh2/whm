<?php
$kerneldir = scandir(__DIR__);

foreach($kerneldir as $k=>$v){
	if($v!='.'&&$v!='..'){
		include_once($v);
	}
}
?>