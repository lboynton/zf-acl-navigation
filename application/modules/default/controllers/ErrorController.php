<?php

class Default_ErrorController extends Zend_Controller_Action
{

	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');

		if (!$errors)
		{
			$this->view->message = 'You have reached the error page';
			return;
		}
		
		if(get_class($errors->exception) == 'App_Exception')
		{
			$this->_forward('index', null, null, array('message' => $errors->exception->getMessage()));
		}

		switch ($errors->type)
		{
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Page not found';
				break;
			default:
				// application error
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'Application error';
				break;
		}
		
		$log = $this->getLog();

		// Log exception, if logger available and it is an app error
		if ($log && $this->getResponse()->getHttpResponseCode() == 500)
		{
			$log->crit($this->view->message . ' - ' . $errors->exception);
		}

		// conditionally display exceptions
		if ($this->getInvokeArg('displayExceptions') == true)
		{
			$this->view->exception = $errors->exception;
		}

		$this->view->request = $errors->request;
	}
	
	public function indexAction()
	{
		$this->view->message = $this->getRequest()->getParam('message');
	}

	public function getLog()
	{
		$bootstrap = $this->getInvokeArg('bootstrap');
		if (!$bootstrap->hasResource('Log'))
		{
			return false;
		}
		$log = $bootstrap->getResource('Log');
		return $log;
	}

}

