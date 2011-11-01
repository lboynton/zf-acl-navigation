<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Action helper for getting logged in user
 *
 * @author Lee Boynton <lee@lboynton.com>
 */
class App_Controller_Action_Helper_User extends Zend_Controller_Action_Helper_Abstract
{
	public function direct($merge = true)
	{
		$doctrine = Zend_Registry::get('doctrine');
		$em = $doctrine->getEntityManager();
		$user = Zend_Auth::getInstance()->getIdentity();
		
		if ($merge)
		{
			// make the user stored in the session managed again
			$user = $em->merge($user);
		}
		
		return $user;
	}
	
	public function id()
	{
		return Zend_Auth::getInstance()->getIdentity()->getId();
	}
	
	public function level()
	{
		return Zend_Auth::getInstance()->getIdentity()->getUserLevel();
	}
}
