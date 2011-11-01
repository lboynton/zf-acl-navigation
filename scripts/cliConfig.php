<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment, (I am only running this in development)
defined('APPLICATION_ENV')
	|| define('APPLICATION_ENV', 'development');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Creating application
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

// Bootstrapping resources
$bootstrap = $application->bootstrap()->getBootstrap();
$bootstrap->bootstrap('Doctrine');

// Retrieve Doctrine Container resource
$container = $application->getBootstrap()->getResource('doctrine');

$em = $container->getEntityManager(getenv('EM') ?: $container->defaultEntityManager);
