<?php
class My_View_Helper_Translation extends Zend_View_Helper_Abstract
{
	public function translation(){
		$translation = new Zend_Session_Namespace('Translation');
		
		$langOptions = array(
			'Nigerian' => array(
				'en_GB' => 'British English',
				'yo_NG' => 'Yoruba',
				'ig_NG' => 'Igbo',
				'ha_NG' => 'Hausa',
			),
			'United Arab Emirates' => array(
				'ar_SA' => 'Arabic',
			),
			'Francophone' => array(
				'fr_FR' => 'French',
			)
		);
		
		$selectBox = '
         	<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
				  	<i class="icon-th icon-white"></i>
				    Language (English)
				    <b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li class="active"><a href="/system/languages/change/lang/en_GB"><b>English</b> <i class="icon-ok pull-right icon-white"></i></a>						
					</li>
					<li><a href="/system/languages/change/lang/ar_SA">Arabic</a></li>
					<li><a href="/system/languages/change/lang/fr_FR">French</a></li>
         		</ul>
         	</li>';
		
		$markup = $selectBox;
		
		return $markup;
	}
}
?>