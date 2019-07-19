<?php

/**
 * @author: Rein Baarsma <rein@solidwebcode.com>
 */
class ValidatorCheckPasword extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * hash_object:    usually the $form->getObject()
   *  * hash_function:  the function of this model object that will hash the input such as 'getPasswordHash'
   *  * $password:      the password to check
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('hash_object');
    $this->addRequiredOption('hash_function');
    $this->addRequiredOption('password');
  }

  /**
   * @see sfValidatorBase
   */
  public function doClean($value)
  {
    // TODO: this shouldn't be like this..
    $old_pass = is_array($value) ? (isset($value['password_check']) ? $value['password_check'] : $value['password']) : $value;

    // may not be the most logical, but I want the empty validation done by another validator
    if (empty($old_pass))
      return $value;

    $obj = $this->getOption('hash_object');
    $fnc = $this->getOption('hash_function');
    $hash = call_user_func(array($obj, $fnc), $old_pass);

    $password = $this->getOption('password');

    if ($hash !== $password)
      throw new sfValidatorError($this, 'invalid');

    return $value;
  }
}


?>
