<?php

/**
 * @author     Geneviève Bastien <gbastien@versatic.net>
 */
class sfI18nPluginExtract extends sfI18nExtract
{
  protected $plugin = '';
  
  public function setPlugin($plugin) {
    $this->plugin = $plugin;
    $this->i18n->setMessageSource(array_merge(array(sfConfig::get('sf_plugins_dir').'/'.$this->plugin.'/i18n'),$this->i18n->getConfiguration()->getI18NDirs($this->plugin)), $this->culture);
  }
  
  protected $extractObjects = array();

  /**
   * Configures the current extract object.
   */
  public function configure()
  {
    if (!isset($this->parameters['plugin']))
    {
      throw new sfException('You must give a "plugin" parameter when extracting for a plugin.');
    }

    $this->setPlugin($this->parameters['plugin']);

    $this->extractObjects = array();

    $plugindir = sfConfig::get('sf_plugins_dir') . '/' . $this->plugin;
    // Modules
    $moduleNames = sfFinder::type('dir')->maxdepth(0)->relative()->in($plugindir . '/modules');
    foreach ($moduleNames as $moduleName)
    {
      $this->extractObjects[] = new sfI18nPluginModuleExtract($this->i18n, $this->culture, array('module' => $moduleName, 'plugin' => $this->plugin));
    }
    
    $forms = sfFinder::type('file')->name('*.php')->discard(array('BaseFormPropel.class.php'))->in($plugindir . '/lib/form');
    foreach ($forms as $form)
    {
      $form = basename($form);
      $extractor = new sfI18nFormExtract($this->i18n, $this->culture);
      $extractor->setForm($form);
      $this->extractObjects[] = $extractor;
    }
  }

  /**
   * Extracts i18n strings.
   *
   * This class must be implemented by subclasses.
   */
  public function extract()
  {
    foreach ($this->extractObjects as $extractObject)
    {
      $extractObject->extract();
    }

    // Add global templates
    $this->extractFromPhpFiles(sfConfig::get('sf_plugins_dir') . '/'. $this->plugin . '/lib/model');
  }
  
  /**
   * Gets the current i18n strings.
   */
  public function getCurrentMessages()
  {
    return array_unique(array_merge($this->currentMessages, $this->aggregateMessages('getCurrentMessages')));
  }

  /**
   * Gets all i18n strings seen during the extraction process.
   */
  public function getAllSeenMessages()
  {
    return array_unique(array_merge($this->allSeenMessages, $this->aggregateMessages('getAllSeenMessages')));
  }

  protected function aggregateMessages($method)
  {
    $messages = array();
    foreach ($this->extractObjects as $extractObject)
    {
      $messages = array_merge($messages, $extractObject->$method());
    }

    return array_unique($messages);
  }
}