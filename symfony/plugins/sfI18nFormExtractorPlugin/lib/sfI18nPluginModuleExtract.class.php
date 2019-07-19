<?php

/**
 * @author     Geneviève Bastien <gbastien@versatic.net>
 */
class sfI18nPluginModuleExtract extends sfI18nExtract
{
  protected $plugin = '';
  protected $module = '';

  /**
   * Configures the current extract object.
   */
  public function configure()
  {
    if (!isset($this->parameters['plugin']))
    {
      throw new sfException('You must give a "plugin" parameter when extracting for a plugin module.');
    }
    
    if (!isset($this->parameters['module']))
    {
      throw new sfException('You must give a "module" parameter when extracting for a plugin module.');
    }
    $this->plugin = $this->parameters['plugin'];
    $this->module = $this->parameters['module'];

  }

  /**
   * Extracts i18n strings.
   *
   * This class must be implemented by subclasses.
   */
  public function extract()
  {
    // Extract from PHP files to find __() calls in actions/ lib/ and templates/ directories
    $moduleDir = sfConfig::get('sf_plugins_dir').'/'.$this->plugin.'/modules/' . $this->module;

    $this->extractFromPhpFiles(array(
      $moduleDir.'/actions',
      $moduleDir.'/lib',
      $moduleDir.'/templates',
    ));

    // Extract from generator.yml files
    $generator = $moduleDir.'/config/generator.yml';
    if (file_exists($generator))
    {
      $yamlExtractor = new sfI18nYamlGeneratorExtractor();
      $this->updateMessages($yamlExtractor->extract(file_get_contents($generator)));
    }

    // Extract from validate/*.yml files
    $validateFiles = glob($moduleDir.'/validate/*.yml');
    if (is_array($validateFiles))
    {
      foreach ($validateFiles as $validateFile)
      {
        $yamlExtractor = new sfI18nYamlValidateExtractor();
        $this->updateMessages($yamlExtractor->extract(file_get_contents($validateFile)));
      }
    }
  }
}