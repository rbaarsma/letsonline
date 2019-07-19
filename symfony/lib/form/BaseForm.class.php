<?php

/**
 * Base project form.
 * 
 * @package    letsonline
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
  public function setup()
  {
  }

  /*
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $fields = $this->getWidgetSchema()->getFields();
    foreach ($fields as $key=>$widget)
      if ($widget->getAttribute('disabled') == 'disabled' // only disabled fields
       && $this->getWidgetSchema()->getDefault($key) // only if they have default set
       && $this->getValidatorSchema()->offsetExists($key) // only if they have a validator
      )
        $taintedValues[$key] = $this->getWidgetSchema()->getDefault($key);
    
    parent::bind($taintedValues, $taintedFiles);
  }
  */
}
