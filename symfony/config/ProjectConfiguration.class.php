<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/autoload/sfCoreAutoload.class.php';

sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
	public function setup()
	{
		$this->enablePlugins('sfDoctrinePlugin', 'sfI18nFormExtractorPlugin');
		$this->enablePlugins('sfApplicationMapPlugin');
		$this->enablePlugins('sfThumbnailPlugin');
	}
  
	public function setRootDir($rootDir)
	{
		parent::setRootDir($rootDir);

		$this->setWebDir($rootDir . DIRECTORY_SEPARATOR . '..');
	}  
}