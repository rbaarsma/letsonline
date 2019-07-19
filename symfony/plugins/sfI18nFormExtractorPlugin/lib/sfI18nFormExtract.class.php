<?php

/**
 * @author     Alexandru Emil Lupu <gang.alecs@gmail.com>
 */
class sfI18nFormExtract extends sfI18nExtract
{
  protected $extractObjects = array();
  protected $messages = array();
  protected $form = null;

  public function setForm($form)
  {
    $form = str_replace('.class.php','',$form);
    if ('Form' != substr($form,-4,4))
    {
      $form .='Form';
    }
    if (class_exists($form))
    {
      $class = new ReflectionClass($form);
      if (!$class->isAbstract())
        $this->form = new $form();
      else
        $this->form = null;
    }
  }

  public function extract()
  {
    if (!is_null($this->form))
    {
      $this->registerErrorMessages();
      $this->processLabels();
      $this->processValues();
      $this->processHelp();
      $this->updateMessages($this->getFormMessages());
      $this->messages = array_unique($this->messages);
    }
  }

  private function getFormMessages(){
    return $this->messages;
  }

  private function addMessage($message)
  {
    if (is_array($message))
      foreach ($message as $key=>$val)
        $this->_addMessage($val ? $val : $key);
    else
      $this->_addMessage($message);
  }

  private function _addMessage($message)
  {
    if (preg_match("/(category_id)/", $message)) var_dump($message);
    $this->messages[] = $message;
  }

  private function addMessages($messages)
  {
    foreach ($messages as $message)
      $this->addMessage($message);
  }

  private function registerErrorMessages()
  {
    // initially begin with the validator's messages (also adds custom validation messages)
    $this->addMessages($this->form->getValidatorSchema()->getMessages());

    // TODO: is this still needed?
    $field_list = $this->form->getValidatorSchema()->getFields();
    foreach ($field_list as $field ){
      $this->merge($field);
    }
    $this->merge($this->form->getValidatorSchema()->getPostValidator());
    $this->merge($this->form->getValidatorSchema()->getPreValidator());
  }

  private function merge($field)
  {
    if (method_exists($field,'getMessages'))
      $this->addMessages($field->getMessages());

    if (method_exists($field,'getMessages') && method_exists($field,'getValidators'))
      foreach ($field->getValidators() as $f)
        $this->merge($f);
  }

  private function processLabels()
  {
    $labels = $this->form->getWidgetSchema()->getLabels();
    foreach ($labels as $key=>$value){
      if (empty($value))
        $this->addMessage($this->form->getWidgetSchema()->getFormFormatter()->generateLabelName($key));
      else
        $this->addMessage($value);
    }
  }
  
  private function processValues()
  {
    $widgetSchema = $this->form->getWidgetSchema()->getFields();
    foreach ($widgetSchema as $name => $widget) {
      if ($widget instanceof sfWidgetFormChoiceBase) {
        // unforunately there's no sfWidgetFormI18nBase or something, so we'll just
        // check the culture option, which should not be present if we need to extract
        if (!$widget->hasOption('culture') && !$widget->getAttribute('no_translation'))
          $this->addMessage($widget->getChoices());
      }
    }
  }
  
  private function processHelp()
  {
    $this->addMessage($this->form->getWidgetSchema()->getHelps());
  }
}