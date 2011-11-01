<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected $aclManager;
	
	public function _initAutoloader()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
					'namespace' => 'Default_',
					'basePath' => APPLICATION_PATH . '/modules/default'
				));

		$autoloader = new Zend_Application_Module_Autoloader(array(
					'namespace' => 'Admin_',
					'basePath' => APPLICATION_PATH . '/modules/admin'
				));

		require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';

		$autoloader = Zend_Loader_Autoloader::getInstance();

		$appAutoloader = new \Doctrine\Common\ClassLoader('App');
		$autoloader->pushAutoloader(array($appAutoloader, 'loadClass'), 'App');
	}

	protected function _initSession()
	{
		Zend_Session::start();
	}
	
	protected function _initDoctype()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		$view->doctype('HTML5');
	}
	
	protected function _initAccessControl()
	{
		$front = Zend_Controller_Front::getInstance();
		$this->_aclManager = new App_Controller_Plugin_AclManager(Zend_Auth::getInstance());
		$front->registerPlugin($this->_aclManager);
	}
	
	protected function _initNavigation()
	{
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');
		
		$container = new Zend_Navigation($config);
		$view->navigation($container)
			->setAcl($this->_aclManager->getAcl())
			->setRole($this->_aclManager->getRole());
	}
	
	protected function _initFlashMessenger()
	{
		/** @var $flashMessenger Zend_Controller_Action_Helper_FlashMessenger */
		$flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');

		if ($flashMessenger->hasMessages())
		{
			$this->bootstrap('layout');
			$layout = $this->getResource('layout');
			$view = $layout->getView();
			$view->messages = $flashMessenger->getMessages();
		}
	}
}

