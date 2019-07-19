<?php

/**
 * User form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserPasswordForm extends BaseUserForm
{
  public function configure()
  {
    // only 1 field is used in the sense that it is updated
    $this->useFields(array('password'));
    $this->validatorSchema['password']->setOption('required', true);
    $this->validatorSchema['password']->setMessage('required', "Please provide a new password");

    // add password2 (repeat)
    $this->widgetSchema['password2'] = new sfWidgetFormInputPassword();
    $this->validatorSchema['password2'] = clone $this->validatorSchema['password'];
    $this->validatorSchema['password2']->setOption('required', false); // to not get 2 the same messages

    // add old password check
    $this->widgetSchema['password_check'] = new sfWidgetFormInputPassword();
    $this->validatorSchema['password_check'] = clone $this->validatorSchema['password'];
    $this->validatorSchema['password_check']->setMessage('required', "Type your old password");

    // clarify labels
    $this->getWidgetSchema()->setLabels(array(
      'password'        => "New Password",
      'password2'       => "New Password (again)",
      'password_check'  => "Current Password"
    ));

    // little clarification for password
    $this->getWidgetSchema()->setHelp('password_check', "Please verify you are the owner of this account.");

    // custom validator
    $pw_check_validator = new ValidatorCheckPasword(array(
          'hash_object'   => $this->getObject(),
          'hash_function' => 'getHashedPassword',
          'password'      => $this->getObject()->getPassword()
      ), array('invalid'=>"Incorrect password"));
    $this->mergePreValidator($pw_check_validator);

    // add compare validator
    $messages = array('invalid' => 'The two passwords must be the same.');
    $compare = new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password2', array(), $messages);
    $this->mergePostValidator($compare);

    // add confirm message
    $this->getValidatorSchema()->addMessage('confirm', 'Your password has been changed.');
  }
}
