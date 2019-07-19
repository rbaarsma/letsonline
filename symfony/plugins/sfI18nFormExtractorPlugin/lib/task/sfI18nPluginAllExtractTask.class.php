<?php 

/**
 * @author     Geneviève Bastien <gbastien@versatic.net>
 */

require_once(dirname(__FILE__).'/../sfI18nFormExtract.class.php');
require_once(dirname(__FILE__).'/../sfI18nPluginExtract.class.php');

class sfI18nPluginAllExtractTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
                  new sfCommandArgument('plugin', sfCommandArgument::REQUIRED, 'The plugin to extract for'),
                  new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The main application'),
                  new sfCommandArgument('culture', sfCommandArgument::REQUIRED, 'The target culture'),
    ));

    $this->addOptions(array(
                  new sfCommandOption('display-new', null, sfCommandOption::PARAMETER_NONE, 'Output all new found strings'),
                  new sfCommandOption('display-old', null, sfCommandOption::PARAMETER_NONE, 'Output all old strings'),
                  new sfCommandOption('auto-save', null, sfCommandOption::PARAMETER_NONE, 'Save the new strings'),
                  new sfCommandOption('auto-delete', null, sfCommandOption::PARAMETER_NONE, 'Delete old strings'),
    ));


    $this->namespace = 'i18n';
    $this->name = 'plugin-extract';
    $this->briefDescription = 'Extracts i18n strings from forms and templates';
    $this->detailedDescription = <<<EOF
EOF;
}

  public function execute($arguments = array(), $options = array())
  {
    $this->logSection('i18n', sprintf('extracting i18n strings for the "%s" application', $arguments['application']));
    $config = sfFactoryConfigHandler::getConfiguration($this->configuration->getConfigPaths('config/factories.yml'));
    
    $class = $config['i18n']['class'];
    $params = $config['i18n']['param'];
    unset($params['cache']);
    
    
    /*
     * we initialize a default context in case the users call sfContext::getInstance in their forms
     */
    $config = ProjectConfiguration::getApplicationConfiguration($arguments['application'], 'prod', 'false');
    sfContext::createInstance($config);
    
    
    // Extract the plugin module's strings
    $extract = new sfI18nPluginExtract(new $class($this->configuration, new sfNoCache(), $params), $arguments['culture'], array('plugin' => $arguments['plugin']));
    
    $extract->extract();

    $this->logSection('i18n', sprintf('found "%d" new i18n strings', count($extract->getNewMessages())));
    $this->logSection('i18n', sprintf('found "%d" old i18n strings', count($extract->getOldMessages())));

    
    if ($options['display-new'])
    {
      $this->logSection('i18n', sprintf('display new i18n strings', count($extract->getOldMessages())));
      foreach ($extract->getNewMessages() as $message)
      {
        $this->log('               '.$message."\n");
      }
    }
    
    if ($options['auto-save'])
    {
      $this->logSection('i18n', 'saving new i18n strings');

      $extract->saveNewMessages();
    }

    if ($options['display-old'])
    {
      $this->logSection('i18n', sprintf('display old i18n strings', count($extract->getOldMessages())));
      foreach ($extract->getOldMessages() as $message)
      {
        $this->log('               '.$message."\n");
      }
    }

    if ($options['auto-delete'])
    {
      $this->logSection('i18n', 'deleting old i18n strings');

      $extract->deleteOldMessages();
    }
  }
  
  /**
   * This methos process all the forms in plugin dir
   *
   * @param string $plugin_name
   * @param sfI18nFormExtract $extract
   */
  private function processModulesInPlugin($plugin_name,sfI18nFormExtract $extract)
  {
    // Must extract for each module of the plugin
    $plugindir = sfConfig::get('sf_plugins_dir').'/'.$plugin_name .'/modules';
    $modules = sfFinder::type('dir')->maxDepth(0)->discard(array('.channels','.registry'))->in($plugindir);
    $this->logSection('i18n', sprintf('Found %s modules', count($modules)));
    foreach ($modules as $module) {
      $module = str_replace(sfConfig::get('sf_plugins_dir').'/'.$plugin_name .'/modules/','',$module);
      $this->logSection('i18n', sprintf('Process "%s" module', $module));
      $extract->setModule($module);
      $extract->extract();
    }
    $extract->setModule($module);
    $plugin_form_dir = sfConfig::get('sf_plugins_dir').'/'.$plugin_name .'/lib/form';
    $this->processFormsInDir($plugin_form_dir, $extract);
  }
  
  /**
   * Enter description here...
   *
   * @param string $dir
   * @param sfI18nFormExtract $extract
   */
  private function processFormsInDir($dir,sfI18nFormExtract $extract)
  {
    $forms = sfFinder::type('file')->name('*.php')->discard(array('BaseFormPropel.class.php'))->in($dir);
    $this->logSection('i18n', sprintf('Found %s forms', count($forms)));
    foreach ($forms as $form)
    {
      $form = basename($form);
      $this->logSection('i18n', sprintf('Parsing "%s" form', $form));
      $extract->setForm($form);
      $extract->extract();
    }
  }
  
  /**
   * Enter description here...
   *
   * @param sfI18nFormExtract $extract
   */
  private function processAll(sfI18nFormExtract $extract)
  {
    $this->logSection('i18n', sprintf('Process main %s/lib/form/ dir ', sfConfig::get('sf_root_dir')));
    $this->processFormsInDir(sfConfig::get('sf_root_dir').'/lib/form/',$extract);
    $this->logSection('i18n', sprintf('Process plugin %s dir ', sfConfig::get('sf_plugins_dir')));
    $plugins = sfFinder::type('dir')->maxDepth(0)->discard(array('.channels','.registry'))->in(sfConfig::get('sf_plugins_dir'));
    
    foreach ($plugins as $plugin)
    {
      $plugin = str_replace(sfConfig::get('sf_plugins_dir').'/','',$plugin);
      $this->processPlugin($plugin,$extract);
    }
  }

}