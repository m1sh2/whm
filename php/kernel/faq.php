<?php
require_once('base.php');

class faq extends base{
	function __construct(){
		parent::__construct();
	}
	function faq(){
		$html = '';
		$html .= '<h1>'.$this->lng['faq'].'</h1>';
		$html .= '<ul class="faq">';
		$html .= '<li><a href="#start">Start</a></li>';
		$html .= '<li><a href="#menu">Menu</a></li>';
		$html .= '<li><a href="#sections">Sections</a></li>';
		$html .= '<li><a href="#projects">Projects</a><ul>';
		$html .= '<li><a href="#addproject">Add Project</a></li>';
		$html .= '<li><a href="#task">Task Items</a></li></ul></li>';
		$html .= '<li><a href="#finance">Finance</a><ul>';
		$html .= '<li><a href="#addtransaction">Add Transaction</a></li>';
		$html .= '<li><a href="#financeanalytics">Finance Analytics</a></li></ul>';
		$html .= '</ul>';
		$html .= '<p><a name="start"></a><h2>Start</h2><img src="img/faq/home.png" width="700" /></p>';
		$html .= '<p><a name="menu"></a><h2>Menu</h2><img src="img/faq/menu.png" width="700" /></p>';
		$html .= '<p><a name="sections"></a><h2>Sections</h2><img src="img/faq/section.png" width="700" /></p>';
		$html .= '<p><a name="projects"></a><h2>Projects</h2><img src="img/faq/projects.png" width="700" /></p>';
		$html .= '<p><a name="projects"></a><h3>Add Project</h3><img src="img/faq/addproject.png" width="700" /></p>';
		$html .= '<p><a name="task"></a><h3>Task Items</h3><img src="img/faq/taskitems.png" width="700" /></p>';
		$html .= '<p><a name="finance"></a><h2>Finance</h2><img src="img/faq/finance.png" width="700" /></p>';
		$html .= '<p><a name="addtransaction"></a><h3>Add Transaction</h3><img src="img/faq/addtransaction.png" width="700" /></p>';
		$html .= '<p><a name="financeanalytics"></a><h3>Finance Analytics</h3><img src="img/faq/fanalytics.png" width="700" /></p>';
		return $html;
	}
}
?>