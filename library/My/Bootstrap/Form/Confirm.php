<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Confirm
 *
 * @author TUNDE
 */
class My_Bootstrap_Form_Confirm extends My_Bootstrap_Form{
	public function init(){
		$this->addElement('submit', 'ok', array(
			'label' => 'Okay',
			'ignore' => false
		))->addElement('submit', 'cancel', array(
			'label' => 'Cancel',
			'ignore' => false
		));
	}
}
