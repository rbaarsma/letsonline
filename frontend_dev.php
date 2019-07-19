<?php

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
{
  //die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once(dirname(__FILE__).'/symfony/config/ProjectConfiguration.class.php');

try {
	$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
}
catch (Exception $e)
{
	echo $e->getMessage();
	echo "<hr><pre>";
	var_dump($e);
	echo "</pre>";
}
sfContext::createInstance($configuration)->dispatch();


