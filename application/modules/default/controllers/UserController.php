<?php

class Default_UserController extends Zend_Controller_Action
{

    /**
     * @var App\Application\Container\DoctrineContainer
     */
    protected $doctrine = null;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em = null;

    /**
     * @var App\Entity\Repository\UserRepository
     */
    protected $userRepository = null;

    /**
     * @var App_Auth_Adapter_Doctrine2
     */
    protected $authAdapter = null;

    public function init()
    {
        $this->doctrine = Zend_Registry::get('doctrine');
		$this->em = $this->doctrine->getEntityManager();
		$this->userRepository = $this->em->getRepository('\App\Entity\User');
		$this->authAdapter = new App_Auth_Adapter_Doctrine2($this->em, 
			'\App\Entity\User', 'username', 'password');
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
		$form = new Default_Form_Login();

		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($this->getRequest()->getPost()))
			{
				$auth = Zend_Auth::getInstance();
				$this->authAdapter->setIdentity($form->getValue('username'));
				$this->authAdapter->setCredential($form->getValue('password'));
				$result = $auth->authenticate($this->authAdapter);

				if ($result->isValid())
				{
					$auth->getStorage()->write($this->authAdapter->getEntityResult());
					$this->_helper->flashMessenger->addMessage('You are now logged in.');
					$this->_helper->userArea();
				}
				else
				{
					$description = "Sorry, that is an invalid username or password.";
					$form->setDescription($description);
				}
			}
			else
			{
				$form->setDescription('Sorry, we could not log you in with those details. Please check for any errors below.');
			}
		}

		$this->view->form = $form;
    }

    public function logoutAction()
    {
		Zend_Auth::getInstance()->clearIdentity();
		$this->_helper->redirector('login'); // back to login page
    }

    public function registerAction()
    {
		$form = new Default_Form_Register($this->em);

		if ($this->getRequest()->isPost())
		{
			if ($form->isValid($this->getRequest()->getPost()))
			{
				$user = new App\Entity\User();
				$this->userRepository->register($user, $form->getValues());
				$this->em->flush();

				$this->_helper->flashMessenger->addMessage('Thank you for registering, you may now log in.');
				$this->_helper->redirector('index', 'user');
			}
			else
			{
				$form->setDescription('Sorry, your account could not be created. Please check for any errors below.');
			}
		}

		$this->view->form = $form;
    }


}







