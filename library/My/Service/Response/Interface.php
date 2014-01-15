<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
interface My_Service_Response_Interface {
	public function isSuccessful();
	
	public function getParams();
}
