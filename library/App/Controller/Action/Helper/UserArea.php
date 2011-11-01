<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserArea
 *
 * @author Lee Boynton <lee@lboynton.com>
 */
class App_Controller_Action_Helper_UserArea extends Zend_Controller_Action_Helper_Abstract
{
	public function direct()
	{
		$level = Zend_Auth::getInstance()->getIdentity()->getUserLevel();
		$redirector = Zend_Controller_Action_HelperBroker
			::getStaticHelper('Redirector');
		
		switch($level)
		{
			case 'admin':
				$redirector->direct('index', 'user', 'admin');
				break;
			
			default:
				$redirector->direct('index', 'user', 'default');
		}
	}
}
