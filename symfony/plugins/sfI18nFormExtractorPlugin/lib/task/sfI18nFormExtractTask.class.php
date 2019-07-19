<?php 

/**
 * @author     Alexandru Emil Lupu <gang.alecs@gmail.com>
 */

require_once(dirname(__FILE__).'/../sfI18nFormExtract.class.php');

class sfI18nFormExtractTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
                  new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
                  new sfCommandArgument('culture', sfCommandArgument::REQUIRED, 'The target culture'),
    ));

    $this->addOptions(array(
                  new sfCommandOption('plugin',null, sfCommandOption::PARAMETER_OPTIONAL, 'Extract all the plugin form strings'),
                  new sfCommandOption('all',null, sfCommandOption::PARAMETER_NONE, 'Extract all the form strings no matter if is application or plugin'),
                  new sfCommandOption('form',null, sfCommandOption::PARAMETER_OPTIONAL, 'Extract all the form strings'),
                  new sfCommandOption('display-new', null, sfCommandOption::PARAMETER_NONE, 'Output all new found strings'),
                  new sfCommandOption('display-old', null, sfCommandOption::PARAMETER_NONE, 'Output all old strings'),
                  new sfCommandOption('auto-save', null, sfCommandOption::PARAMETER_NONE, 'Save the new strings'),
                  new sfCommandOption('auto-delete', null, sfCommandOption::PARAMETER_NONE, 'Delete old strings'),
    ));


    $this->namespace = 'i18n';
    $this->name = 'forms-extract';
    $this->briefDescription = 'Extracts i18n validation and label strings from forms';
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
    
    $extract = new sfI18nFormExtract(new $class($this->configuration, new sfNoCache(), $params), $arguments['culture']);

    /*
     * we initialize a default context in case the users call sfContext::getInstance in their forms
     */
    $conf = ProjectConfiguration::getApplicationConfiguration($arguments['application'], 'dev', 'false');
    sfContext::createInstance($conf);

    $sf_user = sfContext::getInstance()->getUser();
    $sf_user->setAuth(Doctrine_Core::getTable('User')->find(1));
    $sf_user->setCulture('en');


    if ($options['form'])
    {
      $this->logSection('i18n', sprintf('Parsing "%s" form', $options['form']));
      $extract->setForm($options['form']);
      $extract->extract();
    }
    if ($options['plugin'])
    {
      $this->processPlugin($options['plugin'],$extract);
    }
    if ($options['all'])
    {
      $this->processAll($extract);
    }
    
    
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
  private function processPlugin($plugin_name,sfI18nFormExtract $extract)
  {
    $this->logSection('i18n', sprintf('Process "%s" plugin', $plugin_name));
    $this->processFormsInDir(sfConfig::get('sf_plugins_dir')."/$plugin_name/lib/form/",$extract);
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