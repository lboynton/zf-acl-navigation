<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AclManager
 *
 * @author Lee Boynton <lee@lboynton.com>
 */
class App_Controller_Plugin_AclManager extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Default user role if user is not logged in or an invalid role is found in
	 * the database
	 * @var string Name indicating the role of the user (guest/member/admin)
	 */
	private $_defaultRole = 'guest';

	/**
	 * The action to dispatch if a user doesn't have sufficient privileges
	 * @var array Array containing the controller and action to dispatch
	 */
	private $_authController = array('module' => 'default', 
		'controller' => 'user', 'action' => 'login');

	public function __construct(Zend_Auth $auth)
	{
		$this->auth = $auth;
		$this->acl = new Zend_Acl();

		// add the different user roles
		$this->acl->addRole(new Zend_Acl_Role($this->_defaultRole));
		$this->acl->addRole(new Zend_Acl_Role('client'), $this->_defaultRole);
		$this->acl->addRole(new Zend_Acl_Role('admin'), array('client'));

		// default module
		$this->acl->add(new Zend_Acl_Resource('user'));
		$this->acl->add(new Zend_Acl_Resource('index'));
		$this->acl->add(new Zend_Acl_Resource('error'));

		// admin module
		$this->acl->add(new Zend_Acl_Resource('admin'));
		$this->acl->add(new Zend_Acl_Resource('admin:user'), 'admin');

		// deny everything by default, explicitly allow access to resources and privileges
		$this->acl->deny()
			->allow('guest', 'user', 'login')
			->allow('guest', 'user', 'register')
			->allow('guest', 'index')
			->allow('guest', 'error')
			->allow('client', 'user')
			->allow('admin', 'admin')
			->deny('client', 'user', 'login')
			->deny('client', 'user', 'register')
			->deny('admin', 'user', 'index');;
	}

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		// get role of user
		$role = $this->getRole();

		// the ACL resource is the requested controller name
		$resource = $request->controller;

		if($request->module != 'default')
		{
			$resource = $request->module . ':' . $resource;
		}

		// the ACL privilege is the requested action name
		$privilege = $request->action;

		// if we haven't explicitly added the resource, check the default global
		// permissions
		if (!$this->acl->has($resource))
		{
			$resource = null;
		}

		// access denied - reroute the request to the default action handler
		if (!$this->acl->isAllowed($role, $resource, $privilege))
		{
			$request->setModuleName($this->_authController['module']);
			$request->setControllerName($this->_authController['controller']);
			$request->setActionName($this->_authController['action']);
		}
	}

	public function getAcl()
	{
		return $this->acl;
	}

	public function getRole()
	{
		if ($this->auth->hasIdentity())
		{
			$role = $this->auth->getIdentity()->getUserLevel();
		}
		else
		{
			$role = $this->_defaultRole;
		}

		if (!$this->acl->hasRole($role))
		{
			$role = $this->_defaultRole;
		}

		return $role;
	}
}
