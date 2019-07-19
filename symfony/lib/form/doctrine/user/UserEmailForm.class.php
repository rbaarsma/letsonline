<?php

/**
 * User form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserEmailForm extends BaseUserForm
{
  public function configure()
  {
    // from the base form we use only email and password
    $this->useFields(array('email', 'password'));

    // add cloned widget old_email
    $this->setWidget('old_email', clone $this->getWidget('email'));
    $this->getWidget('old_email')->setAttribute('disabled', 'disabled');
    $this->getWidget('old_email')->setAttribute('value', $this->getObject()->getEmail());

    // set email object to empty so the 'new_email' doens't have a value
    $this->getObject()->setEmail('');

    // create email2 and validator from email
    $this->setWidget('email2', clone $this->getWidget('email'));
    $this->setValidator('email2', clone $this->getValidator('email'));
    $this->validatorSchema['email2']->setOption('required', true);

    // add validator to password to check it against the current password
    $this->validatorSchema['password'] = new sfValidatorAnd(array(
      $this->validatorSchema['password'],
      new ValidatorCheckPasword(array(
          'hash_object'   => $this->getObject(),
          'hash_function' => 'getHashedPassword',
          'password'      => $this->getObject()->getPassword()
      ), array('invalid'=>"Incorrect password"))
    ));

    // clarify labels
    $this->getWidgetSchema()->setLabels(array(
      'email'         => "Current E-mail",
      'email'     => "New E-mail",
      'email2'    => "New E-mail (again)",
      'password'  => "Current Password"
    ));

    // little clarification for password
    $this->getWidgetSchema()->setHelp('password', "Please verify you are the owner of this account.");

    // add compare validator
    $messages = array('invalid' => 'The two e-mail addresses must be the same.');
    $compare = new sfValidatorSchemaCompare('email', sfValidatorSchemaCompare::EQUAL, 'email2', array(), $messages);
    $this->mergePostValidator($compare);

    // add confirm message
    $this->getValidatorSchema()->addMessage('confirm', 'Your e-mail address has been changed.');

    // show both e-mail fields first and password last
    $this->getWidgetSchema()->setPositions(array('id','old_email','email','email2','password'));
  }

  public function getEmailValue()
  {
    $val = $this['email']->getValue();
    return $val != $this->getObject()->getEmail() ? $val : "";
  }

  public function save($conn = null)
  {
    unset($this['old_email']);
    unset($this['email2']);
    unset($this['password']);

    // basically save only email
    return parent::save($conn);
  }
}
