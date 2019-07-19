<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
//die('De website is verhuisd en is daarmee tijdelijk offline. We hopen binnen 24 uur weer beschikbaar te zijn.');
require_once(dirname(__FILE__).'/symfony/config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
