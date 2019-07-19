<?php

/**
 * User form.
 *
 * @package    Lets Online
 * @subpackage form
 * @author     Rein Baarsma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserDataForm extends BaseUserForm
{ 
  public function configure()
  {
    $this->useFields(array(
      'email',
      'password',
      'first_name',
      'last_name',
      'address_line',
      'zip_code',
      'city',
      'country',
      'phone_home',
      'phone_work',
      'phone_mobile',
      'birth_date',
      'language'
    ));

    foreach (array('first_name','last_name') as $field)
      $this->getWidgetSchema()->addRequiredOption($field);

    $this->getWidget('email')->setAttribute('disabled', 'disabled');
    $this->getWidget('email')->setOption('default', $this->getObject()->getEmail());
    $this->getWidget('email')->setAttribute('value', $this->getObject()->getEmail());

    $this->getWidget('password')->setAttribute('disabled', 'disabled');

    unset($this->validatorSchema['email']);
    unset($this->validatorSchema['password']);

    $this->getValidatorSchema()->addMessage('confirm', 'Your personal data has been updated.');
  }
}
