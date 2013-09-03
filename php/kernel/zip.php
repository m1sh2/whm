<?php
require_once('base.php');

class Zipper extends ZipArchive { 
    // function __construct(){
		// parent::__construct();
	// }
	public function addDir($path) {
		print 'adding ' . $path . '<br>';
		$this->addEmptyDir($path);
		$nodes = glob($path . '/*');
		foreach ($nodes as $node) {
			print $node . '<br>';
			if (is_dir($node)) {
				$this->addDir($node);
			} else if (is_file($node))  {
				$this->addFile($node);
			}
		}
	}
}
?>